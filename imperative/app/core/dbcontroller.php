<?php

namespace MVC\core;

use Dcblogdev\PdoWrapper\Database;
use PDO;

class dbcontroller
{
  private $options;
  private $db;
  public function __construct()
  {
    $this->options = [
      //required
      'username' => 'root',
      'database' => 'hotel',
      //optional
      'password' => '',
      'type' => 'mysql',
      'charset' => 'utf8',
      'host' => 'localhost',
      'port' => '3306'
    ];
    $this->db = new Database($this->options);
  }
  public function getDb()
  {
    return $this->db->getPdo();
  }
  public function insert($tablename, $data)
  {
    if (!empty($data)) {
      return $this->db->insert($tablename, $data);
    }
  }
  public function  update($tablename, $data, $id)
  {
    return $this->db->update($tablename, $data, $id);
  }
  public function delete($tablename, $id)
  {
    return $this->db->delete($tablename, $id);
  }
  public function select($query)
  {
    $data = $this->db->run($query)->fetchAll(PDO::FETCH_ASSOC);
    if (count($data) == 0) {
      return [];
    } else {
      return $data;
    }
  }
}
