<?php

namespace App\Storage;

use App\Model\Customer;
use App\Model\Transaction;
use App\Model\User;
use PDO;
use PDOException;

class DB implements Storage
{
  private string $serverName = "localhost";
  private string $databaseName = "banking";
  private string $userName = "root";
  private string $password = "";

  private ?PDO $conn = null;
  public function __construct()
  {
    try {
      $this->conn = new PDO("mysql:host=$this->serverName;dbname=$this->databaseName", $this->userName, $this->password);
      // set the PDO error mode to exception
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      echo "Connection failed: " . $e->getMessage();
    }
  }
  public function __destruct()
  {
    $this->conn = null;
  }

  public function createTable(string $sql)
  {
    try {
      $this->conn->exec($sql);
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }

  public function save(string $model, array $data): bool
  {
    try {
      $columns = "";
      $values = "";
      //echo "<pre>";print_r($data);exit();
      foreach ($data as $key => $value) {
        $columns .= $key . ',';
        $values .= "'$value'" . ',';
      }
      $columns = rtrim($columns, ",");
      $values = rtrim($values, ",");

      $sql = "INSERT INTO " . $model . "($columns) VALUES($values);"; //exit();
      $this->conn->exec($sql);
      return true;
    } catch (PDOException $e) {
      return false;
    }
  }

  public function loadAll(string $model): array
  {
    $stmt = $this->conn->prepare("SELECT * FROM $model;");
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $result = $stmt->fetchAll();
    return $result;
  }


  public function loadWhere($model, array $where): ?array
  {
    $whereClause = "";
    if ($where['email'] != null) {
      foreach ($where as $key => $value) {
        $whereClause .= " $key='$value' AND";
      }
      $whereClause = rtrim($whereClause, "AND");
      $whereClause = " WHERE $whereClause";
    } else {
      $whereClause = "";
    }
    $sql = "SELECT * FROM $model $whereClause;";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $data = $stmt->fetchAll();

    if ($data) {
      return $data;
    } else {
      return null;
    }
  }
  public function loadJoinWhere($model1, $model2, array $where, array $joinBetween, array $select): ?array
  {
    $whereClause = "";
    //print_r($where);
    $noCondition = false;
    foreach ($where as $key => $value) {
      if ($value == null) {
        $noCondition = true;
        break;
      }
    }
    if ($noCondition) {
      $whereClause = "";
    } else {
      if ($where) {
        foreach ($where as $key => $value) {
          $whereClause .= " $key='$value' AND";
        }
        $whereClause = rtrim($whereClause, "AND");
        $whereClause = " WHERE $whereClause";
      } else {
        $whereClause = "";
      }
    }


    // $values = "";
    // foreach ($where as $key => $value) {
    //   $values .= " $key='$value' AND";
    // }
    // $values = rtrim($values, "AND");

    $join = "";
    foreach ($joinBetween as $key1 => $key2) {
      $join .= " $model1.$key1=$model2.$key2 AND";
    }
    $join = rtrim($join, "AND");

    $columns = "";
    //print_r($select);
    foreach ($select as $table => $key) {
      $keys = explode(",", $key);
      foreach ($keys as $aKey) {
        $columns .= " $table.$aKey,";
      }
      // if(str_contains( $columns,  $key)){
      //   $key="_$key";
      // }
      //$columns .= " $table.$key,";
    }
    $columns = rtrim($columns, ",");

    $sql = "SELECT $columns FROM $model1 INNER JOIN $model2 ON $join $whereClause;"; //exit();
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $data = $stmt->fetchAll();

    if ($data) {
      return $data;
    } else {
      return null;
    }
  }

  public function getModelPath(string $model): string
  {
    return "data/" . $model . ".txt";
  }
}
