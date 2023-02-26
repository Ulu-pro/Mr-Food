<?php
function keyboard($rows): string {
  return json_encode([
      "inline_keyboard" => $rows
  ], true);
}

/** @noinspection PhpArrayShapeAttributeCanBeAddedInspection */
function button($text, $web_app = ""): array {
  $array = [
      "text" => $text
  ];
  if ($web_app != "") {
    $array["web_app"] = [
        "url" => $web_app
    ];
  }
  return $array;
}