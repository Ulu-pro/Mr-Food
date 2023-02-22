<?php
require "bot.php";
require "env.php";
require "text.php";

$bot = new Bot(Env::TOKEN);
$data = $bot->getData();
file_put_contents("log.txt", json_encode($data, true)."\n", FILE_APPEND);

$message = $data["message"];
$chat_id = $message["chat"]["id"];
$text = $message["text"];

function keyboard($rows): string {
  return json_encode([
      "resize_keyboard" => true,
      "keyboard" => $rows
  ], true);
}

if ($text == "/ping" && in_array($chat_id, Env::ADMIN_ID)) {
  $bot->sendMessage($chat_id, Text::PING);
}

else if ($text == "/start") {
  $sign_up = keyboard([
      [
          [
              "text" => Text::SIGN_UP,
              "request_contact" => true
          ]
      ]
  ]);
  $bot->sendMessage($chat_id, Text::START, $sign_up);
}

else if (isset($message["contact"])) {
  $phone = $message["contact"]["phone_number"];
  $phone = substr($phone, 1);
  $bot->sendMessage($chat_id, $phone);
}