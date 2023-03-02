<?php
require "../env.php";
require "../text.php";
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
  <link rel="stylesheet" href="style.css?<?php echo time() ?>">
</head>
<body>
<section class="menu">
  <?php
  $db->menu_list(function ($id, $name, $type, $price) {
    echo "
  <div class='card'>
    <div class='quantity'>0</div>
    <img class='image' src='images/$id.jpg' alt='$name'>
    <div class='name'>$name</div>
    <div class='type'>$type</div>
    <div class='price'>$price UZS</div>
    <div class='counters'>
      <button class='counter-minus'>-</button>
      <button class='counter-plus'>+</button>
    </div>
  </div>
    ";
  });
  ?>
</section>
<script src="https://telegram.org/js/telegram-web-app.js?0"></script>
<!--suppress JSUnresolvedVariable-->
<script>
let App = window.Telegram.WebApp;
App.MainButton.text = "<?php echo Text::BUTTON_TOTAL_ORDER ?>";
</script>
<script src="script.js?<?php echo time() ?>"></script>
</body>
</html>