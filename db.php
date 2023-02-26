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

  public function select($table, $callback) {
    $quoted_table = "`$table`";
    $result = $this->db->query("SELECT * FROM $quoted_table");
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $callback(Tables::parse($table, $row));
      }
    }
  }
}

class Tables {
  const CATEGORY = "category";
  const PRODUCT = "product";
  const PRODUCT_TYPE = "product_type";

  public static function parse($table, $object): array {
    return match ($table) {
      self::CATEGORY => [
          $object["category_id"],
          $object["category_name"]
      ],
      self::PRODUCT => [
          $object["product_id"],
          $object["category_id"],
          $object["product_name"]
      ],
      self::PRODUCT_TYPE => [
          $object["product_type_id"],
          $object["product_id"],
          $object["product_type_price"]
      ],
      default => [],
    };
  }
}

$db = new DB();