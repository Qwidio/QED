## 5/11/26 - making it become more like Next.js
I went through lots of docs[(see the references.md)](https://github.com/Qwidio/QED/blob/main/README.md) just to make it actually work like a simpler PHP version of Nextjs without making it easy to exploit. some incomplete and missing things were like below:
  * The router was still a manual list: 
  In [`README.md`](https://github.com/Qwidio/QED/blob/main/README.md), I had sketched out a Next.js folder layout (`apps/app.php`, `apps/dashboard/app.php`), but [`index.php`](https://github.com/Qwidio/QED/blob/main/index.php) was still using a hardcoded `$routes` array—and `/about` and `/pages/404.php` didn't exist yet, so visiting anything other than `/` crashed PHP.
  * People could open internal PHP files directly: 
  Because [`.htaccess`](https://github.com/Qwidio/QED/blob/main/.htaccess) had `RewriteCond %{REQUEST_FILENAME} !-f`, Apache let anyone visit `/qed/config.php` or `/qed/layouts/main.php` straight from the browser instead of forcing them through `index.php`.
  * Layouts ran backwards: 
  [`layouts/main.php`](https://github.com/Qwidio/QED/blob/main/layouts/main.php) printed `<!DOCTYPE html>` and `<title>` *before* loading the actual page (`require $meta_file;`). That meant a page couldn't change its own `<title>` from the database, return a `404` status, or redirect after a form submit without hitting PHP's infamous `"Cannot modify header information - headers already sent"` error.
  * CSS broke on deeper URLs: 
  Using `href="../global.css"` in [`layouts/main.php`](https://github.com/Qwidio/QED/blob/main/layouts/main.php) worked on `/qed/`, but broke as soon as a URL had extra slashes like `/qed/dashboard/settings`.
  * DB credentials were sitting in global variables: 
  [`config.php`](https://github.com/Qwidio/QED/blob/main/config.php) had `$host`, `$db_user`, and `$db_pass` hardcoded at the top where any included file could see them.
Yeah, that's why I'd spent the whole day just to properly implement this. the other reason was also that I'll be using this as the basis for my larger projects.

### Added real folder-based routing (without the LFI vulnerabilities): 
Replaced the manual `$routes` array in [`index.php`](https://github.com/Qwidio/QED/blob/main/index.php) with `resolve_app_route()`. Now dropping a `page.php` or `app.php` inside any folder in `apps/` automatically becomes a route. Added support for `[id]` / `[slug]` dynamic folders (which pass values into `$params`) and `route.php` files for quick JSON APIs. To keep people from doing `../../` path-climbing attacks, every URL piece is checked against a strict alphanumeric regex and locked inside `apps/` with `realpath()`.
### Flipped the render order with `ob_start()`: 
Changed `index.php` so the page runs *first* inside an output buffer, saves its HTML into `$children`, and then wraps itself outward through any nested `layout.php` files and finally [`layouts/main.php`](https://github.com/Qwidio/QED/blob/main/layouts/main.php). Now any page can change `$meta['title']`, set `$meta['status'] = 404`, or redirect with `header('Location: ...')` whenever it wants.
### Cleaned up `config.php` & security: 
Moved DB credentials into a `.env` file loaded inside a `DB` class so passwords aren't floating around in global variables. Added a one-liner `DB::query($sql, $params)` helper for prepared statements, hardened `session_start()` cookies, added automatic CSRF token checking on `POST` forms (`csrf_field()`), and added a `component()` helper so reusable UI pieces in `components/` don't leak variables.
### Locked down `.htaccess`: 
Blocked direct browser access to `config.php`, `apps/`, `layouts/`, `components/`, and dotfiles like `.env`.
### docs, docs, docs (`documentation.md`): 
Putting together a full guide comparing this setup with normal PHP and walking through how to build a complete Task Manager CRUD app, noted I do asked AI for helping me making it actually somewhat usefull as I'm already too tired at the time of writing

### properly making access to `/public/` images and `/script/` JS files easy & safe
I've realized that if I wanted to put images inside `/public/` (like `/image.png` or `/logo/image.png`) or JavaScript files inside `/script/`, there wasn't a clean way to load them across different pages and embarrassingly I've just realized it when started to work on the `app.php`,
At first I thought about doing something like `$publicAsset = __DIR__ . "/public/"`, but the thing is that `__DIR__` actually gives the hosted site computer's local hard drive path (like `C:\xampp\htdocs\qed\public\`), which the browser blocks if you put it inside an `<img src="...">` tag. The browser needs a URL path starting with `$base_path` (`/qed/public/...`) and that's why I did the thing below

### Added `public_asset()` for images (`config.php`): 
Wrote a small helper function that takes `'/image.png'` or `'/logo/image.png'` and turns it into `/qed/public/logo/image.png`. To keep it safe, it blocks `..` path traversal and only 
accepts image extensions (`.png`, `.jpg`, `.jpeg`, `.gif`, `.webp`, `.avif`, `.svg`, `.ico`). If someone passes anything else (like a `.php` file), it just returns an empty string `""`.
### Added `script_asset()` for JavaScript (`config.php`): 
Made the matching helper for `/script/` that blocks `..` and only 
accepts `.js` and `.mjs` files.
### Backed both folders up in `.htaccess`: 
Added two short rewrite rules in [`.htaccess`](https://github.com/Qwidio/QED/blob/main/.htaccess) so that even if someone types `/qed/public/shell.php` or `/qed/script/test.php` directly into the browser bar, Apache blocks it with a `403 Forbidden` unless it's an allowed image or `.js` file.
### Updated `documentation.md`: 
Added the new `public_asset()` and `script_asset()` helpers and `.htaccess` rules into the main documentation so everything stays in sync.