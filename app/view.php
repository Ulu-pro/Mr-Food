<?php
require "../text.php";
require "../types.php";
require "../db.php";
if (!isset($db)) exit;

$order_id = $_GET["order_id"];
$chat_id = $_GET["chat_id"];

$user_id = $db->get_user_id($chat_id);
[$owner_id, $comment, $status_code] =
    $db->get_order_info($order_id);

if ($user_id != $owner_id) {
  exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Mister Food | Order</title>
  <link rel="stylesheet" href="style.css">
  <!--suppress CssUnusedSymbol -->
  <style>
    .order, .items .item {
     display: block !important;
    }
  </style>
</head>
<body>
<section class="order">
  <h1 class="header"><?php echo sprintf(Text::ORDER_ID_HEADER, $order_id) ?></h1>
  <span class="edit"><?php echo Text::STATUS[$status_code] ?></span>
  <div class="items">
    <?php
    $db->order_items($order_id, function ($type_id, $quantity, $name, $type_name, $price) {
      $price = price_format($price, 0);
      echo "
    <div class='item'>
      <img class='image' src='images/$type_id.jpg' alt='$name $type_name'>
      <span class='name'>$name
        <span class='quantity'>$quantity</span>
      </span>
      <div class='type'>$type_name</div>
      <div class='price'>$price</div>
    </div>
      ";
    });
    ?>
  </div>
  <input id="comment" type="text" class="comment" value="<?php echo $comment ?>" disabled>
  <label for="comment" class="comment-label"><?php echo Text::ORDER_COMMENT_DESCRIPTION ?></label>
</section>
<script src="https://telegram.org/js/telegram-web-app.js?0"></script>
<!--suppress JSUnresolvedVariable,JSUnresolvedFunction-->
<script>window.Telegram.WebApp.expand();</script>
</body>
</html>