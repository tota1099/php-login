<?php

namespace tests\infra\db\helpers;

use App\infra\db\helpers\MysqlHelper;
use PHPUnit\Framework\TestCase;

final class MysqlHelperTest extends TestCase
{
  public MysqlHelper $sut;

  public function setUp() : void {
    $this->sut = new MysqlHelper();
  }

  public function testInsertAndFetchRecord() {
    $params = [
      'name',
      (new \DateTime())->format('Y-m-d H:i:s')
    ];
    $lastRecord = $this->sut->fetch('SELECT MAX(id) as id FROM module');
    $id = $this->sut->insert('INSERT INTO module (name, created) VALUES (?,?)', $params);
    $recordInserted = $this->sut->fetch('SELECT name, created FROM module WHERE id = ' . $id);

    $this->assertEquals($id, $lastRecord['id'] + 1);
    $this->assertEquals($params[0], $recordInserted['name']);
    $this->assertEquals($params[1], $recordInserted['created']);
  }

  public function testExecuteUpdateRecord() {
    $params = [
      'name',
      (new \DateTime())->format('Y-m-d H:i:s')
    ];
    $id = $this->sut->insert('INSERT INTO module (name, created) VALUES (?,?)', $params);

    $this->sut->execute('UPDATE module SET name="another_name" WHERE id = '. $id);

    $recordUpdate = $this->sut->fetch('SELECT name, created FROM module WHERE id = ' . $id);

    $this->assertEquals('another_name', $recordUpdate['name']);
    $this->assertEquals($params[1], $recordUpdate['created']);
  }

  public function testExecuteDeleteRecord() {
    $params = [
      'name',
      (new \DateTime())->format('Y-m-d H:i:s')
    ];

    $id = $this->sut->insert('INSERT INTO module (name, created) VALUES (?,?)', $params);

    $recordInserted = $this->sut->fetch('SELECT name, created FROM module WHERE id = ' . $id);

    $this->assertEquals($params[0], $recordInserted['name']);
    $this->assertEquals($params[1], $recordInserted['created']);

    $this->sut->execute('DELETE FROM module WHERE id = ' . $id);

    $recordDeleted = $this->sut->fetch('SELECT name, created FROM module WHERE id = ' . $id);

    $this->assertFalse($recordDeleted);
  }

  public function testFetchAll() {
    $now = (new \DateTime())->format('Y-m-d H:i:s');
    
    $ids = [];

    $ids[] = $this->sut->insert('INSERT INTO module (name, created) VALUES (?,?)', ['name-1', $now]);
    $ids[] = $this->sut->insert('INSERT INTO module (name, created) VALUES (?,?)', ['name-2', $now]);
    $ids[] = $this->sut->insert('INSERT INTO module (name, created) VALUES (?,?)', ['name-3', $now]);

    $recordsInserted = $this->sut->fetchAll('SELECT name, created FROM module WHERE id IN (' . implode(',', $ids) . ')');

    $this->assertEquals(count($recordsInserted), count($ids));
  }
}