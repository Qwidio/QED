<?php
declare(strict_types=1);
$meta['title'] = 'Documentation | QED';
?>
<header class="fixed top-0 left-0 w-full z-20 flex backdrop-blur-md bg-zinc-900/80 border-b border-zinc-800 shadow-md">
    <nav class="w-full flex items-center justify-center gap-3 px-[2vw] py-2.5 z-30">
        <a href="<?= e($base_path, ENT_QUOTES, 'UTF-8') ?>/" class="px-3 py-2 text-base rounded-md text-white hover:bg-zinc-800 hover:text-[var(--accent)] transition">
            Home
        </a>
        <a href="#" class="px-3 py-2 text-base rounded-md text-[var(--accent)] font-semibold hover:bg-zinc-800 hover:text-[var(--accent)] transition">
            Docs
        </a>
        <a href="https://github.com/Qwidio/QED" target="_blank" rel="noopener noreferrer" class="px-3 py-2 text-base rounded-md text-zinc-400 hover:bg-zinc-800 hover:text-white transition">
            GitHub
        </a>
    </nav>
</header>
<div class="w-[88%] max-w-6xl mx-auto mt-[10vh] mb-[10vh] flex flex-col lg:flex-row gap-8 items-start">
  <aside class="w-full lg:w-64 shrink-0 lg:sticky lg:top-20 bg-zinc-900/70 border border-zinc-800 rounded-xl p-4 flex flex-col gap-2 text-sm">
    <span class="px-2 py-1 text-xs font-bold uppercase tracking-wider text-zinc-400 instrument-serif-regular-italic">Documentation List</span>
    <a href="#intro" class="px-2.5 py-1.5 rounded-md text-zinc-300 hover:bg-zinc-800 hover:text-[var(--accent)] transition">Introduction</a>
    <a href="#about" class="px-2.5 py-1.5 rounded-md text-zinc-300 hover:bg-zinc-800 hover:text-[var(--accent)] transition">About</a>
    <a href="#comparison" class="px-2.5 py-1.5 rounded-md text-zinc-300 hover:bg-zinc-800 hover:text-[var(--accent)] transition">Comparison</a>
    <a href="#folder-structure" class="px-2.5 py-1.5 rounded-md text-zinc-300 hover:bg-zinc-800 hover:text-[var(--accent)] transition">Project Structure</a>
    <a href="#tailwind-setup" class="px-2.5 py-1.5 rounded-md text-zinc-300 hover:bg-zinc-800 hover:text-[var(--accent)] transition">Web Server &amp; Tailwind</a>
    <a href="#core-files" class="px-2.5 py-1.5 rounded-md text-zinc-300 hover:bg-zinc-800 hover:text-[var(--accent)] transition">How Core Files Work</a>
    <a href="#developer-guide" class="px-2.5 py-1.5 rounded-md text-zinc-300 hover:bg-zinc-800 hover:text-[var(--accent)] transition">Developer Guide</a>
    <a href="#infotable" class="px-2.5 py-1.5 rounded-md text-zinc-300 hover:bg-zinc-800 hover:text-[var(--accent)] transition">Reference table</a>
  </aside>

  <main class="flex-1 min-w-0 flex flex-col gap-10 text-zinc-200 leading-relaxed">
    <div id="intro" class="pb-8 scroll-mt-20 flex flex-col gap-4 border-b border-zinc-800">
      <h1 class="text-3xl sm:text-4xl font-black text-white">
        QED, Technical Reference &amp; Developer Documentation
      </h1>
      <p class="text-zinc-300">
        Welcome to the work in progress documentation for <strong class="text-white"><a href="https://github.com/Qwidio/QED" target="_blank" rel="noopener noreferrer" class="text-emerald-400 underline">Qwidio/QED</a></strong>, this docs is written for two types of people:
      </p>
      <ul class="list-disc pl-6 flex flex-col gap-2 text-zinc-300">
        <li><strong class="text-white">Maintainers &amp; Contributors</strong> who want to understand, check, or modify the core routing, layout buffering, asset helpers, and security setup.</li>
        <li><strong class="text-white">Developers</strong> who want to build pages, nested layouts, components, JSON APIs, and CRUD features using this project.</li>
      </ul>
      <p class="p-3 text-xs text-zinc-400 bg-zinc-900/80 border border-zinc-800 rounded-lg italic">
        Disclorsure: At the time of writing, this docs is in some part assisted with AI. It is already rewritten in some part with my own writing and the part that still kept is there because it is already good enough
      </p>
    </div>

    <div id="about" class="pb-8 scroll-mt-20 flex flex-col gap-4 border-b border-zinc-800">
      <h2 class="text-2xl font-bold text-white">
        About the project
      </h2>
      <p class="text-zinc-300">
        QED in it's simple term is a recreation of the <strong class="text-white">Next.js App Router</strong> built in pure PHP.
        <br>
        The goal is to have folder-based routing (<code class="px-1.5 py-0.5 rounded bg-zinc-800 text-emerald-400 text-xs">apps/**/app.php</code> or <code class="px-1.5 py-0.5 rounded bg-zinc-800 text-emerald-400 text-xs">page.php</code>), dynamic <code class="px-1.5 py-0.5 rounded bg-zinc-800 text-emerald-400 text-xs">[id]</code> folders, nested layouts (<code class="px-1.5 py-0.5 rounded bg-zinc-800 text-emerald-400 text-xs">layout.php</code>), co-located <code class="px-1.5 py-0.5 rounded bg-zinc-800 text-emerald-400 text-xs">POST</code> form actions, and <strong class="text-white">Tailwind CSS</strong> styling—without needing a Node.js runtime on your production server and without leaving Local File Inclusion (LFI) holes open.
      </p>
      <h3 class="mt-2 text-xl font-bold text-white">
        What's changed from the initial commit (<code class="px-1.5 py-0.5 rounded bg-zinc-800 text-emerald-400 text-base">20967c0</code>)
      </h3>
      <div class="bg-zinc-900/60 border border-zinc-800 rounded-xl overflow-x-auto">
        <table class="w-full text-left border-collapse text-sm">
          <thead>
            <tr class="text-white border-b border-zinc-800 bg-zinc-900">
              <th class="p-3 font-bold">Subsytem</th>
              <th class="p-3 font-bold">Commit <code>20967c0</code> (Old)</th>
              <th class="p-3 font-bold">Current QED</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-zinc-800 text-zinc-300">
            <tr>
              <td class="p-3 font-bold text-white">Routing</td>
              <td class="p-3">
                Hardcoded <code>$routes</code> array in <a href="https://github.com/Qwidio/QED/blob/main/index.php" target="_blank" rel="noopener noreferrer" class="text-emerald-400 underline">index.php</a> pointing to missing <code>/apps/about/app.php</code> and <code>/pages/404.php</code> files
              </td>
              <td class="p-3">
                Automatic folder-based routing (<code>resolve_app_route()</code>) supporting <code>apps/**/app.php</code>, <code>page.php</code>, <code>[param]</code> folders, and <code>apps/404.php</code>
              </td>
            </tr>
            <tr>
              <td class="p-3 font-bold text-white">Render order</td>
              <td class="p-3">
                <a href="https://github.com/Qwidio/QED/blob/main/layouts/main.php" target="_blank" rel="noopener noreferrer" class="text-emerald-400 underline">layouts/main.php</a> echoed <code>&lt;head&gt;</code> and <code>&lt;title&gt;</code> <em>before</em> loading the page, causing <code>"Headers already sent"</code> errors on redirects
              </td>
              <td class="p-3">
                Page runs <em>first</em> inside <code>ob_start()</code>; pages can change <code>$meta['title']</code>, return a <code>404</code> status, or redirect before any layout HTML is sent
              </td>
            </tr>
            <tr>
              <td class="p-3 font-bold text-white">Direct file access</td>
              <td class="p-3">
                <a href="https://github.com/Qwidio/QED/blob/main/.htaccess" target="_blank" rel="noopener noreferrer" class="text-emerald-400 underline">.htaccess</a> let anyone open <code>/qed/config.php</code> or <code>/qed/layouts/main.php</code> straight from the browser
              </td>
              <td class="p-3">
                <code>.htaccess</code> blocks direct browser access to <code>config.php</code>, <code>apps/</code>, <code>layouts/</code>, <code>components/</code>, <code>src/</code>, <code>package.json</code>, <code>tailwind.config.js</code>, and dotfiles (<code>403 Forbidden</code>)
              </td>
            </tr>
            <tr>
              <td class="p-3 font-bold text-white">Static asset</td>
              <td class="p-3">
                <code>&lt;link rel="stylesheet" href="../global.css"&gt;</code> broke on deeper URLs like <code>/qed/dashboard/settings</code>
              </td>
              <td class="p-3">
                Root-relative <code>global.css</code> path plus <code>public_asset()</code> (locked to images) and <code>script_asset()</code> (locked to <code>.js</code>/<code>.mjs</code>)
              </td>
            </tr>
            <tr>
              <td class="p-3 font-bold text-white">Styling</td>
              <td class="p-3">
                Huge custom CSS file (<code>global.css</code>) with custom class names and no editor autocomplete
              </td>
              <td class="p-3">
                Official <strong class="text-white">Tailwind CSS</strong> (<code>src/input.css</code> + <code>tailwind.config.js</code> -&gt; compiled <a href="https://github.com/Qwidio/QED/blob/main/global.css" target="_blank" rel="noopener noreferrer" class="text-emerald-400 underline">global.css</a>) with full VS Code autocomplete
              </td>
            </tr>
            <tr>
              <td class="p-3 font-bold text-white">Database &amp; <code>.env</code></td>
              <td class="p-3">
                Plaintext <code>$db_pass</code> and <code>$connection</code> sitting in global variables in <a href="https://github.com/Qwidio/QED/blob/main/config.php" target="_blank" rel="noopener noreferrer" class="text-emerald-400 underline">config.php</a>
              </td>
              <td class="p-3">
                <code>.env</code> credentials loaded inside a <code>DB::query($sql, $params)</code> wrapper using prepared statements (<code>mysqli::execute_query()</code>)
              </td>
            </tr>
            <tr>
              <td class="p-3 font-bold text-white">Session &amp; Forms</td>
              <td class="p-3">
                Basic <code>session_start()</code> with no CSRF protection
              </td>
              <td class="p-3">
                <code>HttpOnly</code> + <code>SameSite=Lax</code> + <code>use_strict_mode</code> session cookies and automatic <code>verify_csrf()</code> check on <code>POST</code> requests
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    
    <div id="comparison" class="pb-8 scroll-mt-20 flex flex-col gap-4 border-b border-zinc-800">
      <h2 class="text-2xl font-bold text-white">Comparison between this, "normal" PHP &amp; NextJS</h2>
      <p class="text-zinc-300">
        In normal procedural PHP, every page is a separate file in the root folder (<code class="px-1.5 py-0.5 rounded bg-zinc-800 text-emerald-400 text-xs">tasks.php</code>, <code class="px-1.5 py-0.5 rounded bg-zinc-800 text-emerald-400 text-xs">edit_task.php?id=5</code>), and you have to manually <code class="px-1.5 py-0.5 rounded bg-zinc-800 text-emerald-400 text-xs">require 'config.php'</code> and <code class="px-1.5 py-0.5 rounded bg-zinc-800 text-emerald-400 text-xs">include 'header.php'</code> at the top of every single file. If you try to redirect or set a 404 status after <code class="px-1.5 py-0.5 rounded bg-zinc-800 text-emerald-400 text-xs">header.php</code> is included, PHP throws the <code class="px-1.5 py-0.5 rounded bg-zinc-800 text-red-400 text-xs">"Cannot modify header information - headers already sent"</code> warning.
      </p>
      <p class="text-zinc-300">
        Here is how QED handles common tasks compared to normal PHP and Next.js:
      </p>
      <div class="bg-zinc-900/60 border border-zinc-800 rounded-xl overflow-x-auto">
        <table class="w-full text-left border-collapse text-sm">
          <thead>
            <tr class="text-white border-b border-zinc-800 bg-zinc-900">
              <th class="p-3 font-bold">Task</th>
              <th class="p-3 font-bold">"Normal" PHP</th>
              <th class="p-3 font-bold">Next.js App Router</th>
              <th class="p-3 font-bold">QED</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-zinc-800 text-zinc-300">
            <tr>
              <td class="p-3 font-bold text-white">Creating New Page</td>
              <td class="p-3">
                Create <code>/about.php</code> and manually include <code>header.php</code> &amp; <code>footer.php</code>
              </td>
              <td class="p-3">
                Create <code>app/about/page.tsx</code>
              </td>
              <td class="p-3">
                Create <code>apps/about/app.php</code> or <code>page.php</code> (layouts wrap it automatically)
              </td>
            </tr>
            <tr>
              <td class="p-3 font-bold text-white">Dynamic URLs</td>
              <td class="p-3"><code>
                /task.php?id=42</code> or manual <code>.htaccess</code> regex
              </td>
              <td class="p-3"><code>
                app/tasks/[id]/page.tsx</code> (<code>params.id</code>)
              </td>
              <td class="p-3"><code>
                apps/tasks/[id]/app.php</code> (<code>$params['id']</code>)
              </td>
            </tr>
            <tr>
              <td class="p-3 font-bold text-white">Changing <code>&lt;title&gt;</code></td>
              <td class="p-3">
                Must set <code>$title</code> before <code>include 'header.php'</code>
              </td>
              <td class="p-3">
                <code>generateMetadata()</code>
              </td>
              <td class="p-3">
                Set <code>$meta['title'] = '...'</code> anywhere inside your page
              </td>
            </tr>
            <tr>
              <td class="p-3 font-bold text-white">Redirect &amp; 404s</td>
              <td class="p-3">
                Fails if called after <code>header.php</code> is included
              </td>
              <td class="p-3">
                <code>redirect()</code> / <code>notFound()</code>
              </td>
              <td class="p-3">
                Set <code>$meta['status'] = 404;</code> or call <code>header('Location: ...'); exit;</code> anywhere whithin the page
              </td>
            </tr>
            <tr>
              <td class="p-3 font-bold text-white">Nested Layouts</td>
              <td class="p-3">
                Manual <code>include 'sidebar.php'</code> in every file
              </td>
              <td class="p-3">
                <code>app/dashboard/layout.tsx</code>
              </td>
              <td class="p-3">
                Put <code>layout.php</code> inside <code>apps/dashboard/</code> wrapping <code>&lt;?= $children ?&gt;</code>
              </td>
            </tr>
            <tr>
              <td class="p-3 font-bold text-white">Reusable Components</td>
              <td class="p-3">
                <code>include 'card.php'</code> (shares global variables)
              </td>
              <td class="p-3">
                <code>&lt;Card title="Hello" /&gt;</code>
              </td>
              <td class="p-3">
                <code>&lt;?php component('card', ['title' =&gt; 'Hello']); ?&gt;</code> (isolated scope)
              </td>
            </tr>
            <tr>
              <td class="p-3 font-bold text-white">Image &amp; Javascripts</td>
              <td class="p-3">
                Relative paths (<code>../../public/image.png</code>) that break on nested URLs
              </td>
              <td class="p-3">
                <code>&lt;Image /&gt;</code> / <code>&lt;Script /&gt;</code>
              </td>
              <td class="p-3">
                <code>&lt;?= public_asset('/logo/image.png') ?&gt;</code>
                 &amp;
                <code>&lt;?= script_asset('/app.js') ?&gt;</code>
              </td>
            </tr>
            <tr>
              <td class="p-3 font-bold text-white">CRSF protection</td>
              <td class="p-3">
                Manual token check copied into every form scripts
              </td>
              <td class="p-3">
                Built into the server actions
              </td>
              <td class="p-3">
                Put <code>&lt;?= csrf_field() ?&gt;</code> inside <code>&lt;form&gt;</code>; <code>index.php</code> Checks it automatically
              </td>
            </tr>
            <tr>
              <td class="p-3 font-bold text-white">SQL Queries</td>
              <td class="p-3">
                4-5 lines of <code>prepare()</code>, <code>bind_param()</code>, <code>execute()</code>, <code>get_result()</code>
              </td>
              <td class="p-3">
                ORM/Query builder
              </td>
              <td class="p-3">
                One line <code>DB::query('SELECT * FROM tasks WHERE id = ?', [$id])</code>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <h3 class="mt-4 text-xl font-bold text-white">Quick Code Comparison: "Normal" PHP vs QED</h3>
      <p class="font-bold text-white text-sm">Normal PHP (<code>profile.php?id=10</code>):</p>
      <pre class="px-4 py-2 rounded-xl bg-zinc-950 border border-zinc-800 text-xs text-zinc-200 overflow-x-auto">
        <code class="bg-transparent">&lt;?php
            require_once __DIR__ . '/includes/config.php';

            $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

            $stmt = $connection-&gt;prepare('SELECT username, bio FROM users WHERE id = ?');
            $stmt-&gt;bind_param('i', $id);
            $stmt-&gt;execute();
            $user = $stmt-&gt;get_result()-&gt;fetch_assoc();

            // Must check 404 BEFORE including header.php or http_response_code(404) and &lt;title&gt; break
            if (!$user) {
                http_response_code(404);
                $meta_title = 'User Not Found';
                include __DIR__ . '/includes/header.php';
                echo '&lt;h1&gt;404 — User Not Found&lt;/h1&gt;';
                include __DIR__ . '/includes/footer.php';
                exit;
            }

            $meta_title = $user['username'] . ' — Profile';
            include __DIR__ . '/includes/header.php';
            ?&gt;
            &lt;h1&gt;&lt;?php echo htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8'); ?&gt;&lt;/h1&gt;
            &lt;p&gt;&lt;?php echo htmlspecialchars($user['bio'], ENT_QUOTES, 'UTF-8'); ?&gt;&lt;/p&gt;
            &lt;?php include __DIR__ . '/includes/footer.php'; ?&gt;</code></pre>
      <h3 class="mt-2 text-xl font-bold text-white">In QED (<code>apps/users/[id]/app.php</code>):</h3>
      <p class="font-bold text-white text-sm">Normal PHP (<code>profile.php?id=10</code>):</p>
      <pre class="px-4 py-2 rounded-xl bg-zinc-950 border border-zinc-800 text-xs text-zinc-200 overflow-x-auto">
        <code class="bg-transparent">&lt;?php
            // URL: /qed/users/10 -&gt; automatically gives $params['id'] = '10'
            $user = DB::query('SELECT username, bio FROM users WHERE id = ?', [$params['id']])-&gt;fetch_assoc();

            if (!$user) {
                $meta['status'] = 404;
                $meta['title']  = 'User Not Found';
                echo '&lt;h1 class="text-2xl font-bold text-red-400"&gt;404 — User Not Found&lt;/h1&gt;';
                return; // Layouts wrap this automatically with status 404
            }

            $meta['title'] = $user['username'] . ' — Profile';
            ?&gt;
            &lt;h1 class="text-3xl font-black text-white"&gt;&lt;?= e($user['username']) ?&gt;&lt;/h1&gt;
            &lt;p class="mt-2 text-zinc-400"&gt;&lt;?= e($user['bio']) ?&gt;&lt;/p&gt;
        </code>
      </pre>
    </div>
    
    <div id="folder-structure" class="pb-8 scroll-mt-20 flex flex-col gap-4 border-b border-zinc-800">
      <h2 class="text-2xl font-bold text-white">Project Structure</h2>
      <pre class="px-4 py-2 rounded-xl bg-zinc-950 border border-zinc-800 text-xs text-zinc-200 overflow-x-auto">
        <code class="bg-transparent">
QED/
├── .env                        # Local DB credentials (ignored by git, blocked by .htaccess)
├── .env.example                # Example env file
├── .gitignore                  # Ignores .env and node_modules/
├── .htaccess                   # Blocks direct access to internal folders &amp; locks asset extensions
├── package.json                # Pushed to git: Tailwind devDependency &amp; npm build scripts
├── tailwind.config.js          # Pushed to git: Tells Tailwind to scan apps/, layouts/, components/
├── src/                        # Pushed to git: Source CSS folder
│   └── input.css               # Tailwind @tailwind directives &amp; base CSS variables
├── global.css                  # Pushed to git: Compiled CSS output served to the browser
├── config.php                  # Session setup, DB::query() wrapper, CSRF, component &amp; asset helpers
├── index.php                   # Main router &amp; output buffer layout wrapper
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
        </code>
      </pre>
    </div>

    <div id="tailwind-setup" class="pb-8 scroll-mt-20 flex flex-col gap-4 border-b border-zinc-800">
      <h2 class="text-2xl font-bold text-white">Running the Web Server &amp; Using Tailwind CSS</h2>
      <p class="text-zinc-300">
        On your machine it is recommended to have <strong class="text-white">XAMPP</strong> (PHP 8.2+) or a similar Apache + MySQL stack installed.
      </p>
      <p class="text-zinc-300">
        <code class="px-1.5 py-0.5 rounded bg-zinc-800 text-emerald-400 text-xs">node_modules/</code> is <strong class="text-white">not</strong> pushed to GitHub—only <a href="https://github.com/Qwidio/QED/blob/main/src/input.css" target="_blank" rel="noopener noreferrer" class="text-emerald-400 underline">src/input.css</a>, <a href="https://github.com/Qwidio/QED/blob/main/package.json" target="_blank" rel="noopener noreferrer" class="text-emerald-400 underline">package.json</a>, <a href="https://github.com/Qwidio/QED/blob/main/tailwind.config.js" target="_blank" rel="noopener noreferrer" class="text-emerald-400 underline">tailwind.config.js</a>, and the compiled <a href="https://github.com/Qwidio/QED/blob/main/global.css" target="_blank" rel="noopener noreferrer" class="text-emerald-400 underline">global.css</a> are pushed—how you run the project depends on whether you are just running/editing PHP or changing Tailwind styles.
      </p>

      <h3 class="mt-2 text-xl font-bold text-white">Option A: Running the site or editing PHP only</h3>
      <p class="text-zinc-300">
        If you are deploying to a production server or only editing PHP backend logic without adding new Tailwind classes, you <strong class="text-white">do not</strong> need Node.js or <code>npm</code>:
      </p>
      <ol class="list-decimal pl-6 flex flex-col gap-2 text-zinc-300">
        <li>
          Clone the repository into your Apache root (e.g., <code>C:\xampp\htdocs\QED</code>).
          <ul class="list-disc pl-6 mt-1 text-zinc-400">
            <li><em>Note:</em> If you host QED at the main domain root (<code>/</code>) instead of <code>/qed</code>, change <code>$base_path = '';</code> in <a href="https://github.com/Qwidio/QED/blob/main/config.php" target="_blank" rel="noopener noreferrer" class="text-emerald-400 underline">config.php</a>.</li>
          </ul>
        </li>
        <li>Copy <code>.env.example</code> to <code>.env</code> and fill in your database settings (<code>DB_HOST</code>, <code>DB_USER</code>, <code>DB_PASS</code>, <code>DB_NAME</code>).</li>
        <li>Start Apache and MySQL in XAMPP. Because the compiled <code>global.css</code> is already in the repo, all existing styles work out of the box.</li>
      </ol>

      <h3 class="mt-4 text-xl font-bold text-white">Option B: Editing styles &amp; adding new Tailwind classes</h3>
      <p class="text-zinc-300">
        Because <code>node_modules/</code> is ignored by Git, if you add <strong class="text-white">new</strong> Tailwind classes to <code>apps/app.php</code> or any other PHP file, Tailwind needs to scan your files and update <code>global.css</code>.
      </p>
      <ol class="list-decimal pl-6 flex flex-col gap-3 text-zinc-300">
        <li>
          <strong class="text-white">Prerequisite:</strong> Make sure you have <a href="https://nodejs.org/" target="_blank" rel="noopener noreferrer" class="text-emerald-400 underline">Node.js</a> installed on your computer.
        </li>
        <li>
          <strong class="text-white">Install Tailwind from <code>package.json</code>:</strong><br>
          Open PowerShell or your terminal inside the <code>QED</code> folder and run:
          <pre class="p-3 mt-1 rounded-lg bg-zinc-950 border border-zinc-800 text-xs text-zinc-200"><code>npm install</code></pre>
          This reads <code>package.json</code> and recreates the <code>node_modules/</code> folder locally so <code>npx tailwindcss</code> works.
        </li>
        <li>
          <strong class="text-white">Run the Tailwind watcher while coding:</strong>
          <pre class="p-3 mt-1 rounded-lg bg-zinc-950 border border-zinc-800 text-xs text-zinc-200"><code>npm run dev</code></pre>
          <span class="text-xs text-zinc-400"><em>(Or run <code>npx tailwindcss -i ./src/input.css -o ./global.css --watch</code>)</em></span><br>
          Keep this terminal open while editing your <code>.php</code> files. Every time you save a file, Tailwind will automatically update <code>global.css</code>.
        </li>
        <li>
          <strong class="text-white">Minify before pushing to GitHub:</strong><br>
          When you are done editing and ready to push to GitHub, build the minified CSS:
            <pre class="p-3 mt-1 rounded-lg bg-zinc-950 border border-zinc-800 text-xs text-zinc-200"><code>npm run build</code></pre>
            <span class="text-xs text-zinc-400"><em>(Or run <code>npx tailwindcss -i ./src/input.css -o ./global.css --minify</code>)</em></span><br>
            Then commit and push your code along with the updated <code>global.css</code>. Since <code>node_modules/</code> is in <code>.gitignore</code>, Git will only push <code>src/input.css</code>, <code>package.json</code>, <code>tailwind.config.js</code>, and <code>global.css</code>.
          </li>
        </ol>
      
      <h3 class="mt-4 text-xl font-bold text-white">Enabling Tailwind Autocomplete &amp; Syntax Highlighting in VS Code</h3>
      <p class="text-zinc-300">
        By default, the <strong class="text-white">Tailwind CSS IntelliSense</strong> extension in VS Code ignores <code>.php</code> files and doesn't show popup suggestions inside quotes. To make it work when editing <code>app.php</code>:
      </p>
      <ol class="list-decimal pl-6 flex flex-col gap-2 text-zinc-300">
        <li>Make sure you open the <code>QED</code> folder directly in VS Code (<code>File</code> -&gt; <code>Open Folder</code> -&gt; <code>C:\xampp\htdocs\QED</code>), not the parent <code>htdocs</code> folder, otherwise the extension won't find <code>tailwind.config.js</code>.</li>
        <li>Install the <strong class="text-white">Tailwind CSS IntelliSense</strong> extension in VS Code.</li>
        <li>
          Create <code>.vscode/settings.json</code> inside your <code>QED</code> folder (or add to your VS Code settings):
            <pre class="px-3 py-2 rounded-xl bg-zinc-950 border border-zinc-800 text-xs text-zinc-200 overflow-x-auto">
              <code class="bg-transparent">
{
  "tailwindCSS.includeLanguages": {
    "php": "html"
  },
  "editor.quickSuggestions": {
    "strings": "on"
  },
  "tailwindCSS.emmetCompletions": true
}
            </code>
          </pre>
        </li>
      </ol>
    </div>

    <div id="core-files" class="pb-8 scroll-mt-20 flex flex-col gap-4 border-b border-zinc-800">
      <h2 class="text-2xl font-bold text-white">How the core files work for the Maintainers</h2>
      <h3 class="text-xl font-bold text-white">1. Request Flow &amp; <code>.htaccess</code> Lockdown</h3>
      <p class="text-zinc-300">Every request goes through these steps:</p>
      <ol class="list-decimal pl-6 flex flex-col gap-3 text-zinc-300">
        <li>
          <strong class="text-white"><code>.htaccess</code> Filter:</strong>
          <ul class="list-disc pl-6 mt-1 flex flex-col gap-1">
            <li>Blocks direct browser access (<code>403 Forbidden</code>) to <code>config.php</code>, <code>package.json</code>, <code>tailwind.config.js</code>, <code>apps/</code>, <code>layouts/</code>, <code>components/</code>, <code>src/</code>, <code>node_modules/</code>, and dotfiles like <code>.env</code>.</li>
            <li>Locks <code>/public/</code> so Apache only serves image extensions (<code>.png</code>, <code>.jpg</code>, <code>.jpeg</code>, <code>.gif</code>, <code>.webp</code>, <code>.avif</code>, <code>.svg</code>, <code>.ico</code>) and locks <code>/script/</code> so Apache only serves <code>.js</code> or <code>.mjs</code> files. If someone tries to open <code>/qed/public/shell.php</code>, Apache blocks it with <code>403 Forbidden</code>.</li>
            <li>Serves static files like <code>global.css</code> directly.</li>
            <li>Sends everything else to <code>index.php</code>.</li>
          </ul>
        </li>
        <li>
          <strong class="text-white">Session &amp; Security Headers (<code>config.php</code>):</strong>
          <ul class="list-disc pl-6 mt-1 flex flex-col gap-1">
            <li>Starts the session with <code>HttpOnly</code>, <code>SameSite=Lax</code>, and <code>use_strict_mode=1</code>.</li>
            <li>Sends <code>X-Content-Type-Options: nosniff</code>, <code>X-Frame-Options: SAMEORIGIN</code>, and <code>Referrer-Policy</code> headers.</li>
          </ul>
        </li>
        <li>
          <strong class="text-white">LFI-Safe Router (<code>resolve_app_route()</code> in <code>index.php</code>):</strong>
          <ul class="list-disc pl-6 mt-1 flex flex-col gap-1">
            <li>Strips <code>$base_path</code> (<code>/qed</code>) from the URL and splits it into folder segments.</li>
            <li>Checks every segment against <code>/^[a-zA-Z0-9_-]+$/</code>. If a segment has <code>..</code>, slashes, or weird characters, it returns <code>404</code> immediately.</li>
            <li>Checks if a static folder exists first. If not, it looks for a dynamic <code>[param]</code> folder (like <code>[id]</code>) and saves the value in <code>$params['id']</code>.</li>
            <li>Verifies <code>realpath()</code> starts with the <code>apps/</code> folder path so symlinks or path tricks can never escape <code>apps/</code>.</li>
            <li>Looks for <code>route.php</code> (for JSON APIs) or <code>page.php</code> / <code>app.php</code> (for HTML pages).</li>
          </ul>
        </li>
        <li>
          <strong class="text-white">Inside-Out Output Buffering (<code>ob_start()</code>):</strong>
          <ul class="list-disc pl-6 mt-1 flex flex-col gap-1">
            <li>For normal pages, <code>index.php</code> checks CSRF tokens automatically on <code>POST</code>/<code>PUT</code>/<code>DELETE</code> requests (<code>verify_csrf()</code>).</li>
            <li>It runs your <code>app.php</code> or <code>page.php</code> <em>first</em> inside an output buffer (<code>ob_start()</code>) and saves the HTML into <code>$children</code>.</li>
            <li>Because your page runs before <a href="https://github.com/Qwidio/QED/blob/main/layouts/main.php" target="_blank" rel="noopener noreferrer" class="text-emerald-400 underline">layouts/main.php</a>, your page can change <code>$meta['title']</code>, set <code>$meta['status'] = 404</code>, or redirect with <code>header('Location: ...')</code> without hitting <code>"headers already sent"</code> errors.</li>
            <li>Finally, it wraps <code>$children</code> outward through any nested <code>layout.php</code> files and outputs <code>layouts/main.php</code>.</li>
          </ul>
        </li>
      </ol>

      <h3 class="mt-4 text-xl font-bold text-white">2. Why <code>public_asset()</code> and <code>script_asset()</code> are used instead of <code>__DIR__</code></h3>
      <p class="text-zinc-300">
        If you try to use <code>__DIR__ . "/public/image.png"</code> inside an <code>&lt;img src="..."&gt;</code> tag, PHP outputs your computer's local hard drive path (like <code>C:\xampp\htdocs\qed\public\image.png</code>), which the browser blocks. The browser needs a web URL starting with <code>$base_path</code> (<code>/qed/public/image.png</code>).
      </p>
      <ul class="list-disc pl-6 flex flex-col gap-1 text-zinc-300">
        <li><code>public_asset('/logo/image.png')</code> outputs <code>/qed/public/logo/image.png</code>. It blocks <code>..</code> and only allows image extensions.</li>
        <li><code>script_asset('/app.js')</code> outputs <code>/qed/script/app.js</code>. It blocks <code>..</code> and only allows <code>.js</code> and <code>.mjs</code> extensions.</li>
        <li>If someone passes a non-matching file (like <code>public_asset('/config.php')</code>), the function returns an empty string <code>""</code>.</li>
      </ul>
    </div>
    
    <div id="developer-guide" class="pb-8 scroll-mt-20 flex flex-col gap-4 border-b border-zinc-800">
      <h2 class="text-2xl font-bold text-white">Developer guide: pages, components, assets &amp; CRUD</h2>
      <h3 class="text-xl font-bold text-white">1. Creating Pages &amp; Dynamic Routes</h3>
      <p class="text-zinc-300">any code inside <code>apps/</code> with an <code>app.php</code> or <code>page.php</code> file automatically becomes a route:</p>
      <ul class="list-disc pl-6 flex flex-col gap-1 text-zinc-300">
        <li><code>apps/app.php</code> -&gt; <code>/qed/</code></li>
        <li><code>apps/about/app.php</code> -&gt; <code>/qed/about</code></li>
        <li><code>apps/dashboard/settings/app.php</code> -&gt; <code>/qed/dashboard/settings</code></li>
      </ul>
      <p class="text-zinc-300">To make a dynamic route, wrap the folder name in square brackets like <code>[id]</code> or <code>[slug]</code>:</p>
      <ul class="list-disc pl-6 flex flex-col gap-1 text-zinc-300">
        <li><code>apps/tasks/[id]/app.php</code> -&gt; Visiting <code>/qed/tasks/42</code> gives you <code>$params['id'] === '42'</code>.</li>
      </ul>
      <p class="text-zinc-300">Inside any page, you automatically have access to:</p>
      <ul class="list-disc pl-6 flex flex-col gap-1 text-zinc-300">
        <li><code>$meta</code>: Change <code>$meta['title'] = 'My Page';</code> or <code>$meta['status'] = 404;</code>.</li>
        <li><code>$params</code>: Array of dynamic <code>[param]</code> values from the URL.</li>
        <li><code>$base_path</code>: Your base URL path (<code>'/qed'</code>), useful for links and redirects.</li>
      </ul>

      <h3 class="text-xl font-bold text-white">2. Using images (<code>/public/</code>) &amp; Scripts (<code>/script/</code>)</h3>
      <p class="text-zinc-300">Put the images inside <code>public/</code> and JS files inside <code>script/</code>, then load them with <code>public_asset()</code> and <code>script_asset()</code>:</p>
      <pre class="px-4 py-2 rounded-xl bg-zinc-950 border border-zinc-800 text-xs text-zinc-200 overflow-x-auto">
        <code class="bg-transparent">
&lt;!-- Loads /qed/public/image.png and /qed/public/logo/image.png --&gt;
&lt;img src="&lt;?= public_asset('/image.png') ?&gt;" alt="Banner"&gt;
&lt;img src="&lt;?= public_asset('/logo/image.png') ?&gt;" alt="Logo"&gt;

&lt;!-- Loads /qed/script/app.js --&gt;
&lt;script src="&lt;?= script_asset('/app.js') ?&gt;" defer&gt;&lt;/script&gt;
        </code>
      </pre>

      <h3 class="text-xl font-bold text-white">3. Reusable components</h3>
      <p class="text-zinc-300">Put reusable UI snippets inside <code>components/</code> so variables don't leak across your page.</p>
      <p class="text-zinc-300">Create <code>components/task-card.php</code>:</p>
      <pre class="px-4 py-2 rounded-xl bg-zinc-950 border border-zinc-800 text-xs text-zinc-200 overflow-x-auto">
        <code class="bg-transparent">
&lt;li class="flex items-center justify-between p-[15px] bg-gradient-to-b from-[rgb(28,30,31)] to-[rgb(41,40,43)] border border-[rgb(65,59,78)] rounded-[12px]"&gt;
    &lt;a href="&lt;?= e($base_path) ?&gt;/tasks/&lt;?= e($task['id']) ?&gt;" class="flex items-center gap-[10px]"&gt;
        &lt;?php if (!empty($task['is_done'])): ?&gt;
            &lt;s class="text-[rgb(128,128,128)]"&gt;&lt;?= e($task['title']) ?&gt;&lt;/s&gt;
            &lt;span class="text-[11px] text-[rgb(182,153,250)]"&gt;(Completed)&lt;/span&gt;
        &lt;?php else: ?&gt;
            &lt;strong class="text-white font-bold"&gt;&lt;?= e($task['title']) ?&gt;&lt;/strong&gt;
        &lt;?php endif; ?&gt;
    &lt;/a&gt;
    &lt;small class="text-[11px] text-[rgb(128,128,128)]"&gt;&lt;?= e($task['created_at']) ?&gt;&lt;/small&gt;
&lt;/li&gt;
        </code>
      </pre>
      <p class="text-zinc-300">Call it in any page using <code>components</code></p>
      <pre class="px-4 py-2 rounded-xl bg-zinc-950 border border-zinc-800 text-xs text-zinc-200 overflow-x-auto">
        <code class="bg-transparent">
&lt;?php foreach ($tasks as $task): ?&gt;
    &lt;?php component('task-card', ['task' =&gt; $task, 'base_path' =&gt; $base_path]); ?&gt;
&lt;?php endforeach; ?&gt;
        </code>
      </pre>
      <h3 class="text-xl font-bold text-white">4. CRUD examples</h3>
      <h4 class="text-base font-bold text-emerald-400">1. Create the MySQL Table</h4>
      <pre class="px-4 py-2 rounded-xl bg-zinc-950 border border-zinc-800 text-xs text-zinc-200 overflow-x-auto">
        <code class="bg-transparent">
          CREATE TABLE IF NOT EXISTS tasks (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            description TEXT NULL,
            is_done TINYINT(1) NOT NULL DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
          </code>
        </pre>
        <h4 class="text-base font-bold text-emerald-400">2. Read &amp; Create Tasks (<code>apps/tasks/app.php</code>)</h4>
        <p class="text-zinc-300">In QED, you handle the <code>POST</code> form submission right at the top of the same file that displays the page. <code>index.php</code> already checks the CSRF token before running your file.</p>
        <pre class="px-4 py-2 rounded-xl bg-zinc-950 border border-zinc-800 text-xs text-zinc-200 overflow-x-auto">
          <code class="bg-transparent">
&lt;?php
declare(strict_types=1);
$meta['title'] = 'Tasks — QED';
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
$tasks = DB::query('SELECT * FROM tasks ORDER BY id DESC')-&gt;fetch_all(MYSQLI_ASSOC);
?&gt;

&lt;section class="w-[88%] min-h-[20vh] h-max flex flex-col mt-[10vh] p-[15px] gap-[15px]"&gt;
    &lt;h1 class="text-[29px] font-black text-white"&gt;Task Manager&lt;/h1&gt;

    &lt;?php if ($error !== null): ?&gt;
        &lt;p class="p-[10px] rounded-[5px] bg-[red] text-white text-[13px] font-bold"&gt;&lt;?= e($error) ?&gt;&lt;/p&gt;
    &lt;?php endif; ?&gt;

    &lt;!-- Create Form --&gt;
    &lt;form method="POST" action="&lt;?= e($base_path) ?&gt;/tasks" class="p-[15px] bg-gradient-to-b from-[rgb(28,30,31)] to-[rgb(41,40,43)] border border-[rgb(65,59,78)] rounded-[12px] flex flex-col gap-[10px]"&gt;
        &lt;?= csrf_field() ?&gt;
        &lt;div class="flex flex-col gap-[5px]"&gt;
            &lt;label for="title" class="text-[13px] text-[rgba(255,255,255,0.737)]"&gt;Task Title&lt;/label&gt;
            &lt;input id="title" type="text" name="title" placeholder="Finish QED docs" required
                   class="p-[7px] rounded-[10px] text-black bg-white"&gt;
        &lt;/div&gt;
        &lt;div class="flex flex-col gap-[5px]"&gt;
            &lt;label for="description" class="text-[13px] text-[rgba(255,255,255,0.737)]"&gt;Description&lt;/label&gt;
            &lt;textarea id="description" name="description" rows="2"
                      class="p-[7px] rounded-[10px] text-black bg-white resize-none"&gt;&lt;/textarea&gt;
        &lt;/div&gt;
        &lt;button type="submit" class="w-max px-[15px] py-[10px] bg-[rgb(111,82,177)] hover:text-[rgb(255,143,39)] rounded-[12px] text-white font-bold cursor-pointer"&gt;
            Create Task
        &lt;/button&gt;
    &lt;/form&gt;

    &lt;!-- Task List --&gt;
    &lt;?php if (empty($tasks)): ?&gt;
        &lt;p class="text-[13px] text-[rgb(128,128,128)]"&gt;No tasks yet.&lt;/p&gt;
    &lt;?php else: ?&gt;
        &lt;ul class="flex flex-col gap-[10px]"&gt;
            &lt;?php foreach ($tasks as $task): ?&gt;
                &lt;?php component('task-card', ['task' =&gt; $task, 'base_path' =&gt; $base_path]); ?&gt;
            &lt;?php endforeach; ?&gt;
        &lt;/ul&gt;
    &lt;?php endif; ?&gt;
&lt;/section&gt;
          </code>
        </pre>
        <h4 class="text-base font-bold text-emerald-400">3. View, Update &amp; Delete a Single Task(<code>apps/tasks/[id]/app.php</code>)</h4>
        <p class="text-zinc-300">Create the folder <code>apps/tasks/[id]/</code> and add <code>app.php</code>:</p>
        <pre class="px-4 py-2 rounded-xl bg-zinc-950 border border-zinc-800 text-xs text-zinc-200 overflow-x-auto">
          <code class="bg-transparent">
&lt;?php
declare(strict_types=1);
$taskId = (int) ($params['id'] ?? 0);

// 1. Fetch task from DB
$task = DB::query('SELECT * FROM tasks WHERE id = ?', [$taskId])-&gt;fetch_assoc();

// 2. Return 404 if not found
if (!$task) {
    $meta['status'] = 404;
    $meta['title']  = 'Task Not Found';
    echo '&lt;section class="w-[88%] mt-[10vh] flex flex-col gap-[10px]"&gt;';
    echo '&lt;h1 class="text-[24px] font-black text-[rgb(255,43,43)]"&gt;404 — Task Not Found&lt;/h1&gt;';
    echo '&lt;a href="' . e($base_path) . '/tasks" class="text-[rgb(182,153,250)] underline"&gt;&amp;larr; Back to Tasks&lt;/a&gt;';
    echo '&lt;/section&gt;';
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
?&gt;

&lt;section class="w-[88%] min-h-[20vh] h-max flex flex-col mt-[10vh] p-[15px] gap-[15px]"&gt;
    &lt;a href="&lt;?= e($base_path) ?&gt;/tasks" class="text-[13px] text-[rgb(182,153,250)] underline"&gt;&amp;larr; Back to Tasks&lt;/a&gt;
    &lt;h1 class="text-[24px] font-black text-white"&gt;Edit Task #&lt;?= e($task['id']) ?&gt;&lt;/h1&gt;

    &lt;!-- Update Form --&gt;
    &lt;form method="POST" class="p-[15px] bg-gradient-to-b from-[rgb(28,30,31)] to-[rgb(41,40,43)] border border-[rgb(65,59,78)] rounded-[12px] flex flex-col gap-[10px]"&gt;
        &lt;?= csrf_field() ?&gt;
        &lt;input type="hidden" name="_action" value="update"&gt;

        &lt;label class="text-[13px] text-[rgba(255,255,255,0.737)]"&gt;Title&lt;/label&gt;
        &lt;input type="text" name="title" value="&lt;?= e($task['title']) ?&gt;" required class="p-[7px] rounded-[10px] text-black bg-white"&gt;

        &lt;label class="text-[13px] text-[rgba(255,255,255,0.737)]"&gt;Description&lt;/label&gt;
        &lt;textarea name="description" rows="3" class="p-[7px] rounded-[10px] text-black bg-white resize-none"&gt;&lt;?= e($task['description'] ?? '') ?&gt;&lt;/textarea&gt;

        &lt;label class="flex items-center gap-[8px] text-[13px] text-white cursor-pointer"&gt;
            &lt;input type="checkbox" name="is_done" value="1" &lt;?= $task['is_done'] ? 'checked' : '' ?&gt;&gt;
            Completed
        &lt;/label&gt;

        &lt;button type="submit" class="w-max px-[15px] py-[10px] bg-[rgb(111,82,177)] rounded-[12px] text-white font-bold cursor-pointer"&gt;
            Save Changes
        &lt;/button&gt;
    &lt;/form&gt;

    &lt;!-- Delete Form --&gt;
    &lt;form method="POST" onsubmit="return confirm('Delete this task?');"&gt;
        &lt;?= csrf_field() ?&gt;
        &lt;input type="hidden" name="_action" value="delete"&gt;
        &lt;button type="submit" class="px-[15px] py-[8px] bg-[red] rounded-[5px] text-white font-bold cursor-pointer"&gt;
            Delete Task
        &lt;/button&gt;
    &lt;/form&gt;
&lt;/section&gt;
          </code>
        </pre>
        <h4 class="text-base font-bold text-emerald-400">4. JSON API Endpoint (<code>apps/api/tasks/route.php</code>)</h4>
        <p class="text-zinc-300">If you want to fetch tasks via JavaScript <code>fetch('/qed/api/tasks')</code>, create <code>apps/api/tasks/route.php</code>:</p>
        <pre class="px-4 py-2 rounded-xl bg-zinc-950 border border-zinc-800 text-xs text-zinc-200 overflow-x-auto">
          <code class="bg-transparent">
&lt;?php
return [
    'GET' =&gt; static function (array $params): array {
        $tasks = DB::query('SELECT id, title, description, is_done, created_at FROM tasks ORDER BY id DESC')
            -&gt;fetch_all(MYSQLI_ASSOC);
        return ['ok' =&gt; true, 'tasks' =&gt; $tasks];
    },
];
          </code>
        </pre>
    </div>

    <div id="infotable" class="pb-8 scroll-mt-20 flex flex-col gap-4 border-b border-zinc-800">
      <h2 class="text-2xl font-bold text-white">Reference table</h2>
      <p class="text-zinc-300">this table contain every reference that might help you find what you need using this project</p>
      <div class="bg-zinc-900/60 border border-zinc-800 rounded-xl overflow-x-auto">
        <table class="w-full text-left border-collapse text-sm">
          <thead>
            <tr class="border-b border-zinc-800 bg-zinc-900 text-white">
              <th class="p-3 font-bold">Task/Function</th>
              <th class="p-3 font-bold">Command/Code</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-zinc-800 text-zinc-300">
            <tr>
              <td class="p-3 font-bold text-white">Install Tailwind locally (after cloning)</td>
              <td class="p-3"><code>npm install</code></td>
            </tr>
            <tr>
              <td class="p-3 font-bold text-white">Watch &amp; compile Tailwind while editing</td>
              <td class="p-3"><code>npm run dev</code></td>
            </tr>
            <tr>
              <td class="p-3 font-bold text-white">Minify <code>global.css</code> before pushing to Git</td>
              <td class="p-3"><code>npm run build</code></td>
            </tr>
            <tr>
              <td class="p-3 font-bold text-white">Escape output safely in HTML</td>
              <td class="p-3"><code>&lt;?= e($value) ?&gt;</code></td>
            </tr>
            <tr>
              <td class="p-3 font-bold text-white">Load an image from <code>/public/</code></td>
              <td class="p-3"><code>&lt;img src="&lt;?= public_asset('/logo/image.png') ?&gt;"&gt;</code></td>
            </tr>
            <tr>
              <td class="p-3 font-bold text-white">Load a JS script from <code>/script/</code></td>
              <td class="p-3"><code>&lt;script src="&lt;?= script_asset('/app.js') ?&gt;" defer&gt;&lt;/script&gt;</code></td>
            </tr>
            <tr>
              <td class="p-3 font-bold text-white">Add CSRF input to a <code>&lt;form method="POST"&gt;</code></td>
              <td class="p-3"><code>&lt;?= csrf_field() ?&gt;</code></td>
            </tr>
            <tr>
              <td class="p-3 font-bold text-white">Change page <code>&lt;title&gt;</code></td>
              <td class="p-3"><code>$meta['title'] = 'New Title';</code></td>
            </tr>
            <tr>
              <td class="p-3 font-bold text-white">Return a 404 status code</td>
              <td class="p-3"><code>$meta['status'] = 404;</code></td>
            </tr>
            <tr>
              <td class="p-3 font-bold text-white">Get a dynamic <code>[id]</code> folder value</td>
              <td class="p-3"><code>$id = $params['id'];</code></td>
            </tr>
            <tr>
              <td class="p-3 font-bold text-white">Run a <code>SELECT</code> query (all rows)</td>
              <td class="p-3"><code>$rows = DB::query('SELECT * FROM tasks WHERE is_done = ?', [0])-&gt;fetch_all(MYSQLI_ASSOC);</code></td>
            </tr>
            <tr>
              <td class="p-3 font-bold text-white">Run a <code>SELECT</code> query (single row)</td>
              <td class="p-3"><code>$row = DB::query('SELECT * FROM tasks WHERE id = ?', [$id])-&gt;fetch_assoc();</code></td>
            </tr>
            <tr>
              <td class="p-3 font-bold text-white">Run <code>INSERT</code> and get new ID</td>
              <td class="p-3"><code>DB::query('INSERT INTO tasks (title) VALUES (?)', [$title]); $id = DB::lastId();</code></td>
            </tr>
            <tr>
              <td class="p-3 font-bold text-white">Load a component from <code>components/</code></td>
              <td class="p-3"><code>&lt;?php component('task-card', ['task' =&gt; $task, 'base_path' =&gt; $base_path]); ?&gt;</code></td>
            </tr>
        </tbody>
      </table>
    </div>
  </main>
</div>