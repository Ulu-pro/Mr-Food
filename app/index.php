<?php
require "../text.php";
require "../db.php";
if (!isset($db)) exit;
?>
<!DOCTYPE html>
<html lang="uz">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Mister Food</title>
  <link rel="stylesheet" href="style.css?<?php echo time() ?>">
</head>
<body>
<section class="menu">
  <?php
  $db->product_list(function ($id, $name, $type_id, $type_name, $price) {
    echo "
  <div class='card' data-type-id='$type_id' data-type-price='$price'>
    <div class='quantity'>0</div>
    <img class='image' src='images/$id.jpg' alt='$name'>
    <div class='name'>$name</div>
    <div class='type'>$type_name</div>
    <div class='price'></div>
    <div class='counters'>
      <button class='counter-minus'>-</button>
      <button class='counter-plus'>+</button>
    </div>
  </div>
    ";
  });
  ?>
</section>
<section class="order">
  <h1 class="header"><?php echo Text::ORDER_HEADER ?></h1>
  <span class="edit"><?php echo Text::ORDER_EDIT ?></span>
  <div class="items">
    <?php
    $db->product_list(function ($id, $name, $type_id, $type_name) {
      echo "
    <div class='item' data-item-id='$type_id'>
      <img class='image' src='images/$id.jpg' alt='$name'>
      <span class='name'>$name
        <span class='quantity'></span>
      </span>
      <div class='type'>$type_name</div>
      <div class='price'></div>
    </div>
      ";
    });
    ?>
  </div>
  <input id="comment" type="text" class="comment" placeholder="<?php echo Text::ORDER_COMMENT_PLACEHOLDER ?>">
  <label for="comment" class="comment-label"><?php echo Text::ORDER_COMMENT_DESCRIPTION ?></label>
</section>
<script src="https://telegram.org/js/telegram-web-app.js?0"></script>
<!--suppress JSUnresolvedVariable,JSUnresolvedFunction-->
<script>
const App = window.Telegram.WebApp;
const total_order_text = "<?php echo Text::BUTTON_TOTAL_ORDER ?>";
App.expand();
App.MainButton.text = total_order_text;
function formattedPrice(price) {
  return "<?php echo Text::BUTTON_PAY_ORDER ?>".replace(
      '%s', parseInt(price).toLocaleString('uz-UZ'));
}
</script>
<script src="script.js?<?php echo time() ?>"></script>
</body>
</html>