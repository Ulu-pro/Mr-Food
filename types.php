<?php /** @noinspection PhpArrayShapeAttributeCanBeAddedInspection */
function keyboard($rows): string {
  return json_encode([
      "inline_keyboard" => $rows
  ], JSON_HEX_TAG);
}

function button($text, $web_app = null, $pay = false,
                $callback_data = ""): array {
  $array = [
      "text" => $text
  ];
  if ($web_app != null) {
    $array["web_app"] = [
        "url" => $web_app
    ];
  } else if ($pay) {
    $array["pay"] = true;
  } else if ($callback_data != "") {
    $array["callback_data"] = $callback_data;
  }
  return $array;
}

function labeledPrice($label, $amount): array {
  return [
      "label" => $label,
      "amount" => $amount
  ];
}

function price_format($amount, $decimal = null): string {
  return sprintf(Text::BUTTON_PAY_ORDER,
      number_format($amount, $decimal ?? log10(Env::DECIMAL),
          ",", " ")
  );
}

function more_details_row($app, $order_id, $chat_id): array {
  return [
      button(Text::MORE_DETAILS, $app .
          "view.php?order_id=$order_id&chat_id=$chat_id")
  ];
}