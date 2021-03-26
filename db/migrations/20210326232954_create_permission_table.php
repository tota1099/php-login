<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreatePermissionTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('permission');
        $table
            ->addColumn('name', 'string', ['limit' => 255])
            ->addColumn('toolId', 'integer', ['limit' => 11, 'null' => false])
            ->addColumn('accountId', 'integer', ['limit' => 11, 'null' => false])
            ->addColumn('type', 'integer', ['limit' => 2, 'null' => false])
            ->addColumn('created', 'datetime')
            ->addForeignKey('toolId', 'tool', 'id', ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION'])
            ->addForeignKey('accountId', 'account', 'id', ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION'])
            ->create();
    }
}
