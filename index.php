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
  $items = [];
  $labels = [];
  $total_price = 0;

  foreach ($order_items as $item) {
    $type_id = $item["type_id"];
    $quantity = $item["quantity"];
    $items[$type_id] = $quantity;
    [$product_name, $type_name, $type_price] =
        $db->get_product_info($type_id);
    $price = $quantity * $type_price;
    $total_price += $price;
    $labels[] = labeledPrice(sprintf(Text::ORDER_ITEM_FORMAT,
      $product_name, $type_name, $quantity), $price * Env::DECIMAL);
  }

  $user_id = $db->get_user_id($chat_id);
  $order_id = $db->init_order($user_id, $comment, $items);

  $bot->sendInvoice(
      $chat_id,
      sprintf(Text::INVOICE_TITLE, $order_id),
      Text::INVOICE_DESCRIPTION,
      "order-$order_id",
      Env::PAYMENT_TOKEN,
      Env::CURRENCY,
      json_encode($labels, JSON_HEX_TAG),
      "pay-order",
      $app . Env::PHOTO_NAME,
      Env::PHOTO_SIZE,
      Env::PHOTO_DIMENSIONS["W"],
      Env::PHOTO_DIMENSIONS["H"],
      keyboard([
          [
              button(price_format($total_price), null, true)
          ]
      ])
  );
}

else if (isset($data["pre_checkout_query"])) {
  $query = $data["pre_checkout_query"];
  $bot->answerPreCheckoutQuery($query["id"], true);
}

else if (isset($data["callback_query"])) {
  $query = $data["callback_query"];
  $bot->answerCallbackQuery($query["id"]);

  $query_data = $query["data"];
  $order_id = explode("-", $query_data)[1];
  $chat_id = $query["from"]["id"];
  $message = $query["message"];
  $message_id = $message["message_id"];
  [$amount, $owner_id, $name, $phone, $address] =
      $db->get_order_user_info($order_id);
  $text = sprintf(Text::DELIVERED_ORDER,
      $order_id, price_format($amount, 0),
      $name, $phone, $address);
  $inline = keyboard([
      more_details_row($app, $order_id, $chat_id)
  ]);

  $db->delivered_order($order_id);
  $bot->editMessageText($chat_id, $message_id, $text, $inline);
  $bot->sendMessage($owner_id, $text, $inline);
}

else if (isset($data["message"])) {
  $message = $data["message"];
  $chat_id = $message["from"]["id"];

  if (isset($message["successful_payment"])) {
    $payment = $message["successful_payment"];
    $payload = $payment["invoice_payload"];
    $info = $payment["order_info"];
    $name = $info["name"];
    $phone = $info["phone_number"];
    $shipping = $info["shipping_address"];
    $address = $shipping["street_line1"];
    if ($address == "") {
      $address = $shipping["street_line2"];
    }

    $order_id = explode("-", $payload)[1];
    $amount = $payment["total_amount"] / Env::DECIMAL;
    $db->paid_order($order_id, $amount);
    $db->set_user_info($chat_id, $name, $phone, $address);
    $order = sprintf(Text::PAID_ORDER,
      $order_id, price_format($amount, 0),
      $name, $phone, $address);
    $inline = [more_details_row($app, $order_id, $chat_id)];
    $bot->sendMessage($chat_id, $order, keyboard($inline));

    $inline[] = [
        button(Text::STATUS[2], null, false,
            "order-$order_id")
    ];
    foreach (Env::ADMIN_ID as $admin_id) {
      $bot->sendMessage($admin_id, $order, keyboard($inline));
    }
  }

  else if (isset($message["text"])) {
    $text = $message["text"];
    if ($text == "/start") {
      $bot->sendMessage($chat_id, Text::START, keyboard([
          [
              button(Text::START_BUTTON, $app)
          ]
      ]));
    }
  }
}