<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateAccountTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('account');
        $table
            ->addColumn('name', 'string', ['limit' => 255])
            ->addColumn('email', 'string', ['limit' => 100])
            ->addColumn('password', 'string', ['limit' => 255])
            ->addColumn('created', 'datetime')
            ->create();
    }
}
