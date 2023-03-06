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

  public function product_list($callback) {
    $result = $this->db->query("
        SELECT `product`.product_id,
               `product`.product_name,
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
          $row["product_id"],
          $row["product_name"],
          $row["product_type_id"],
          $row["product_type_name"],
          $row["product_type_price"],
      );
    }
  }

  public function get_product_type($type_id): array {
    $type_id = $this->db->real_escape_string($type_id);
    $result = $this->db->query("
        SELECT
               `product`.product_name,
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
}

$db = new DB();