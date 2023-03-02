<?php
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

  public function menu_list($callback) {
    $result = $this->db->query("
        SELECT
               `product`.product_id,
               `product`.product_name,
               `product_type`.product_type_name,
               `product_type`.product_type_price
        FROM `product`, `product_type`
        WHERE `product`.product_id = `product_type`.product_id
        ORDER BY `product`.product_id
    ");
    while ($row = $result->fetch_assoc()) {
      $callback(
          $row["product_id"],
          $row["product_name"],
          $row["product_type_name"],
          $row["product_type_price"],
      );
    }
  }
}

$db = new DB();