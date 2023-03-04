<?php
require "bot.php";
require "text.php";
require "types.php";
require "db.php";

$bot = new Bot(Env::TOKEN);
$data = $bot->getData();
$app = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]app/";
file_put_contents("log.txt", json_encode($data, true)."\n", FILE_APPEND);

$message = $data["message"];
$chat_id = $message["chat"]["id"];
$text = $message["text"];

function keyboard_markup($message, $inline, $url) {
  global $bot, $chat_id;
  $bot->sendMessage($chat_id, $message, keyboard([
      [
          button($inline, $url)
      ]
  ]));
}

switch (substr($text, 1)) {
  case "start":
    keyboard_markup(
        Text::START,
        Text::START_BUTTON,
        $app
    );
    break;
  case "orders":
    // TODO: show orders page of chat_id
    keyboard_markup(
        "Order",
        "Button",
        $app
    );
    break;
  case "ping":
    if (in_array($chat_id, Env::ADMIN_ID)) {
      $bot->sendMessage($chat_id, Text::PING);
    }
    break;
  default:
    $bot->sendMessage($chat_id, Text::HELP);
}