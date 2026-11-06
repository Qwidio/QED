# Q.E.D
why does it named like that? this is the name I come up when making the project

## About
Making my own implementation of NextJS Router App with PHP, targeted for the development of larger projects.

## documentation
see this [documentation.md](documentation.md) for full information about the whole projects <br>
list of [references](references.md) used on for this project development
[journals.md](journals.md) like usual <br>

## Running the project locally
- **Requirements:** PHP 8.2+, Apache HTTP Server (mod_rewrite), MySQL 8.0+ / MariaDB 10.6+ (mysqli)
1. Clone [Qwidio/QED](https://github.com/Qwidio/QED) into your Apache document root (e.g., `htdocs/qed` or `/var/www/html/qed`). If you host Q.E.D. at the root domain (`/`) instead of `/qed`, set `$base_path = '';` in `config.php`.
2. Copy `.env.example` to `.env` and configure your MySQL credentials:
   ```ini
   DB_HOST = "localhost"
   DB_USER = "root"
   DB_PASS = ""
   DB_NAME = "qed"
   ```
3. Ensure Apache `mod_rewrite` is enabled and `AllowOverride All` is set for the installed repo directory.
- *note: for enabling the tailwinds in your development installation, please refer to the [docs](documentation.md)*