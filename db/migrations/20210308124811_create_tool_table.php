<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateToolTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('tool');
        $table
            ->addColumn('name', 'string', ['limit' => 255])
            ->addColumn('moduleId', 'integer', ['limit' => 11, 'null' => false])
            ->addColumn('created', 'datetime')
            ->addForeignKey('moduleId', 'module', 'id', ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION'])
            ->create();
    }
}
