# QED
why does it named like that? this is the name I come up when making the project
<div align="center">
   <img src="example.png" width="50%" alt="logo">
</div>

## About
Making my own implementation of NextJS Router App with PHP, made for ease up routing and in turn faster development of larger projects(NOT GUARANTEED).

### Stacks
At the time of the writing; PHP, MYSQL & Tailwind(with NodeJS for development)


## documentation
see this [documentation.md](documentation.md) for full information about the whole projects <br>
list of [references](references.md) used on for this project development <br>
[journals.md](journals.md) is where I write most of my projects development journey and explanation of the decision making made on the projects

## Running the project locally
### Prerequisites
 On your target machine it is recommended to have:
 - PHP 8.2+, 
 - Apache HTTP Server (with mod_rewrite),
 - MySQL 8.0+ / MariaDB 10.6+ (mysqli)
 Or just using latest XAMPP that supported the newest PHP 8.2+

### Setup (Assumed using XAMPP)
  1. Download the [project in zip file](https://github.com/Qwidio/QED/archive/refs/heads/main.zip) and extract, or Clone the repository:<br>
     ```bash
     git clone https://github.com/Qwidio/QED.git
      ```
 2. move the downloaded/extracted repository (foldered) to the public directory of you apache server (`htdocs/` in case of XAMPP)
 3. Ensure Apache `mod_rewrite` is enabled and `AllowOverride All` is set for the installed repo directory.
 4. Copy `.env.example` to `.env` and configure your MySQL credentials:
      ```ini
      DB_HOST = "localhost"
      DB_USER = "root"
      DB_PASS = ""
      DB_NAME = "qed"
      ```
 5. Install & run tailwind after clonning
      ```bash
      npm Install
      npm run dev
      ```
 - *note: for detail about tailwinds in this project, refer to the [docs](documentation.md)*


### Reference table
Hopefuly this is actually useful
| Task/Function | Command/Code |
| :--- | :--- |
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

## Credits
  [CGCC](https://github.com/Qwidio/CrossGate-Community-Collection) by [QWIDIO(in other words ME)](https://github.com/cure53/DOMPurify) used for readme & docs writing references<br>

## License
  Distributed under the Apache 2.0 License. See [LICENSE](./LICENSE) for details.