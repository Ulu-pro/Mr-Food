<?php
require "bot.php";
require "text.php";
require "types.php";
require "db.php";
if (!isset($db)) exit;

$bot = new Bot(Env::TOKEN);
$data = $bot->getData();
$app = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]app/";
file_put_contents("log.txt", json_encode($data)."\n", FILE_APPEND);

if (isset($data["order_items"])) {
  $chat_id = $data["chat_id"];
  $comment = $data["comment"];
  $order_items = $data["order_items"];
  $purchase = [];
  $total_price = 0;

  foreach ($order_items as $item) {
    $type_id = $item["type_id"];
    $quantity = $item["quantity"];
    [$product_name, $type_name, $type_price] =
        $db->get_product_type($type_id);
    $price = $quantity * $type_price;
    $total_price += $price;
    $purchase[] = labeledPrice(sprintf(Text::ORDER_ITEM_FORMAT,
      $product_name, $type_name, $quantity), $price * Env::DECIMAL);
  }

  // TODO: create order and get it's id
  $bot->sendInvoice(
      $chat_id,
      sprintf(Text::INVOICE_TITLE, "0"),
      Text::INVOICE_DESCRIPTION,
      "invoice-payload",
      Env::PROVIDERS["CLICK"],
      Env::CURRENCY,
      json_encode($purchase, JSON_HEX_TAG),
      "pay-order",
      $app . Env::PHOTO_NAME,
      Env::PHOTO_SIZE,
      Env::PHOTO_DIMENSIONS["W"],
      Env::PHOTO_DIMENSIONS["H"],
      keyboard([
          [
              button(
                  sprintf(Text::BUTTON_PAY_ORDER,
                      number_format($total_price, log10(Env::DECIMAL),
                          ",", " ")
              ), null, true)
          ]
      ])
  );
} else {
  $message = $data["message"];
  $chat_id = $message["chat"]["id"];

  switch (substr($message["text"], 1)) {
    case "start":
      $bot->sendMessage($chat_id, Text::START, keyboard([
          [
              button(Text::START_BUTTON, $app)
          ]
      ]));
      break;
    default:
      $bot->sendMessage($chat_id, Text::HELP);
  }
}