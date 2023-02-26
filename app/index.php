<?php
require "../env.php";
require "../db.php";
if (!isset($db)) exit;
?>
<!DOCTYPE html>
<html lang="uz">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Mister Food</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <?php
  $db->select(Tables::CATEGORY, function ($category) {
    [$id, $name] = $category;
    echo "$id: $name";
  });
  ?>
<script src="https://telegram.org/js/telegram-web-app.js?0"></script>
<!--suppress JSUnresolvedVariable-->
<script>
let App = window.Telegram.WebApp
App.MainButton.show()
</script>
</body>
</html>