<?php

class MysqlHelper {
  private ?PDO $database;

  private function connect() {
    try {
      $this->database = new PDO($_ENV['DATABASE_URI']);
    } catch (PDOException $e) {
      throw $e;
    }
  }

  private function disconnect() {
    $this->database = null;
  }

  public function fetch($sql, $params = []) : Array {
    $this->connect();
    $stmt= $this->getDataBase()->prepare($sql);
    $stmt->execute($params);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $this->disconnect();
    return $result;
  }

  public function fetchAll($sql) : Array {
    $this->connect();
    $stmt= $this->getDataBase()->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

  public function update($sql, $params = []) {
    $this->connect();
    $stmt= $this->getDataBase()->prepare($sql);
    $stmt->execute($params);
    $this->disconnect();
  }

  public function remove($sql, $params = []) {
    $this->connect();
    $stmt= $this->getDataBase()->prepare($sql);
    $stmt->execute($params);
    $this->disconnect();
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