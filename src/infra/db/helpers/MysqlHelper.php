<?php

namespace App\infra\db\helpers;

class MysqlHelper {
  private ?\PDO $database;

  private function connect() {
    $this->database = new \PDO($_ENV['DATABASE_URI'], $_ENV['DATABASE_USER'], $_ENV['DATABASE_PASSWORD']);
  }

  private function disconnect() {
    $this->database = null;
  }

  public function fetch($sql, $params = []) : Array|bool {
    $this->connect();
    $stmt= $this->getDataBase()->prepare($sql);
    $stmt->execute($params);
    $result = $stmt->fetch(\PDO::FETCH_ASSOC);
    $this->disconnect();
    return $result;
  }

  public function fetchAll($sql) : Array {
    $this->connect();
    $stmt= $this->getDataBase()->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    $this->disconnect();
    return $result;
  }

  public function insert($sql, $params = []) {
    $this->connect();
    $stmt= $this->getDataBase()->prepare($sql);
    $stmt->execute($params);
    $lastId = $this->getDataBase()->lastInsertId();
    $this->disconnect();
    return $lastId;
  }

  public function execute($sql, $params = []) : bool {
    $this->connect();
    $stmt= $this->getDataBase()->prepare($sql);
    $success = $stmt->execute($params);
    $this->disconnect();
    return $success;
  }

  public function exists($sql, $params = []) {
    $this->connect();
    $stmt = $this->getDataBase()->prepare($sql);
    $stmt->execute($params);
    $exists = $stmt->fetchColumn() > 0; 
    $this->disconnect();
    return $exists;
  }

  public function getDataBase() {
    return $this->database;
  }
}