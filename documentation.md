# Q.E.D — Complete Technical Reference & Developer Documentation
Welcome to the work in progress documentation for **[Qwidio/QED](https://github.com/Qwidio/QED)**, this docs is written for two types of people:
* **Maintainers & Contributors** who want to understand, check, or modify the core routing, layout buffering, asset helpers, and security setup.
* **Developers** who want to build pages, nested layouts, components, JSON APIs, and CRUD features using this project.

At the time of writing and until it changed, this docs is in some part assisted with AI including phrasing and wording. It is already planned the part with too much AI to be rewritten sooner or later.

## About the projects

QED in it's simple term is a recreation of the **Next.js App Router** built in pure PHP. 
The goal is to have folder-based routing (`apps/**/app.php` or `page.php`), dynamic `[id]` folders, nested layouts (`layout.php`), co-located `POST` form actions, and **Tailwind CSS** styling—without needing a Node.js runtime on your production server and without leaving Local File Inclusion (LFI) holes open.


### What changed from the initial commit (`20967c0`)?

| Subsystem | Commit `20967c0` (Old) | Current Q.E.D Setup |
| :--- | :--- | :--- |
| **Routing** | Hardcoded `$routes` array in [index.php](https://www.google.com/search?q=https://github.com/Qwidio/QED/blob/main/index.php) pointing to missing `/apps/about/app.php` and `/pages/404.php` files | Automatic folder-based routing (`resolve_app_route()`) supporting `apps/**/app.php`, `page.php`, `[param]` folders, and `apps/404.php` |
| **Render Order** | [layouts/main.php](https://www.google.com/search?q=https://github.com/Qwidio/QED/blob/main/layouts/main.php) echoed `<head>` and `<title>` *before* loading the page, causing `"Headers already sent"` errors on redirects | Page runs *first* inside `ob_start()`; pages can change `$meta['title']`, return a `404` status, or redirect before any layout HTML is sent |
| **Direct File Access** | \[suspicious link removed\] let anyone open `/qed/config.php` or `/qed/layouts/main.php` straight from the browser | `.htaccess` blocks direct browser access to `config.php`, `apps/`, `layouts/`, `components/`, `src/`, `package.json`, `tailwind.config.js`, and dotfiles (`403 Forbidden`) |
| **Static Assets** | `<link rel="stylesheet" href="../global.css">` broke on deeper URLs like `/qed/dashboard/settings` | Root-relative `global.css` path plus `public_asset()` (locked to images) and `script_asset()` (locked to `.js`/`.mjs`) |
| **Styling** | Huge custom CSS file (`global.css`) with custom class names and no editor autocomplete | Official **Tailwind CSS** (`src/input.css` + `tailwind.config.js` -> compiled [global.css](https://www.google.com/search?q=https://github.com/Qwidio/QED/blob/main/global.css)) with full VS Code autocomplete |
| **Database & `.env`** | Plaintext `$db_pass` and `$connection` sitting in global variables in [config.php](https://www.google.com/search?q=https://github.com/Qwidio/QED/blob/main/config.php) | `.env` credentials loaded inside a `DB::query($sql, $params)` wrapper using prepared statements (`mysqli::execute_query()`) |
| **Sessions & Forms** | Basic `session_start()` with no CSRF protection | `HttpOnly` + `SameSite=Lax` + `use_strict_mode` session cookies and automatic `verify_csrf()` check on `POST` requests |

## How it compares with normal PHP & Next.js

In normal procedural PHP, every page is a separate file in the root folder (`tasks.php`, `edit_task.php?id=5`), and you have to manually `require 'config.php'` and `include 'header.php'` at the top of every single file. If you try to redirect or set a 404 status after `header.php` is included, PHP throws the `"Cannot modify header information - headers already sent"` warning.

Here is how Q.E.D handles common tasks compared to normal PHP and Next.js:

| Task | Normal PHP | Next.js (App Router) | Q.E.D |
| :--- | :--- | :--- | :--- |
| **Making a new page** | Create `/about.php` and manually include `header.php` & `footer.php` | Create `app/about/page.tsx` | Create `apps/about/app.php` or `page.php` (layouts wrap it automatically) |
| **Dynamic URLs** | `/task.php?id=42` or manual `.htaccess` regex | `app/tasks/[id]/page.tsx` (`params.id`) | `apps/tasks/[id]/app.php` (`$params['id']`) |
| **Changing `<title>`** | Must set `$title` before `include 'header.php'` | `generateMetadata()` | Set `$meta['title'] = '...'` anywhere inside your page |
| **Redirects & 404s** | Fails if called after `header.php` is included | `redirect()` / `notFound()` | Set `$meta['status'] = 404;` or call `header('Location: ...'); exit;` anywhere in the page |
| **Nested Layouts** | Manual `include 'sidebar.php'` in every file | `app/dashboard/layout.tsx` | Put `layout.php` inside `apps/dashboard/` wrapping `<?= $children ?>` |
| **Reusable Components** | `include 'card.php'` (shares global variables) | `<Card title="Hello" />` | `<?php component('card', ['title' => 'Hello']); ?>` (isolated scope) |
| **Images & JS** | Relative paths (`../../public/image.png`) that break on nested URLs | `<Image />` / `<Script />` | `<?= public_asset('/logo/image.png') ?>` & `<?= script_asset('/app.js') ?>` |
| **CSRF Protection** | Manual token check copied into every form script | Built into Server Actions | Put `<?= csrf_field() ?>` inside `<form>`; `index.php` checks it automatically |
| **SQL Queries** | 4-5 lines of `prepare()`, `bind_param()`, `execute()`, `get_result()` | ORM / Query Builder | One line: `DB::query('SELECT * FROM tasks WHERE id = ?', [$id])` |

### Quick Code Comparison: Normal PHP vs. Q.E.D

**Normal PHP (`profile.php?id=10`):**
```php
<?php
require_once __DIR__ . '/includes/config.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$stmt = $connection->prepare('SELECT username, bio FROM users WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Must check 404 BEFORE including header.php or http_response_code(404) and <title> break
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

**In Q.E.D (`apps/users/[id]/app.php`):**
```php
<?php
// URL: /qed/users/10 -> automatically gives $params['id'] = '10'
$user = DB::query('SELECT username, bio FROM users WHERE id = ?', [$params['id']])->fetch_assoc();

if (!$user) {
    $meta['status'] = 404;
    $meta['title']  = 'User Not Found';
    echo '<h1 class="text-2xl font-bold text-red-400">404 — User Not Found</h1>';
    return; // Layouts wrap this automatically with status 404
}

$meta['title'] = $user['username'] . ' — Profile';
?>
<h1 class="text-3xl font-black text-white"><?= e($user['username']) ?></h1>
<p class="mt-2 text-zinc-400"><?= e($user['bio']) ?></p>
```

## Project Folder Structure

```text
QED/
├── .env                        # Local DB credentials (ignored by git, blocked by .htaccess)
├── .env.example                # Example env file
├── .gitignore                  # Ignores .env and node_modules/
├── .htaccess                   # Blocks direct access to internal folders & locks asset extensions
├── package.json                # Pushed to git: Tailwind devDependency & npm build scripts
├── tailwind.config.js          # Pushed to git: Tells Tailwind to scan apps/, layouts/, components/
├── src/                        # Pushed to git: Source CSS folder
│   └── input.css               # Tailwind @tailwind directives & base CSS variables
├── global.css                  # Pushed to git: Compiled CSS output served to the browser
├── config.php                  # Session setup, DB::query() wrapper, CSRF, component & asset helpers
├── index.php                   # Main router & output buffer layout wrapper
├── public/                     # Image files only (.png, .jpg, .webp, .svg, etc.)
│   ├── image.png
│   └── logo/
│       └── image.png
├── script/                     # JavaScript files only (.js, .mjs)
│   └── app.js
├── components/                 # Reusable UI components loaded via component()
│   └── task-card.php
├── layouts/                    # Main HTML wrapper
│   └── main.php
└── apps/                       # Routes folder (matches URL paths)
    ├── 404.php                 # 404 error page
    ├── app.php                 # "/" home page (page.php also works)
    ├── about/
    │   └── app.php             # "/about" page
    ├── tasks/
    │   ├── app.php             # "/tasks" page (list + POST create)
    │   └── [id]/
    │       └── app.php         # "/tasks/42" dynamic route (view + POST update/delete)
    └── api/
        └── tasks/
            └── route.php       # "/api/tasks" JSON API endpoint
```

## Running the Web Server & Using Tailwind CSS

On your machine it is recommended to have **XAMPP** (PHP 8.2+) or a similar Apache + MySQL stack installed.

Because `node_modules/` is **not** pushed to GitHub—only [src/input.css](https://www.google.com/search?q=https://github.com/Qwidio/QED/blob/main/src/input.css), [package.json](https://www.google.com/search?q=https://github.com/Qwidio/QED/blob/main/package.json), [tailwind.config.js](https://www.google.com/search?q=https://github.com/Qwidio/QED/blob/main/tailwind.config.js), and the compiled [global.css](https://www.google.com/search?q=https://github.com/Qwidio/QED/blob/main/global.css) are pushed—how you run the project depends on whether you are just running/editing PHP or changing Tailwind styles.

### Option A: Running the site or editing PHP only (No Node.js needed)

If you are deploying to a production server or only editing PHP backend logic without adding new Tailwind classes, you **do not** need Node.js or `npm`:

1. Clone the repository into your Apache root (e.g., `C:\xampp\htdocs\QED`).
   * *Note:* If you host Q.E.D at the main domain root (`/`) instead of `/qed`, change `$base_path = '';` in [config.php](https://www.google.com/search?q=https://github.com/Qwidio/QED/blob/main/config.php).
2. Copy `.env.example` to `.env` and fill in your database settings (`DB_HOST`, `DB_USER`, `DB_PASS`, `DB_NAME`).
3. Start Apache and MySQL in XAMPP. Because the compiled `global.css` is already in the repo, all existing styles work out of the box.

### Option B: Editing styles & adding new Tailwind classes

Because `node_modules/` is ignored by Git, if you add **new** Tailwind classes to `apps/app.php` or any other PHP file, Tailwind needs to scan your files and update `global.css`.

1. **Prerequisite:** Make sure you have [Node.js](https://nodejs.org/) installed on your computer.
2. **Install Tailwind from `package.json`:**
   Open PowerShell or your terminal inside the `QED` folder and run:
   ```powershell
   npm install
   ```
   This reads `package.json` and recreates the `node_modules/` folder locally so `npx tailwindcss` works.
3. **Run the Tailwind watcher while coding:**
   ```powershell
   npm run dev
   ```
   *(Or run `npx tailwindcss -i ./src/input.css -o ./global.css --watch`)*  
   Keep this terminal open while editing your `.php` files. Every time you save a file, Tailwind will automatically update `global.css`.
4. **Minify before pushing to GitHub:**
   When you are done editing and ready to push to GitHub, build the minified CSS:
   ```powershell
   npm run build
   ```
   *(Or run `npx tailwindcss -i ./src/input.css -o ./global.css --minify`)*  
   Then commit and push your code along with the updated `global.css`. Since `node_modules/` is in `.gitignore`, Git will only push `src/input.css`, `package.json`, `tailwind.config.js`, and `global.css`.

### Getting Tailwind Autocomplete & Syntax Highlighting in VS Code

By default, the **Tailwind CSS IntelliSense** extension in VS Code ignores `.php` files and doesn't show popup suggestions inside quotes. To make it work when editing `app.php`:

1. Make sure you open the `QED` folder directly in VS Code (`File` -> `Open Folder` -> `C:\xampp\htdocs\QED`), not the parent `htdocs` folder, otherwise the extension won't find `tailwind.config.js`.
2. Install the **Tailwind CSS IntelliSense** extension in VS Code.
3. Create `.vscode/settings.json` inside your `QED` folder (or add to your VS Code settings):
   ```json
   {
     "tailwindCSS.includeLanguages": {
       "php": "html"
     },
     "editor.quickSuggestions": {
       "strings": "on"
     },
     "tailwindCSS.emmetCompletions": true
   }
   ```

## How the Core Files Work (For Maintainers)

### 1. Request Flow & `.htaccess` Lockdown

Every request goes through these steps in order:

1. **`.htaccess` Filter:**
   * Blocks direct browser access (`403 Forbidden`) to `config.php`, `package.json`, `tailwind.config.js`, `apps/`, `layouts/`, `components/`, `src/`, `node_modules/`, and dotfiles like `.env`.
   * Locks `/public/` so Apache only serves image extensions (`.png`, `.jpg`, `.jpeg`, `.gif`, `.webp`, `.avif`, `.svg`, `.ico`) and locks `/script/` so Apache only serves `.js` or `.mjs` files. If someone tries to open `/qed/public/shell.php`, Apache blocks it with `403 Forbidden`.
   * Serves static files like `global.css` directly.
   * Sends everything else to `index.php`.
2. **Session & Security Headers (`config.php`):**
   * Starts the session with `HttpOnly`, `SameSite=Lax`, and `use_strict_mode=1`.
   * Sends `X-Content-Type-Options: nosniff`, `X-Frame-Options: SAMEORIGIN`, and `Referrer-Policy` headers.
3. **LFI-Safe Router (`resolve_app_route()` in `index.php`):**
   * Strips `$base_path` (`/qed`) from the URL and splits it into folder segments.
   * Checks every segment against `/^[a-zA-Z0-9_-]+$/`. If a segment has `..`, slashes, or weird characters, it returns `404` immediately.
   * Checks if a static folder exists first. If not, it looks for a dynamic `[param]` folder (like `[id]`) and saves the value in `$params['id']`.
   * Verifies `realpath()` starts with the `apps/` folder path so symlinks or path tricks can never escape `apps/`.
   * Looks for `route.php` (for JSON APIs) or `page.php` / `app.php` (for HTML pages).
4. **Inside-Out Output Buffering (`ob_start()`):**
   * For normal pages, `index.php` checks CSRF tokens automatically on `POST`/`PUT`/`DELETE` requests (`verify_csrf()`).
   * It runs your `app.php` or `page.php` *first* inside an output buffer (`ob_start()`) and saves the HTML into `$children`.
   * Because your page runs before [layouts/main.php](https://www.google.com/search?q=https://github.com/Qwidio/QED/blob/main/layouts/main.php), your page can change `$meta['title']`, set `$meta['status'] = 404`, or redirect with `header('Location: ...')` without hitting `"headers already sent"` errors.
   * Finally, it wraps `$children` outward through any nested `layout.php` files and outputs `layouts/main.php`.

### 2. Why `public_asset()` and `script_asset()` are used instead of `__DIR__`

If you try to use `__DIR__ . "/public/image.png"` inside an `<img src="...">` tag, PHP outputs your computer's local hard drive path (like `C:\xampp\htdocs\qed\public\image.png`), which the browser blocks. The browser needs a web URL starting with `$base_path` (`/qed/public/image.png`).

* `public_asset('/logo/image.png')` outputs `/qed/public/logo/image.png`. It blocks `..` and only allows image extensions.
* `script_asset('/app.js')` outputs `/qed/script/app.js`. It blocks `..` and only allows `.js` and `.mjs` extensions.
* If someone passes a non-matching file (like `public_asset('/config.php')`), the function returns an empty string `""`.

### 3. Core Files Reference

#### `.htaccess`
```apache
Options -Indexes
RewriteEngine On

# 1. Block direct access to internal PHP files, Tailwind configs, folders, and dotfiles
RewriteRule ^(?:config\.php|package(?:-lock)?\.json|tailwind\.config\.js|apps/|layouts/|components/|src/|node_modules/|\.) - [F,L,NC]

# 2. Lock /public/ to image extensions only
RewriteCond %{REQUEST_URI} ^(?:/[^/]+)?/public/ [NC]
RewriteCond %{REQUEST_URI} !\.(png|jpe?g|gif|webp|avif|svg|ico)$ [NC]
RewriteRule ^ - [F,L]

# 3. Lock /script/ to .js and .mjs extensions only
RewriteCond %{REQUEST_URI} ^(?:/[^/]+)?/script/ [NC]
RewriteCond %{REQUEST_URI} !\.(m?js)$ [NC]
RewriteRule ^ - [F,L]

# 4. Serve existing static files (global.css, images, JS) directly
RewriteCond %{REQUEST_FILENAME} -f
RewriteCond %{REQUEST_URI} !\.(php|phtml|inc)$ [NC]
RewriteRule ^ - [L]

# 5. Route everything else through index.php
RewriteRule ^ index.php [QSA,L]
```

#### `package.json`
```json
{
  "name": "qed",
  "version": "1.0.0",
  "private": true,
  "scripts": {
    "dev": "tailwindcss -i ./src/input.css -o ./global.css --watch",
    "build": "tailwindcss -i ./src/input.css -o ./global.css --minify"
  },
  "devDependencies": {
    "tailwindcss": "^3.4.17"
  }
}
```

#### `tailwind.config.js`
```javascript
/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./apps/**/*.php",
    "./components/**/*.php",
    "./layouts/**/*.php",
    "./index.php",
  ],
  theme: {
    extend: {},
  },
  plugins: [],
};
```

## Developer Guide: Pages, Components, Assets & CRUD

### 1. Creating Pages & Dynamic Routes

Any folder inside `apps/` with an `app.php` or `page.php` file automatically becomes a route:
* `apps/app.php` -> `/qed/`
* `apps/about/app.php` -> `/qed/about`
* `apps/dashboard/settings/app.php` -> `/qed/dashboard/settings`

To make a dynamic route, wrap the folder name in square brackets like `[id]` or `[slug]`:
* `apps/tasks/[id]/app.php` -> Visiting `/qed/tasks/42` gives you `$params['id'] === '42'`.

Inside any page, you automatically have access to:
* `$meta`: Change `$meta['title'] = 'My Page';` or `$meta['status'] = 404;`.
* `$params`: Array of dynamic `[param]` values from the URL.
* `$base_path`: Your base URL path (`'/qed'`), useful for links and redirects.

### 2. Using Images (`/public/`) & Scripts (`/script/`)

Put images inside `public/` and JS files inside `script/`, then load them with `public_asset()` and `script_asset()`:

```php
<!-- Loads /qed/public/image.png and /qed/public/logo/image.png -->
<img src="<?= public_asset('/image.png') ?>" alt="Banner">
<img src="<?= public_asset('/logo/image.png') ?>" alt="Logo">

<!-- Loads /qed/script/app.js -->
<script src="<?= script_asset('/app.js') ?>" defer></script>
```

### 3. Reusable Components (`components/`)

Put reusable UI snippets inside `components/` so variables don't leak across your page.

Create `components/task-card.php`:
```php
<li class="flex items-center justify-between p-[15px] bg-gradient-to-b from-[rgb(28,30,31)] to-[rgb(41,40,43)] border border-[rgb(65,59,78)] rounded-[12px]">
    <a href="<?= e($base_path) ?>/tasks/<?= e($task['id']) ?>" class="flex items-center gap-[10px]">
        <?php if (!empty($task['is_done'])): ?>
            <s class="text-[rgb(128,128,128)]"><?= e($task['title']) ?></s>
            <span class="text-[11px] text-[rgb(182,153,250)]">(Completed)</span>
        <?php else: ?>
            <strong class="text-white font-bold"><?= e($task['title']) ?></strong>
        <?php endif; ?>
    </a>
    <small class="text-[11px] text-[rgb(128,128,128)]"><?= e($task['created_at']) ?></small>
</li>
```

Call it in any page using `component()`:
```php
<?php foreach ($tasks as $task): ?>
    <?php component('task-card', ['task' => $task, 'base_path' => $base_path]); ?>
<?php endforeach; ?>
```

### 4. How to CRUD in Q.E.D (Task Manager Example)

#### Step 1: Create the MySQL Table
Run this SQL in your database (e.g., via phpMyAdmin):
```sql
CREATE TABLE IF NOT EXISTS tasks (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    is_done TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Step 2: Read & Create Tasks (`apps/tasks/app.php`)
In Q.E.D, you handle the `POST` form submission right at the top of the same file that displays the page. `index.php` already checks the CSRF token before running your file.

```php
<?php
declare(strict_types=1);
$meta['title'] = 'Tasks — Q.E.D';
$error = null;

// --- CREATE (POST /qed/tasks) ---
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
        header("Location: {$base_path}/tasks");
        exit;
    }
}

// --- READ (GET /qed/tasks) ---
$tasks = DB::query('SELECT * FROM tasks ORDER BY id DESC')->fetch_all(MYSQLI_ASSOC);
?>

<section class="w-[88%] min-h-[20vh] h-max flex flex-col mt-[10vh] p-[15px] gap-[15px]">
    <h1 class="text-[29px] font-black text-white">Task Manager</h1>

    <?php if ($error !== null): ?>
        <p class="p-[10px] rounded-[5px] bg-[red] text-white text-[13px] font-bold"><?= e($error) ?></p>
    <?php endif; ?>

    <!-- Create Form -->
    <form method="POST" action="<?= e($base_path) ?>/tasks" class="p-[15px] bg-gradient-to-b from-[rgb(28,30,31)] to-[rgb(41,40,43)] border border-[rgb(65,59,78)] rounded-[12px] flex flex-col gap-[10px]">
        <?= csrf_field() ?>
        <div class="flex flex-col gap-[5px]">
            <label for="title" class="text-[13px] text-[rgba(255,255,255,0.737)]">Task Title</label>
            <input id="title" type="text" name="title" placeholder="Finish Q.E.D docs" required
                   class="p-[7px] rounded-[10px] text-black bg-white">
        </div>
        <div class="flex flex-col gap-[5px]">
            <label for="description" class="text-[13px] text-[rgba(255,255,255,0.737)]">Description</label>
            <textarea id="description" name="description" rows="2"
                      class="p-[7px] rounded-[10px] text-black bg-white resize-none"></textarea>
        </div>
        <button type="submit" class="w-max px-[15px] py-[10px] bg-[rgb(111,82,177)] hover:text-[rgb(255,143,39)] rounded-[12px] text-white font-bold cursor-pointer">
            Create Task
        </button>
    </form>

    <!-- Task List -->
    <?php if (empty($tasks)): ?>
        <p class="text-[13px] text-[rgb(128,128,128)]">No tasks yet.</p>
    <?php else: ?>
        <ul class="flex flex-col gap-[10px]">
            <?php foreach ($tasks as $task): ?>
                <?php component('task-card', ['task' => $task, 'base_path' => $base_path]); ?>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>
```

#### Step 3: View, Update & Delete a Single Task (`apps/tasks/[id]/app.php`)
Create the folder `apps/tasks/[id]/` and add `app.php`:

```php
<?php
declare(strict_types=1);
$taskId = (int) ($params['id'] ?? 0);

// 1. Fetch task from DB
$task = DB::query('SELECT * FROM tasks WHERE id = ?', [$taskId])->fetch_assoc();

// 2. Return 404 if not found
if (!$task) {
    $meta['status'] = 404;
    $meta['title']  = 'Task Not Found';
    echo '<section class="w-[88%] mt-[10vh] flex flex-col gap-[10px]">';
    echo '<h1 class="text-[24px] font-black text-[rgb(255,43,43)]">404 — Task Not Found</h1>';
    echo '<a href="' . e($base_path) . '/tasks" class="text-[rgb(182,153,250)] underline">&larr; Back to Tasks</a>';
    echo '</section>';
    return;
}

// 3. Handle Update or Delete on POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['_action'] ?? 'update';

    if ($action === 'delete') {
        DB::query('DELETE FROM tasks WHERE id = ?', [$taskId]);
        header("Location: {$base_path}/tasks");
        exit;
    }

    if ($action === 'update') {
        $title       = trim((string) ($_POST['title'] ?? ''));
        $description = trim((string) ($_POST['description'] ?? ''));
        $isDone      = isset($_POST['is_done']) ? 1 : 0;

        if ($title !== '') {
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

<section class="w-[88%] min-h-[20vh] h-max flex flex-col mt-[10vh] p-[15px] gap-[15px]">
    <a href="<?= e($base_path) ?>/tasks" class="text-[13px] text-[rgb(182,153,250)] underline">&larr; Back to Tasks</a>
    <h1 class="text-[24px] font-black text-white">Edit Task #<?= e($task['id']) ?></h1>

    <!-- Update Form -->
    <form method="POST" class="p-[15px] bg-gradient-to-b from-[rgb(28,30,31)] to-[rgb(41,40,43)] border border-[rgb(65,59,78)] rounded-[12px] flex flex-col gap-[10px]">
        <?= csrf_field() ?>
        <input type="hidden" name="_action" value="update">

        <label class="text-[13px] text-[rgba(255,255,255,0.737)]">Title</label>
        <input type="text" name="title" value="<?= e($task['title']) ?>" required class="p-[7px] rounded-[10px] text-black bg-white">

        <label class="text-[13px] text-[rgba(255,255,255,0.737)]">Description</label>
        <textarea name="description" rows="3" class="p-[7px] rounded-[10px] text-black bg-white resize-none"><?= e($task['description'] ?? '') ?></textarea>

        <label class="flex items-center gap-[8px] text-[13px] text-white cursor-pointer">
            <input type="checkbox" name="is_done" value="1" <?= $task['is_done'] ? 'checked' : '' ?>>
            Completed
        </label>

        <button type="submit" class="w-max px-[15px] py-[10px] bg-[rgb(111,82,177)] rounded-[12px] text-white font-bold cursor-pointer">
            Save Changes
        </button>
    </form>

    <!-- Delete Form -->
    <form method="POST" onsubmit="return confirm('Delete this task?');">
        <?= csrf_field() ?>
        <input type="hidden" name="_action" value="delete">
        <button type="submit" class="px-[15px] py-[8px] bg-[red] rounded-[5px] text-white font-bold cursor-pointer">
            Delete Task
        </button>
    </form>
</section>
```

#### Step 4: JSON API Endpoint (`apps/api/tasks/route.php`)
If you want to fetch tasks via JavaScript `fetch('/qed/api/tasks')`, create `apps/api/tasks/route.php`:

```php
<?php
return [
    'GET' => static function (array $params): array {
        $tasks = DB::query('SELECT id, title, description, is_done, created_at FROM tasks ORDER BY id DESC')
            ->fetch_all(MYSQLI_ASSOC);
        return ['ok' => true, 'tasks' => $tasks];
    },
];
```

### Quick Cheat Sheet

| What you want to do | Command / Code |
| :--- | :--- |
| **Install Tailwind locally (after cloning)** | `npm install` |
| **Watch & compile Tailwind while editing** | `npm run dev` |
| **Minify `global.css` before pushing to Git** | `npm run build` |
| **Escape output safely in HTML** | `<?= e($value) ?>` |
| **Load an image from `/public/`** | `<img src="<?= public_asset('/logo/image.png') ?>">` |
| **Load a JS script from `/script/`** | `<script src="<?= script_asset('/app.js') ?>" defer></script>` |
| **Add CSRF input to a `<form method="POST">`** | `<?= csrf_field() ?>` |
| **Change page `<title>`** | `$meta['title'] = 'New Title';` |
| **Return a 404 status code** | `$meta['status'] = 404;` |
| **Get a dynamic `[id]` folder value** | `$id = $params['id'];` |
| **Run a `SELECT` query (all rows)** | `$rows = DB::query('SELECT * FROM tasks WHERE is_done = ?', [0])->fetch_all(MYSQLI_ASSOC);` |
| **Run a `SELECT` query (single row)** | `$row = DB::query('SELECT * FROM tasks WHERE id = ?', [$id])->fetch_assoc();` |
| **Run `INSERT` and get new ID** | `DB::query('INSERT INTO tasks (title) VALUES (?)', [$title]); $id = DB::lastId();` |
| **Load a component from `components/`** | `<?php component('task-card', ['task' => $task, 'base_path' => $base_path]); ?>` |
