<?php
require "bot.php";
require "env.php";
require "text.php";
require "types.php";

$bot = new Bot(Env::TOKEN);
$data = $bot->getData();
$app = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]/app/";
file_put_contents("log.txt", json_encode($data, true)."\n", FILE_APPEND);

if (!isset($data["message"])) {
  exit;
}

$message = $data["message"];
$chat_id = $message["chat"]["id"];
$text = $message["text"];

if ($text == "/ping" && in_array($chat_id, Env::ADMIN_ID)) {
  $bot->sendMessage($chat_id, Text::PING);
}

else if ($text == "/start") {
  $bot->sendMessage($chat_id, Text::START, keyboard([
    [
      button(Text::ORDER, $app)
    ]
  ]));
}

else {
  $bot->sendMessage($chat_id, Text::UNKNOWN);
}