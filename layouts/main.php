<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../global.css">
  <title><?= htmlspecialchars($meta_title, ENT_QUOTES, 'UTF-8') ?></title>
</head>
<body>
  <?php require $meta_file;?>
</body>
</html>