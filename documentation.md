# Q.E.D — Complete Technical Reference & Developer Documentation
Welcome to the work in progress documentation for **[Qwidio/QED](https://github.com/Qwidio/QED)**, this manual is written for two types of people:
* **Core Maintainers & Contributors** who architect, audit, and extend the underlying routing, rendering, asset, database, and security engine.
* **Application Developers** who build pages, nested layouts, reusable UI components, static assets, JSON APIs, and full Create/Read/Update/Delete (CRUD) features on top of Q.E.D.

At the time of writing and until it changed, this docs is quite largely assisted with AI including phrasing and wording. It is already planned the part with too much AI to be rewritten sooner or later.

## Part I: Executive Overview & Architectural Evolution

### 1.1 Project Vision & Design Philosophy

[Qwidio/QED](https://github.com/Qwidio/QED) brings the developer ergonomics of the **Next.js App Router**—file-system routing, dynamic bracketed route segments (`[id]`), nested layouts (`layout.php`), co-located form mutations (Server Actions), extension-locked static asset helpers (`public_asset()` and `script_asset()`), and JSON API route handlers (`route.php`)—to native PHP with **zero external runtime dependencies**, **zero Node.js build pipelines**, and **zero exploitable file-inclusion surfaces**.

Core engineering principles:
1. **Zero-Config File-System Routing:** Dropping a `page.php` inside `apps/dashboard/settings/` immediately exposes `/dashboard/settings` without touching a central router configuration file.
2. **Inside-Out Output Buffering:** Unlike traditional PHP scripts that stream `<head>` HTML before executing page logic, Q.E.D. executes the target `page.php` first inside an output buffer (`ob_start()`), captures its output as `$children`, and wraps it outward through nested `layout.php` files up to [`layouts/main.php`](https://github.com/Qwidio/QED/blob/main/layouts/main.php).
3. **Secure by Default:** Every request passes through strict URL segment whitelisting, `realpath()` directory confinement, extension-locked `/public/` and `/script/` asset rules, automatic CSRF token verification on state-changing requests, and native prepared SQL execution (`mysqli::execute_query()`).

### 1.2 Baseline Audit of Legacy Version (Commit `20967c0`) vs. Upgraded Engine (All Commit after `20967c0`)

The initial commit of [Qwidio/QED](https://github.com/Qwidio/QED) established a minimalist front-controller skeleton across [`index.php`](https://github.com/Qwidio/QED/blob/main/index.php), [`config.php`](https://github.com/Qwidio/QED/blob/main/config.php), [`.htaccess`](https://github.com/Qwidio/QED/blob/main/.htaccess), [`layouts/main.php`](https://github.com/Qwidio/QED/blob/main/layouts/main.php), [`apps/app.php`](https://github.com/Qwidio/QED/blob/main/apps/app.php), and [`README.md`](https://github.com/Qwidio/QED/blob/main/README.md). To fulfill the Next.js-inspired directory vision sketched in [`README.md`](https://github.com/Qwidio/QED/blob/main/README.md) while hardening the runtime for production, the new architecture resolves six foundational limitations found in latest Commit `20967c0`(at the time of writing):

| Subsystem | Commit `20967c0` | Upgraded Q.E.D. Architecture (All Commit after `20967c0`) |
| :--- | :--- | :--- |
| **Route Resolution** | Hardcoded `$routes` associative array in [`index.php`](https://github.com/Qwidio/QED/blob/main/index.php) pointing to missing `/apps/about/app.php` and `/pages/404.php` files | Automated, LFI-safe directory traversal engine (`resolve_app_route()`) supporting `apps/**/page.php`, `[param]` folders, and `apps/404.php` |
| **Execution & Render Order** | [`layouts/main.php`](https://github.com/Qwidio/QED/blob/main/layouts/main.php) emits `<!DOCTYPE html>...<title>` *before* `require $meta_file;`, preventing pages from changing `$meta_title`, HTTP status codes, or redirecting | Leaf `page.php` executes first inside `ob_start()`; `$meta['title']`, `$meta['status']`, and `header('Location: ...')` execute before any layout HTML is emitted |
| **Direct Script Access** | [`.htaccess`](https://github.com/Qwidio/QED/blob/main/.htaccess) rule `RewriteCond %{REQUEST_FILENAME} !-f` lets browsers directly execute `/qed/config.php` or `/qed/layouts/main.php` | `.htaccess` explicitly blocks direct HTTP requests to `config.php`, `apps/`, `layouts/`, `components/`, and dotfiles (`403 Forbidden`) |
| **Static Asset Resolution** | `<link rel="stylesheet" href="../global.css">` in [`layouts/main.php`](https://github.com/Qwidio/QED/blob/main/layouts/main.php) breaks on nested URLs like `/qed/dashboard/settings`; no `/public/` or `/script/` helpers | Root-anchored stylesheet resolution (`<?= e($base_path) ?>/global.css`) plus extension-locked `public_asset()` (images) and `script_asset()` (JS) helpers |
| **Database & Credentials** | Plaintext `$db_pass` and `$connection` live in global scope in [`config.php`](https://github.com/Qwidio/QED/blob/main/config.php) and connect on every request | `.env`-backed `DB::query($sql, $params)` lazy singleton isolates credentials inside method scope and enforces prepared statements |
| **Session & Form Security** | Unconfigured `session_start()` in [`config.php`](https://github.com/Qwidio/QED/blob/main/config.php); no CSRF protection on form submissions | Hardened session cookies (`HttpOnly`, `SameSite=Lax`, `use_strict_mode=1`, conditional `Secure`) + automatic `verify_csrf()` gate |

## Part II: How Q.E.D. Differs from Traditional ("Normal") PHP & Next.js

### 2.1 The Architectural Shift from Traditional PHP

In traditional ("normal") procedural PHP development, the web server maps URLs directly to standalone `.php` scripts in the document root (`index.php`, `tasks.php`, `edit_task.php?id=5`, `delete_task.php`). That model creates recurring pain points as a project grows:
* **Repetitive Boilerplate:** Every single page file must manually `require_once '../config.php';` and `include '../includes/header.php';` at the top, plus `include '../includes/footer.php';` at the bottom.
* **The "Headers Already Sent" Trap:** If `header.php` is included at the top of a script, HTML output begins immediately. If query logic halfway down the file discovers a record does not exist or needs to redirect after a `POST`, calling `http_response_code(404)` or `header('Location: ...')` fails with `Warning: Cannot modify header information - headers already sent`.
* **Broken Relative Asset Paths:** Using `<img src="../public/logo/image.png">` works on `/dashboard/index.php` but breaks with a 404 on `/dashboard/settings/profile.php`.
* **Global Scope Pollution:** Variables declared in `config.php`, `header.php`, or an included partial (`$user`, `$id`, `$title`, `$row`) bleed into the global namespace and silently overwrite variables in the parent script.
* **Inconsistent Security Checks:** Each standalone script is responsible for remembering to start the session, validate CSRF tokens, cast query parameters, and prepare SQL statements.

Q.E.D. replaces that scattered multi-script model with a **Unified Front Controller + Inside-Out Component Pipeline** modeled after the Next.js App Router:

| Development Workflow | Traditional ("Normal") PHP | Next.js (App Router) | Q.E.D. (Upgraded PHP) |
| :--- | :--- | :--- | :--- |
| **Creating a Route** | Create `/about.php` and manually include `header.php` & `footer.php` | Create `app/about/page.tsx` | Create `apps/about/page.php` (layouts wrap automatically) |
| **Clean Dynamic URLs** | Query string `/task.php?id=42` or manual `.htaccess` regex rules | `app/tasks/[id]/page.tsx` (`params.id`) | `apps/tasks/[id]/page.php` (`$params['id']`) |
| **Dynamic Page `<title>`** | Must compute `$title` before `include 'header.php'`, forcing all logic above HTML | `generateMetadata()` | Mutate `$meta['title'] = '...'` anywhere inside `page.php` |
| **Redirects & 404 Status** | Breaks if called after `header.php` is included | `redirect()` / `notFound()` | Set `$meta['status'] = 404;` or call `header('Location: ...'); exit;` anywhere in `page.php` |
| **Nested Section Layouts** | Manual `include 'dashboard_sidebar.php'` in every dashboard script | `app/dashboard/layout.tsx` | Create `apps/dashboard/layout.php` wrapping `<?= $children ?>` |
| **Reusable UI Partials** | `include 'card.php'` (shares and mutates parent variables) | `<Card title="Hello" />` | `<?php component('card', ['title' => 'Hello']); ?>` (strictly isolated scope) |
| **Image & Script Assets** | Fragile `../../public/image.png` relative paths | `<Image src="/image.png" />` / `<Script src="/app.js" />` | `<?= public_asset('/logo/image.png') ?>` (images) & `<?= script_asset('/app.js') ?>` (JS) |
| **Form CSRF Protection** | Hand-rolled token check duplicated in every `POST` script | Built into Server Actions | Add `<?= csrf_field() ?>` in `<form>`; `index.php` verifies it automatically |
| **Parameterized SQL** | 4-5 lines of `$stmt = $conn->prepare(...)`, `bind_param()`, `execute()`, `get_result()` | ORM / Query Builder | Single call: `DB::query('SELECT * FROM tasks WHERE id = ?', [$id])` |
| **Build & Deployment** | Upload `.php` files | Node.js build step, bundler, SSR runtime | Upload `.php` files—zero build step, runs on any PHP 8.2+ host |

### 2.2 Side-by-Side Code Comparison: Traditional PHP vs. Q.E.D.

Consider viewing a single user profile and returning a proper `404 Not Found` status when the user ID does not exist.

**Traditional PHP (`profile.php?id=10`):**
```php
<?php
// Every file must manually locate and require config.php
require_once __DIR__ . '/includes/config.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Verbose 4-step prepared statement boilerplate
$stmt = $connection->prepare('SELECT username, bio FROM users WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Must check 404 BEFORE including header.php, otherwise http_response_code(404) and <title> fail
if (!$user) {
    http_response_code(404);
    $meta_title = 'User Not Found';
    include __DIR__ . '/includes/header.php';
    echo '<h1>404 — User Not Found</h1>';
    include __DIR__ . '/includes/footer.php';
    exit;
}

$meta_title = $user['username'] . ' — Profile';
include __DIR__ . '/includes/header.php';
?>
<h1><?php echo htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8'); ?></h1>
<p><?php echo htmlspecialchars($user['bio'], ENT_QUOTES, 'UTF-8'); ?></p>
<?php include __DIR__ . '/includes/footer.php'; ?>
```

**The Q.E.D. Way (`apps/users/[id]/page.php`):**
```php
<?php
// Clean URL: /qed/users/10 -> automatically provides $params['id'] = '10'
$user = DB::query('SELECT username, bio FROM users WHERE id = ?', [$params['id']])->fetch_assoc();

if (!$user) {
    $meta['status'] = 404;
    $meta['title']  = 'User Not Found';
    echo '<h1>404 — User Not Found</h1>';
    return; // Layouts wrap this output automatically with HTTP status 404!
}

$meta['title'] = $user['username'] . ' — Profile';
?>
<h1><?= e($user['username']) ?></h1>
<p><?= e($user['bio']) ?></p>
```

## Part III: Core Engine Internals & Security Specification (For Maintainers & Contributors)

### 3.1 Repository Directory Topology

```text
QED/
├── .env                        # Local environment secrets (excluded from git, blocked by .htaccess)
├── .env.example                # Environment variable template for contributors
├── .gitattributes              # Repository line-ending normalization
├── .gitignore                  # Ignores .env and editor artifacts
├── .htaccess                   # Apache rewrite gateway, internal directory lockdown & asset extension filter
├── LICENSE                     # Apache 2.0 License
├── README.md                   # Repository overview
├── documentation.md            # Unified technical & developer documentation
├── config.php                  # Session hardening, HTTP headers, DB singleton, CSRF, component & asset helpers
├── index.php                   # Next.js-style App Router & inside-out output buffering engine
├── global.css                  # Global stylesheet served from root
├── public/                     # Static image assets (strictly locked to image extensions)
│   ├── image.png
│   └── logo/
│       └── image.png
├── script/                     # Frontend JavaScript assets (strictly locked to .js / .mjs extensions)
│   ├── app.js
│   └── utils/
│       └── modal.js
├── components/                 # Reusable, scope-isolated UI partials
│   └── task-card.php
├── layouts/                    # Root HTML document shell
│   └── main.php
└── apps/                       # File-system routed pages, nested layouts, and JSON APIs
    ├── 404.php                 # Default 404 Not Found view
    ├── page.php                # "/" home route (backward-compatible with apps/app.php)
    ├── about/
    │   └── page.php            # "/about" static route
    ├── dashboard/
    │   ├── layout.php          # Nested layout wrapping all "/dashboard/*" pages
    │   ├── page.php            # "/dashboard" overview page
    │   └── settings/
    │       └── page.php        # "/dashboard/settings" page
    ├── tasks/
    │   ├── page.php            # "/tasks" route (List + POST Create action)
    │   └── [id]/
    │       └── page.php        # "/tasks/42" dynamic route (Read + POST Update/Delete actions)
    └── api/
        └── tasks/
            ├── route.php       # "/api/tasks" JSON endpoint (GET list, POST create)
            └── [id]/
                └── route.php   # "/api/tasks/42" JSON endpoint (GET one, DELETE one)
```

### 3.2 Deterministic 8-Stage Request Lifecycle

Maintainers modifying [`index.php`](https://github.com/Qwidio/QED/blob/main/index.php) or [`config.php`](https://github.com/Qwidio/QED/blob/main/config.php) must preserve the strict ordering of the 8-stage execution pipeline:

1. **Stage 1 — Apache Access, Asset Extension Filter & Rewrite Gate ([`.htaccess`](https://github.com/Qwidio/QED/blob/main/.htaccess)):**
   * Denies direct HTTP requests (`403 Forbidden`) to `config.php`, `apps/`, `layouts/`, `components/`, and any dotfile (`.env`, `.git`).
   * Enforces strict file-extension whitelists on `/public/` (only `.png`, `.jpg`, `.jpeg`, `.gif`, `.webp`, `.avif`, `.svg`, `.ico`) and `/script/` (only `.js`, `.mjs`), returning `403 Forbidden` if any other file type (such as `.php`) is requested from those directories.
   * Serves existing non-PHP static files directly from the filesystem.
   * Rewrites all remaining requests to `index.php [QSA,L]`.
2. **Stage 2 — Runtime Bootstrap & Session Hardening ([`config.php`](https://github.com/Qwidio/QED/blob/main/config.php)):**
   * Enforces `declare(strict_types=1);` and defines `$base_path` (default `'/qed'`).
   * Starts the PHP session with `use_strict_mode = 1`, `cookie_httponly = true`, `cookie_samesite = 'Lax'`, and `cookie_secure = true` (auto-detected over HTTPS).
   * Sends baseline defensive HTTP headers (`X-Content-Type-Options: nosniff`, `X-Frame-Options: SAMEORIGIN`, `Referrer-Policy: strict-origin-when-cross-origin`).
3. **Stage 3 — URI Normalization ([`index.php`](https://github.com/Qwidio/QED/blob/main/index.php)):**
   * Extracts `PHP_URL_PATH` from `$_SERVER['REQUEST_URI']`, strips the configurable `$base_path` prefix (`/qed`), and splits the remaining path into non-empty segments.
4. **Stage 4 — LFI-Safe Route Resolution (`resolve_app_route()`):**
   * Traverses `apps/` segment by segment, validating segment characters, matching static directories before `[param]` dynamic directories, collecting intermediate `layout.php` files, and locating either `route.php` (API) or `page.php` / `app.php` (Page).
5. **Stage 5 — Branch A: JSON API Route Dispatch (`route.php`):**
   * When `$match['type'] === 'api'`, sets `Content-Type: application/json; charset=utf-8`, loads the method map returned by `route.php`, executes the closure matching `$_SERVER['REQUEST_METHOD']`, serializes the response with `json_encode(..., JSON_THROW_ON_ERROR)`, and exits.
6. **Stage 6 — Branch B: Page CSRF Verification Gate (`verify_csrf()`):**
   * Before executing any page view on `POST`, `PUT`, `PATCH`, or `DELETE`, validates `$_POST['_csrf']` (or `HTTP_X_CSRF_TOKEN`) against `$_SESSION['_csrf']` via constant-time `hash_equals()`. Terminates with `403 Forbidden` on mismatch.
7. **Stage 7 — Leaf Page Execution Inside Output Buffer:**
   * Initializes `$meta = ['title' => 'QED', 'status' => 200]` (or `404` if unmatched).
   * Calls `ob_start()` and executes the resolved `page.php` inside a `static function` closure receiving `&$meta`, `$params`, and `$base_path`.
   * Captures rendered output into `$children = ob_get_clean()` and applies `http_response_code($meta['status'])`.
8. **Stage 8 — Inside-Out Nested Layout Composition:**
   * Iterates in reverse (`array_reverse()`) through any collected `apps/**/layout.php` files, wrapping `$children` from the deepest subdirectory outward.
   * Renders [`layouts/main.php`](https://github.com/Qwidio/QED/blob/main/layouts/main.php), injecting `<?= e($meta['title']) ?>` and `<?= $children ?>`.

### 3.3 Deep Dive: LFI-Proof File-System Routing Engine

File-system routers in PHP must never pass raw user input into `require` or `include`. `resolve_app_route()` enforces three independent security invariants so Local File Inclusion (LFI) and path traversal (`../../`) are impossible:

1. **Invariant 1 — Strict Alphanumeric Segment Whitelist:**
   Every URL segment is decoded once with `rawurldecode()` and tested against `/^[a-zA-Z0-9_-]+$/`. Any segment containing dots (`.`), slashes (`/`, `\`), null bytes (`\0`), or special characters immediately aborts traversal and returns `null` (`404 Not Found`).
2. **Invariant 2 — Canonical `realpath()` Boundary Confinement:**
   Every matched static or `[param]` directory is resolved with `realpath()` and verified with `str_starts_with($realCandidate, $realRoot . DIRECTORY_SEPARATOR)`. Even if a symlink exists inside `apps/`, traversal cannot escape the canonical `apps/` root directory.
3. **Invariant 3 — Fixed Terminal Script Names:**
   User input only selects *directories* that already exist inside `apps/`. The actual file passed to `require` is always a hardcoded string literal (`'route.php'`, `'page.php'`, `'app.php'`, or `'layout.php'`).

**Dynamic `[param]` Priority Rule:**
At each directory level, `resolve_app_route()` checks for an exact static folder match first (`is_dir($currentDir . '/' . $segment)`). Only if no static folder exists does it scan the current directory for a bracketed folder matching `/^\[([a-zA-Z0-9_]+)\]$/`. This matches Next.js specificity rules: `/tasks/archived` routes to `apps/tasks/archived/page.php` if present, while `/tasks/42` falls back to `apps/tasks/[id]/page.php` with `$params['id'] = '42'`.

### 3.4 Deep Dive: Inside-Out Output Buffering & Closure Scope Isolation

In the original [`index.php`](https://github.com/Qwidio/QED/blob/main/index.php), `require __DIR__ . '/layouts/main.php'` executed in global scope and then required `$meta_file` inside `<body>`. That design had two side effects:
1. Any variable created inside a page leaked into the global scope.
2. Pages could not modify `<head>` metadata (`$meta_title`), change HTTP status codes, or send `Location` redirect headers.

The upgraded engine wraps both page execution and nested layout execution inside anonymous `static` closures paired with `ob_start()`:

```php
ob_start();
(static function (string $__file, array &$meta, array $params, string $base_path): void {
    require $__file;
})($viewFile, $meta, $params, $base_path);
$children = ob_get_clean();
```

* **`array &$meta` (Passed by Reference):** Because `$meta` is passed by reference, modifying `$meta['title'] = 'Edit Task'` or `$meta['status'] = 404` inside `page.php` updates the outer `$meta` array before `http_response_code()` and `layouts/main.php` run.
* **`array $params` & `string $base_path` (Passed by Value):** Read-only route parameters and the base URL path are immediately available in every page and layout without `global` declarations.
* **Exception-Safe Buffering:** If a page issues `header("Location: {$base_path}/tasks"); exit;`, PHP flushes or discards the uncommitted buffer and sends the `302 Found` redirect header cleanly because no layout HTML has been echoed to the client yet.

### 3.5 Deep Dive: Lazy-Loaded Scoped Database Layer (`DB`)

The `DB` class in [`config.php`](https://github.com/Qwidio/QED/blob/main/config.php) replaces global `$connection` and plaintext `$db_pass` variables with a hardened static interface:

* **Zero-Dependency `.env` Parsing:** Reads `.env` using PHP's native C-level `parse_ini_file(__DIR__ . '/.env')`, avoiding the overhead of third-party Composer packages while keeping credentials out of Git history.
* **Credential Garbage Collection:** Database credentials live strictly as local variables inside `DB::connect()`. Once `new \mysqli(...)` succeeds, the local `$env` array falls out of scope and cannot be dumped by `print_r($GLOBALS)`.
* **Lazy Initialization:** Static pages (such as `/about`) never invoke `DB::connect()`, saving a MySQL socket connection on every non-database request.
* **Native Prepared Statements via `execute_query()`:** `DB::query(string $sql, array $params = [])` delegates directly to PHP 8.2+'s `mysqli::execute_query()`, which prepares, binds, and executes parameterized SQL in a single round-trip.

### 3.6 Deep Dive: Extension-Locked Static Asset Pipeline (`public_asset()` & `script_asset()`)

A common pitfall when building a custom PHP router is trying to use `__DIR__ . '/public/image.png'` inside an HTML `<img>` tag. Because `__DIR__` evaluates to an **operating system filesystem path** (such as `C:\xampp\htdocs\qed\public\image.png` or `/var/www/html/qed/public/image.png`), web browsers block it as a local `file://` resource. Browsers require a **root-anchored URL path** prefixed with `$base_path` (`/qed/public/image.png`).

To provide Next.js-style universal asset paths (`/image.png` or `/logo/image.png`) that work from any nested route depth while preventing arbitrary file disclosure or rogue script execution, Q.E.D. enforces a **Dual-Layer Extension Lock** across [`config.php`](https://github.com/Qwidio/QED/blob/main/config.php) and [`.htaccess`](https://github.com/Qwidio/QED/blob/main/.htaccess):

1. **Layer 1 — PHP Helper Validation (`public_asset()` & `script_asset()` in `config.php`):**
   * `public_asset(string $path)` strips leading slashes (`ltrim($path, '/')`), rejects any path containing `..` traversal sequences, and verifies that the filename ends with a safe image extension (`/\.(png|jpe?g|gif|webp|avif|svg|ico)$/i`). If valid, it returns the HTML-escaped URL `$base_path . '/public/' . $clean`; otherwise it fails closed and returns `''`.
   * `script_asset(string $path)` applies the same traversal guard to `/script/` and strictly enforces JavaScript extensions (`/\.(m?js)$/i`), returning `$base_path . '/script/' . $clean` or `''`.
2. **Layer 2 — Apache `.htaccess` Directory Extension Lockdown:**
   * Even if a user bypasses the HTML helpers and types `/qed/public/shell.php` or `/qed/script/config.bak` directly in their browser address bar, `.htaccess` intercepts all requests under `/public/` and `/script/` and returns `403 Forbidden` unless the URL ends in an allowed image extension (for `/public/`) or `.js`/`.mjs` extension (for `/script/`).

### 3.7 Security Threat Model & Maintainer Invariants

When contributing to or extending Q.E.D., maintainers must verify all changes against this threat matrix:

| Threat Category | Attack Scenario | Enforced Defense in Q.E.D. | Maintainer Invariant (Do Not Violate) |
| :--- | :--- | :--- | :--- |
| **Local File Inclusion (LFI)** | Requesting `/qed/..%2f..%2fetc/passwd` or passing `../../config` to `component()` | `/^[a-zA-Z0-9_-]+$/` regex + `realpath()` prefix check + fixed filenames | Never concatenate unvalidated request input into `require`, `include`, `file_get_contents`, or `component()`. |
| **Direct Script Execution** | Requesting `/qed/apps/tasks/page.php` or `/qed/.env` directly via HTTP | `.htaccess` rule `RewriteRule ^(?:config\.php\|apps/\|layouts/\|components/\|\.) - [F,L,NC]` | Keep all new internal directories (e.g., `middleware/`, `services/`) inside the `.htaccess` deny regex. |
| **Rogue File Execution in `/public/` or `/script/`** | Uploading or requesting `/qed/public/exploit.php` or passing `../config.php` to `public_asset()` | `public_asset()` / `script_asset()` regex + `.htaccess` extension lockdown on `/public/` and `/script/` | Never allow `.php`, `.phtml`, or `.inc` extensions inside `/public/` or `/script/` rules. |
| **SQL Injection (SQLi)** | Injecting `' OR 1=1 --` via `$_POST` or URL `$params` | `DB::query($sql, $params)` uses `mysqli::execute_query()` with `?` placeholders | Never interpolate PHP variables (`"WHERE id = $id"`) inside a SQL string; always pass values in the second `$params` array. |
| **Cross-Site Scripting (XSS)** | Submitting `<script>...</script>` in a task title or URL parameter | `e()` helper wraps `htmlspecialchars(..., ENT_QUOTES \| ENT_SUBSTITUTE, 'UTF-8')` | Escape every dynamic value echoed into HTML text or attributes with `<?= e($val) ?>`. Only `<?= $children ?>` in layouts is echoed raw. |
| **Cross-Site Request Forgery (CSRF)** | External site submitting a hidden `POST` form to `/qed/tasks/5` | Automatic `verify_csrf()` check in `index.php` using 256-bit `random_bytes(32)` token and `hash_equals()` | Never disable `verify_csrf()` for page routes; always include `<?= csrf_field() ?>` in every HTML `<form method="POST">`. |
| **Session Hijacking / Fixation** | Reading `document.cookie` via XSS or supplying an attacker-chosen `PHPSESSID` | `cookie_httponly=true`, `cookie_samesite=Lax`, `use_strict_mode=1`, `cookie_secure` on HTTPS | When implementing user login/logout, always call `session_regenerate_id(true)` immediately after privilege elevation. |

### 3.8 Complete Upgraded Core Source Code Reference

Maintainers and contributors can apply the following five files directly to upgrade [Qwidio/QED](https://github.com/Qwidio/QED).

#### 1. `.htaccess`
```apache
Options -Indexes
RewriteEngine On

# 1. Block direct HTTP access to config.php, internal folders, and dotfiles (.env, .git)
RewriteRule ^(?:config\.php|apps/|layouts/|components/|\.) - [F,L,NC]

# 2. Lock /public/ strictly to image extensions (blocks .php and non-image files)
RewriteCond %{REQUEST_URI} ^(?:/[^/]+)?/public/ [NC]
RewriteCond %{REQUEST_URI} !\.(png|jpe?g|gif|webp|avif|svg|ico)$ [NC]
RewriteRule ^ - [F,L]

# 3. Lock /script/ strictly to JavaScript extensions (.js, .mjs)
RewriteCond %{REQUEST_URI} ^(?:/[^/]+)?/script/ [NC]
RewriteCond %{REQUEST_URI} !\.(m?js)$ [NC]
RewriteRule ^ - [F,L]

# 4. Serve existing non-PHP static files (CSS, JS, images, fonts) directly
RewriteCond %{REQUEST_FILENAME} -f
RewriteCond %{REQUEST_URI} !\.(php|phtml|inc)$ [NC]
RewriteRule ^ - [L]

# 5. Route all remaining requests through the front controller
RewriteRule ^ index.php [QSA,L]
```

#### 2. `.env.example`
```ini
DB_HOST = "localhost"
DB_USER = "root"
DB_PASS = ""
DB_NAME = "qed"
```

#### 3. `config.php`
```php
<?php
declare(strict_types=1);

// Base URL path (set to '' if hosted at the domain root)
$base_path = '/qed';

// 1. Hardened Session Configuration
if (session_status() !== PHP_SESSION_ACTIVE) {
    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (int) ($_SERVER['SERVER_PORT'] ?? 80) === 443;

    session_start([
        'use_strict_mode' => 1,
        'cookie_httponly' => true,
        'cookie_secure'   => $isHttps,
        'cookie_samesite' => 'Lax',
    ]);
}

// 2. Baseline Defensive HTTP Headers
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('Referrer-Policy: strict-origin-when-cross-origin');

// 3. Lazy-Loaded Scoped Database Singleton
final class DB {
    private static ?\mysqli $conn = null;

    public static function connect(): \mysqli {
        if (self::$conn !== null) {
            return self::$conn;
        }
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $envFile = __DIR__ . '/.env';
        $env = is_file($envFile) ? (parse_ini_file($envFile) ?: []) : [];

        try {
            self::$conn = new \mysqli(
                (string) ($env['DB_HOST'] ?? 'localhost'),
                (string) ($env['DB_USER'] ?? 'root'),
                (string) ($env['DB_PASS'] ?? ''),
                (string) ($env['DB_NAME'] ?? 'qed')
            );
            self::$conn->set_charset('utf8mb4');
            return self::$conn;
        } catch (\mysqli_sql_exception $e) {
            error_log('failed to connect with db: ' . $e->getMessage());
            http_response_code(500);
            exit('Failed to connect with the server.');
        }
    }

    public static function query(string $sql, array $params = []): \mysqli_result|bool {
        return self::connect()->execute_query($sql, $params);
    }

    public static function lastId(): int|string {
        return self::connect()->insert_id;
    }

    public static function affectedRows(): int|string {
        return self::connect()->affected_rows;
    }
}

// 4. Context-Safe HTML Escaping Helper
function e(string|int|float|null $value): string {
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// 5. Extension-Locked Static Asset Helpers (/public/ for images, /script/ for JS)
function public_asset(string $path): string {
    global $base_path;
    $clean = ltrim($path, '/');

    if (str_contains($clean, '..') || !preg_match('/\.(png|jpe?g|gif|webp|avif|svg|ico)$/i', $clean)) {
        return '';
    }

    return e($base_path . '/public/' . $clean);
}

function script_asset(string $path): string {
    global $base_path;
    $clean = ltrim($path, '/');

    if (str_contains($clean, '..') || !preg_match('/\.(m?js)$/i', $clean)) {
        return '';
    }

    return e($base_path . '/script/' . $clean);
}

// 6. Cryptographic CSRF Protection Helpers
function csrf_token(): string {
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf'];
}

function csrf_field(): string {
    return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
}

function verify_csrf(): void {
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    if (in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
        $token = $_POST['_csrf'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        if (!is_string($token) || !hash_equals(csrf_token(), $token)) {
            http_response_code(403);
            exit('Forbidden: Invalid CSRF token.');
        }
    }
}

// 7. Scope-Isolated UI Component Loader
function component(string $name, array $props = []): void {
    if (!preg_match('/^[a-zA-Z0-9_\/-]+$/', $name)) {
        throw new \InvalidArgumentException('Invalid component name.');
    }
    $baseDir = realpath(__DIR__ . '/components');
    $target  = realpath(__DIR__ . '/components/' . $name . '.php');
    if ($baseDir === false || $target === false || !str_starts_with($target, $baseDir . DIRECTORY_SEPARATOR)) {
        throw new \RuntimeException("Component not found: {$name}");
    }
    (static function (string $__file, array $__props): void {
        extract($__props, EXTR_SKIP);
        require $__file;
    })($target, $props);
}
```

#### 4. `index.php`
```php
<?php
declare(strict_types=1);
require_once __DIR__ . '/config.php';

$request_path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
if ($base_path !== '' && ($request_path === $base_path || str_starts_with($request_path, $base_path . '/'))) {
    $request_path = substr($request_path, strlen($base_path));
}

$segments = array_values(array_filter(explode('/', trim($request_path, '/')), 'strlen'));

/**
 * Resolves URL segments inside /apps supporting static folders, [param] folders,
 * nested layout.php files, page.php views, and route.php JSON endpoints.
 */
function resolve_app_route(string $appsRoot, array $segments): ?array {
    $realRoot = realpath($appsRoot);
    if ($realRoot === false) {
        return null;
    }

    $currentDir = $realRoot;
    $params = [];
    $layouts = [];

    if (is_file($currentDir . DIRECTORY_SEPARATOR . 'layout.php')) {
        $layouts[] = $currentDir . DIRECTORY_SEPARATOR . 'layout.php';
    }

    foreach ($segments as $segment) {
        $segment = rawurldecode($segment);
        if (!preg_match('/^[a-zA-Z0-9_-]+$/', $segment)) {
            return null;
        }

        $staticCandidate = $currentDir . DIRECTORY_SEPARATOR . $segment;
        $realStatic = is_dir($staticCandidate) ? realpath($staticCandidate) : false;

        if ($realStatic !== false && str_starts_with($realStatic, $realRoot . DIRECTORY_SEPARATOR)) {
            $currentDir = $realStatic;
        } else {
            $matchedDynamic = null;
            foreach (scandir($currentDir) ?: [] as $entry) {
                if (preg_match('/^\[([a-zA-Z0-9_]+)\]$/', $entry, $matches)) {
                    $candidate = realpath($currentDir . DIRECTORY_SEPARATOR . $entry);
                    if ($candidate !== false && is_dir($candidate) && str_starts_with($candidate, $realRoot . DIRECTORY_SEPARATOR)) {
                        $matchedDynamic = $candidate;
                        $params[$matches[1]] = $segment;
                        break;
                    }
                }
            }
            if ($matchedDynamic === null) {
                return null;
            }
            $currentDir = $matchedDynamic;
        }

        if (is_file($currentDir . DIRECTORY_SEPARATOR . 'layout.php')) {
            $layouts[] = $currentDir . DIRECTORY_SEPARATOR . 'layout.php';
        }
    }

    if (is_file($currentDir . DIRECTORY_SEPARATOR . 'route.php')) {
        return [
            'type'   => 'api',
            'file'   => $currentDir . DIRECTORY_SEPARATOR . 'route.php',
            'params' => $params,
        ];
    }

    foreach (['page.php', 'app.php'] as $viewFile) {
        $candidate = $currentDir . DIRECTORY_SEPARATOR . $viewFile;
        if (is_file($candidate)) {
            return [
                'type'    => 'page',
                'file'    => $candidate,
                'params'  => $params,
                'layouts' => $layouts,
            ];
        }
    }

    return null;
}

$match = resolve_app_route(__DIR__ . '/apps', $segments);

// 1. Dispatch JSON API Route Handlers (apps/**/route.php)
if ($match !== null && $match['type'] === 'api') {
    header('Content-Type: application/json; charset=utf-8');
    $params = $match['params'];
    $handlers = require $match['file'];
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

    if (is_array($handlers) && isset($handlers[$method]) && is_callable($handlers[$method])) {
        echo json_encode($handlers[$method]($params), JSON_THROW_ON_ERROR);
        exit;
    }

    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

// 2. Enforce Automatic CSRF Verification on State-Changing Page Requests
verify_csrf();

// 3. Execute Leaf Page Inside Output Buffer
$meta = [
    'title'  => 'QED',
    'status' => $match !== null ? 200 : 404,
];
$params = $match['params'] ?? [];
$viewFile = $match['file'] ?? (__DIR__ . '/apps/404.php');

if ($match === null) {
    $meta['title'] = 'Not Found';
}

ob_start();
if (is_file($viewFile)) {
    (static function (string $__file, array &$meta, array $params, string $base_path): void {
        require $__file;
    })($viewFile, $meta, $params, $base_path);
} else {
    echo '<h1>404 — Page Not Found</h1><p>The requested resource could not be found.</p>';
}
$children = ob_get_clean();

http_response_code($meta['status']);

// 4. Wrap Nested Layouts from Innermost to Outermost
foreach (array_reverse($match['layouts'] ?? []) as $layoutFile) {
    ob_start();
    (static function (string $__layout, string $children, array $meta, string $base_path): void {
        require $__layout;
    })($layoutFile, $children, $meta, $base_path);
    $children = ob_get_clean();
}

// 5. Render Root Layout
require __DIR__ . '/layouts/main.php';
```

#### 5. `layouts/main.php`
```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= e($base_path) ?>/global.css">
    <script src="<?= script_asset('/app.js') ?>" defer></script>
    <title><?= e($meta['title'] ?? 'QED') ?></title>
</head>
<body>
    <?= $children ?>
</body>
</html>
```

## Part IV: Application Developer Guide — Routing, Layouts, Assets, Components & Full CRUD

### 4.1 Local Environment Setup

1. Clone [Qwidio/QED](https://github.com/Qwidio/QED) into your Apache document root (e.g., `htdocs/qed` or `/var/www/html/qed`). If you host Q.E.D. at the root domain (`/`) instead of `/qed`, set `$base_path = '';` in `config.php`.
2. Copy `.env.example` to `.env` and configure your MySQL credentials:
   ```ini
   DB_HOST = "localhost"
   DB_USER = "root"
   DB_PASS = ""
   DB_NAME = "qed"
   ```
3. Ensure Apache `mod_rewrite` is enabled and `AllowOverride All` is set for your directory.

### 4.2 Creating Pages, Dynamic Routes & Nested Layouts

#### Static Pages (`apps/**/page.php`)
Every directory inside `apps/` containing a `page.php` file maps directly to a URL path:
* `apps/page.php` -> `GET /qed/`
* `apps/about/page.php` -> `GET /qed/about`
* `apps/dashboard/settings/page.php` -> `GET /qed/dashboard/settings`

Inside any `page.php`, three variables are automatically injected:
* `$meta` (`array`, passed by reference): Set `$meta['title'] = 'About Us';` or `$meta['status'] = 404;`.
* `$params` (`array`): Dynamic route parameters extracted from bracketed folder names.
* `$base_path` (`string`): Your configured base URL prefix (e.g., `'/qed'`), used for building links and redirects.

#### Dynamic Route Segments (`apps/**/[param]/page.php`)
Wrap a folder name in square brackets (`[id]`, `[slug]`, `[category]`) to capture dynamic URL segments:
* File path: `apps/blog/[slug]/page.php`
* Request URL: `/qed/blog/building-with-qed`
* Available inside `page.php`: `$params['slug'] === 'building-with-qed'`

You can also nest multiple dynamic segments, such as `apps/projects/[projectId]/tasks/[taskId]/page.php`, which populates both `$params['projectId']` and `$params['taskId']`.

#### Nested Layouts (`apps/**/layout.php`)
Just like Next.js, placing a `layout.php` inside a route folder wraps every `page.php` inside that folder and its subfolders. Create `apps/dashboard/layout.php`:

```php
<div class="dashboard-shell">
    <nav class="dashboard-sidebar">
        <h3>Dashboard</h3>
        <ul>
            <li><a href="<?= e($base_path) ?>/dashboard">Overview</a></li>
            <li><a href="<?= e($base_path) ?>/dashboard/settings">Settings</a></li>
        </ul>
    </nav>
    <main class="dashboard-content">
        <?= $children ?>
    </main>
</div>
```

When a user visits `/qed/dashboard/settings`, Q.E.D. renders `apps/dashboard/settings/page.php` first, passes its HTML as `$children` into `apps/dashboard/layout.php`, and then passes that combined HTML as `$children` into [`layouts/main.php`](https://github.com/Qwidio/QED/blob/main/layouts/main.php).

### 4.3 Building Scope-Isolated UI Components (`components/*.php`)

To keep pages clean and DRY without leaking variables across templates, store reusable UI blocks inside `components/` and invoke them with `component('name', $props)`.

Create `components/task-card.php`:
```php
<li class="task-card">
    <a href="<?= e($base_path) ?>/tasks/<?= e($task['id']) ?>">
        <?php if (!empty($task['is_done'])): ?>
            <s><?= e($task['title']) ?></s> <span>(Completed)</span>
        <?php else: ?>
            <strong><?= e($task['title']) ?></strong>
        <?php endif; ?>
    </a>
    <small>Created: <?= e($task['created_at']) ?></small>
</li>
```

Render it inside any page:
```php
<?php foreach ($tasks as $task): ?>
    <?php component('task-card', ['task' => $task, 'base_path' => $base_path]); ?>
<?php endforeach; ?>
```

### 4.4 Managing Static Images (`/public/`) & Frontend Scripts (`/script/`)

In Q.E.D., static images live inside `public/` and frontend JavaScript files live inside `script/`:

```text
QED/
├── public/
│   ├── image.png
│   └── logo/
│       └── image.png
└── script/
    ├── app.js
    └── utils/
        └── modal.js
```

Rather than writing fragile relative paths (`../../public/logo/image.png`) that break when routes are nested, or using `__DIR__` (which outputs a local hard-drive path that browsers block), use `public_asset()` and `script_asset()` from any layout, page, or component:

```html
<!-- Referencing images inside /public/ (supports nested folders) -->
<img src="<?= public_asset('/image.png') ?>" alt="Hero Banner">
<img src="<?= public_asset('/logo/image.png') ?>" alt="Brand Logo">

<!-- Referencing JavaScript files inside /script/ (supports nested folders) -->
<script src="<?= script_asset('/app.js') ?>" defer></script>
<script src="<?= script_asset('/utils/modal.js') ?>" defer></script>
```

**Built-In Security Guards on Asset Helpers:**
* `public_asset()` only accepts image extensions (`.png`, `.jpg`, `.jpeg`, `.gif`, `.webp`, `.avif`, `.svg`, `.ico`).
* `script_asset()` only accepts JavaScript extensions (`.js`, `.mjs`).
* Passing a non-matching extension (e.g., `public_asset('/config.php')`) or a directory traversal sequence (`script_asset('../index.php')`) automatically fails closed and returns an empty string `""`.

### 4.5 Complete Step-by-Step CRUD Guide (Task Manager)

Below is a complete, production-grade **Create, Read, Update, and Delete (CRUD)** implementation in Q.E.D. showing how database queries, CSRF protection, dynamic routes, and Post/Redirect/Get (PRG) mutations fit together.

#### Step 1: Create the Database Schema
Run the following SQL statement in your `qed` database:

```sql
CREATE TABLE IF NOT EXISTS tasks (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    is_done TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Step 2: List Tasks (READ) & Create Task (CREATE) in `apps/tasks/page.php`
In Q.E.D., you co-locate the `POST` form handler at the top of the same `page.php` that renders the list—just like a Next.js Server Action. Because `index.php` runs `verify_csrf()` before executing `page.php`, CSRF validation is already enforced automatically.

Create `apps/tasks/page.php`:
```php
<?php
$meta['title'] = 'Tasks — QED';
$error = null;

// --- CREATE (Handles POST /qed/tasks) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = trim((string) ($_POST['title'] ?? ''));
    $description = trim((string) ($_POST['description'] ?? ''));

    if ($title === '') {
        $error = 'Task title is required.';
        $meta['status'] = 422;
    } else {
        DB::query(
            'INSERT INTO tasks (title, description) VALUES (?, ?)',
            [$title, $description]
        );
        // Post/Redirect/Get prevents duplicate submissions on browser refresh
        header("Location: {$base_path}/tasks");
        exit;
    }
}

// --- READ (Handles GET /qed/tasks with optional status filter) ---
$filter = $_GET['filter'] ?? 'all';
if ($filter === 'pending') {
    $tasks = DB::query('SELECT * FROM tasks WHERE is_done = ? ORDER BY id DESC', [0])->fetch_all(MYSQLI_ASSOC);
} elseif ($filter === 'done') {
    $tasks = DB::query('SELECT * FROM tasks WHERE is_done = ? ORDER BY id DESC', [1])->fetch_all(MYSQLI_ASSOC);
} else {
    $tasks = DB::query('SELECT * FROM tasks ORDER BY id DESC')->fetch_all(MYSQLI_ASSOC);
}
?>

<main class="container">
    <h1>Task Manager</h1>

    <?php if ($error !== null): ?>
        <p role="alert" style="color: #b91c1c;"><?= e($error) ?></p>
    <?php endif; ?>

    <!-- Create Form -->
    <form method="POST" action="<?= e($base_path) ?>/tasks" style="margin-bottom: 2rem;">
        <?= csrf_field() ?>
        <div>
            <label for="title">Task Title</label><br>
            <input id="title" type="text" name="title" placeholder="Ship Q.E.D" required>
        </div>
        <div style="margin-top: 0.5rem;">
            <label for="description">Description (Optional)</label><br>
            <textarea id="description" name="description" rows="2"></textarea>
        </div>
        <button type="submit" style="margin-top: 0.5rem;">Create Task</button>
    </form>

    <!-- Filter Navigation -->
    <nav style="margin-bottom: 1rem;">
        Filter:
        <a href="<?= e($base_path) ?>/tasks?filter=all">All</a> |
        <a href="<?= e($base_path) ?>/tasks?filter=pending">Pending</a> |
        <a href="<?= e($base_path) ?>/tasks?filter=done">Completed</a>
    </nav>

    <!-- Task List -->
    <?php if (empty($tasks)): ?>
        <p>No tasks match this filter.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($tasks as $task): ?>
                <?php component('task-card', ['task' => $task, 'base_path' => $base_path]); ?>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</main>
```

#### Step 3: View, Edit (UPDATE) & Remove (DELETE) a Task in `apps/tasks/[id]/page.php`
Create the bracketed directory `apps/tasks/[id]/` and place `page.php` inside it. Any request to `/qed/tasks/12` populates `$params['id'] = '12'`.

Create `apps/tasks/[id]/page.php`:
```php
<?php
$taskId = (int) ($params['id'] ?? 0);

// 1. Fetch existing record
$task = DB::query('SELECT * FROM tasks WHERE id = ?', [$taskId])->fetch_assoc();

// 2. Return clean 404 if record does not exist
if (!$task) {
    $meta['status'] = 404;
    $meta['title']  = 'Task Not Found — QED';
    echo '<h1>404 — Task Not Found</h1>';
    echo '<p><a href="' . e($base_path) . '/tasks">&larr; Back to Tasks</a></p>';
    return;
}

$error = null;

// 3. Handle UPDATE and DELETE mutations on POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['_action'] ?? 'update';

    if ($action === 'delete') {
        // --- DELETE ---
        DB::query('DELETE FROM tasks WHERE id = ?', [$taskId]);
        header("Location: {$base_path}/tasks");
        exit;
    }

    if ($action === 'update') {
        // --- UPDATE ---
        $title       = trim((string) ($_POST['title'] ?? ''));
        $description = trim((string) ($_POST['description'] ?? ''));
        $isDone      = isset($_POST['is_done']) ? 1 : 0;

        if ($title === '') {
            $error = 'Task title cannot be empty.';
            $meta['status'] = 422;
        } else {
            DB::query(
                'UPDATE tasks SET title = ?, description = ?, is_done = ? WHERE id = ?',
                [$title, $description, $isDone, $taskId]
            );
            header("Location: {$base_path}/tasks");
            exit;
        }
    }
}

$meta['title'] = 'Edit: ' . $task['title'];
?>

<main class="container">
    <p><a href="<?= e($base_path) ?>/tasks">&larr; Back to Tasks</a></p>
    <h1>Edit Task #<?= e($task['id']) ?></h1>

    <?php if ($error !== null): ?>
        <p role="alert" style="color: #b91c1c;"><?= e($error) ?></p>
    <?php endif; ?>

    <!-- UPDATE FORM -->
    <form method="POST" style="margin-bottom: 1.5rem;">
        <?= csrf_field() ?>
        <input type="hidden" name="_action" value="update">

        <div style="margin-bottom: 0.75rem;">
            <label for="title">Title</label><br>
            <input id="title" type="text" name="title" value="<?= e($task['title']) ?>" required>
        </div>

        <div style="margin-bottom: 0.75rem;">
            <label for="description">Description</label><br>
            <textarea id="description" name="description" rows="3"><?= e($task['description'] ?? '') ?></textarea>
        </div>

        <div style="margin-bottom: 0.75rem;">
            <label>
                <input type="checkbox" name="is_done" value="1" <?= $task['is_done'] ? 'checked' : '' ?>>
                Completed
            </label>
        </div>

        <button type="submit">Save Changes</button>
    </form>

    <!-- DELETE FORM -->
    <form method="POST" onsubmit="return confirm('Permanently delete this task?');">
        <?= csrf_field() ?>
        <input type="hidden" name="_action" value="delete">
        <button type="submit" style="color: #b91c1c;">Delete Task</button>
    </form>
</main>
```

#### Step 4: Expose a JSON API Endpoint (`apps/api/tasks/route.php`)
To expose your tasks as a RESTful JSON API for frontend `fetch()` calls or external clients, create `apps/api/tasks/route.php`:

```php
<?php
return [
    'GET' => static function (array $params): array {
        $tasks = DB::query('SELECT id, title, description, is_done, created_at FROM tasks ORDER BY id DESC')
            ->fetch_all(MYSQLI_ASSOC);
        return ['ok' => true, 'tasks' => $tasks];
    },

    'POST' => static function (array $params): array {
        verify_csrf(); // Optional if using session cookie auth for AJAX calls
        $payload = json_decode(file_get_contents('php://input') ?: '{}', true) ?? [];
        $title = trim((string) ($payload['title'] ?? ''));

        if ($title === '') {
            http_response_code(422);
            return ['ok' => false, 'error' => 'Title is required'];
        }

        DB::query('INSERT INTO tasks (title) VALUES (?)', [$title]);
        http_response_code(201);
        return ['ok' => true, 'id' => DB::lastId()];
    },
];
```

### 4.6 Developer & Maintainer Quick-Reference Cheat Sheet

| Common Task | Q.E.D. Idiom |
| :--- | :--- |
| **Escape any variable in HTML** | `<?= e($value) ?>` |
| **Load an image from `/public/`** | `<img src="<?= public_asset('/logo/image.png') ?>" alt="Logo">` |
| **Load a JS file from `/script/`** | `<script src="<?= script_asset('/utils/modal.js') ?>" defer></script>` |
| **Add CSRF protection to a `<form>`** | `<?= csrf_field() ?>` |
| **Send CSRF token via `fetch()` header** | `'X-CSRF-Token': '<?= e(csrf_token()) ?>'` |
| **Change page `<title>` dynamically** | `$meta['title'] = 'Custom Title';` |
| **Return a `404` or `422` HTTP status** | `$meta['status'] = 404;` |
| **Read a `[slug]` URL parameter** | `$slug = $params['slug'];` |
| **Fetch all rows (`SELECT`)** | `$rows = DB::query('SELECT * FROM tasks WHERE is_done = ?', [0])->fetch_all(MYSQLI_ASSOC);` |
| **Fetch a single row (`SELECT`)** | `$row = DB::query('SELECT * FROM tasks WHERE id = ?', [$id])->fetch_assoc();` |
| **Insert a row & get `insert_id`** | `DB::query('INSERT INTO tasks (title) VALUES (?)', [$title]); $newId = DB::lastId();` |
| **Update / Delete & get affected count** | `DB::query('DELETE FROM tasks WHERE id = ?', [$id]); $count = DB::affectedRows();` |
| **Redirect cleanly inside `page.php`** | `header("Location: {$base_path}/tasks"); exit;` |
| **Render an isolated component** | `<?php component('task-card', ['task' => $task, 'base_path' => $base_path]); ?>` |