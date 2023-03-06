<?php
class Bot {
  private string $url;
  private mixed $data;

  public function __construct($token) {
    $this->url = "https://api.telegram.org/bot$token/";
    $this->data = $this->getData();
  }

  public function getData() {
    if (empty($this->data)) {
      $raw_data = file_get_contents("php://input");
      return json_decode($raw_data, true);
    } else {
      return $this->data;
    }
  }

  public function request($method, $params) {
    $_ = file_get_contents($this->url . $method . "?" . http_build_query($params));
    file_put_contents('log.txt', json_encode($params)."\n", FILE_APPEND);
    unset($_);
  }

  public function sendMessage($chat_id, $text, $reply_markup = []) {
    $this->request("sendMessage", [
        "chat_id" => $chat_id,
        "text" => $text,
        "parse_mode" => "Markdown",
        "reply_markup" => $reply_markup
    ]);
  }

  public function sendInvoice(
      $chat_id, $title, $description, $payload, $provider_token, $currency, $prices,
      $start_parameter, $photo_url, $photo_size, $photo_width, $photo_height, $reply_markup = []
  ) {
    $this->request("sendInvoice", [
        "chat_id" => $chat_id,
        "title" => $title,
        "description" => $description,
        "payload" => $payload,
        "provider_token" => $provider_token,
        "currency" => $currency,
        "prices" => $prices,
        "start_parameter" => $start_parameter,
        "photo_url" => $photo_url,
        "photo_size" => $photo_size,
        "photo_width" => $photo_width,
        "photo_height" => $photo_height,
        "need_name" => true,
        "need_phone_number" => true,
        "need_shipping_address" => true,
        "reply_markup" => $reply_markup
    ]);
  }
}