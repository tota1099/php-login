<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateModuleTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('module');
        $table
            ->addColumn('name', 'string', ['limit' => 255])
            ->addColumn('created', 'datetime')
            ->create();
    }
}
