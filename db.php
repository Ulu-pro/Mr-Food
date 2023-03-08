<?php
require "env.php";
class DB {
  private mysqli $db;

  public function __construct() {
    $this->db = new mysqli(
        Env::DB_HOST,
        Env::DB_USER,
        Env::DB_PASS,
        Env::DB_NAME
    );
  }

  public function escape($content): string {
    return $this->db->real_escape_string($content);
  }

  public function product_list($callback) {
    $result = $this->db->query("
        SELECT `product`.product_name,
               `product_type`.product_type_id,
               `product_type`.product_type_name,
               `product_type`.product_type_price
        FROM `product`
        INNER JOIN `product_type`
        ON `product`.product_id = `product_type`.product_id
        ORDER BY `product`.product_id
    ");
    while ($row = $result->fetch_assoc()) {
      $callback(
          $row["product_name"],
          $row["product_type_id"],
          $row["product_type_name"],
          $row["product_type_price"],
      );
    }
  }

  public function get_product_info($type_id): array {
    $type_id = $this->escape($type_id);
    $result = $this->db->query("
        SELECT `product`.product_name,
               `product_type`.product_type_name,
               `product_type`.product_type_price
        FROM `product`
        INNER JOIN `product_type`
        ON `product`.product_id = `product_type`.product_id
        WHERE `product_type`.product_type_id = $type_id
    ");
    $row = $result->fetch_assoc();
    return [
        $row["product_name"],
        $row["product_type_name"],
        $row["product_type_price"]
    ];
  }

  public function get_user_id($chat_id): int {
    $chat_id = $this->escape($chat_id);
    $result = $this->db->query("
        SELECT user_id
        FROM `user`
        WHERE user_chat_id = $chat_id
    ");
    if ($result->num_rows > 0) {
      return $result->fetch_assoc()["user_id"];
    }
    $this->db->query("
        INSERT INTO `user` (user_chat_id)
        VALUE ($chat_id)
    ");
    return $this->get_user_id($chat_id);
  }

  public function set_user_info($chat_id, $name, $phone, $address) {
    $chat_id = $this->escape($chat_id);
    $name = $this->escape($name);
    $phone = $this->escape($phone);
    $address = $this->escape($address);
    $this->db->query("
        UPDATE `user`
        SET user_name = '$name',
            user_phone = '$phone',
            user_address = '$address'
        WHERE user_chat_id = $chat_id
    ");
  }

  public function init_order($user_id, $comment, $items): int {
    $user_id = $this->escape($user_id);
    $comment = $this->escape($comment);
    $this->db->query("
        INSERT INTO `order` (user_id, order_amount, order_comment, order_status)
        VALUES ('$user_id', 0, '$comment', 0)
    ");
    $result = $this->db->query("
        SELECT order_id
        FROM `order`
        WHERE user_id = $user_id
        ORDER BY order_id DESC
        LIMIT 1
    ");
    $order_id = $result->fetch_assoc()["order_id"];
    foreach ($items as $type_id => $quantity) {
      $this->db->query("
          INSERT INTO `order_item` (order_id, product_type_id, order_item_quantity)
          VALUES ($order_id, $type_id, $quantity)
      ");
    }
    return $order_id;
  }

  public function paid_order($order_id, $amount) {
    $order_id = $this->escape($order_id);
    $amount = $this->escape($amount);
    $this->db->query("
        UPDATE `order`
        SET order_amount = $amount,
            order_status = 1
        WHERE order_id = $order_id
    ");
  }

  public function delivered_order($order_id) {
    $order_id = $this->escape($order_id);
    $this->db->query("
        UPDATE `order`
        SET order_status = 2
        WHERE order_id = $order_id
    ");
  }

  public function get_order_info($order_id): array {
    $order_id = $this->escape($order_id);
    $result = $this->db->query("
        SELECT user_id,
               order_comment,
               order_status
        FROM `order`
        WHERE order_id = $order_id
    ");
    $row = $result->fetch_assoc();
    return [
        $row["user_id"],
        $row["order_comment"],
        $row["order_status"]
    ];
  }

  public function get_order_user_info($order_id): array {
    $order_id = $this->escape($order_id);
    $result = $this->db->query("
        SELECT `order`.order_amount,
               `user`.user_chat_id,
               `user`.user_name,
               `user`.user_phone,
               `user`.user_address
        FROM `order`
        INNER JOIN `user`
        ON `order`.user_id = `user`.user_id
        WHERE `order`.order_id = $order_id
    ");
    $row = $result->fetch_assoc();
    return [
        $row["order_amount"],
        $row["user_chat_id"],
        $row["user_name"],
        $row["user_phone"],
        $row["user_address"]
    ];
  }

  public function order_items($order_id, $callback) {
    $order_id = $this->escape($order_id);
    $result = $this->db->query("
        SELECT product_type_id,
               order_item_quantity
        FROM `order_item`
        WHERE order_id = $order_id
    ");
    while ($row = $result->fetch_assoc()) {
      $type_id = $row["product_type_id"];
      $quantity = $row["order_item_quantity"];
      [$product_name, $type_name, $type_price] =
          $this->get_product_info($type_id);
      $callback(
          $type_id,
          $quantity,
          $product_name,
          $type_name,
          $type_price
      );
    }
  }
}

$db = new DB();