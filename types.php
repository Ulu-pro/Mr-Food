<?php /** @noinspection PhpArrayShapeAttributeCanBeAddedInspection */
function keyboard($rows): string {
  return json_encode([
      "inline_keyboard" => $rows
  ], JSON_HEX_TAG);
}

function button($text, $web_app = null, $pay = false): array {
  $array = [
      "text" => $text
  ];
  if ($web_app != null) {
    $array["web_app"] = [
        "url" => $web_app
    ];
  } else if ($pay) {
    $array["pay"] = true;
  }
  return $array;
}

function labeledPrice($label, $amount): array {
  return [
      "label" => $label,
      "amount" => $amount
  ];
}