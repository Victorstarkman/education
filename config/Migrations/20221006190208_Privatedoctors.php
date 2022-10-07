<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class Privatedoctors extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * @return void
     */
    public function change()
    {
        $table = $this->table('privatedoctors');
        $table->addColumn('name', 'string', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('lastname', 'string', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('license', 'string', [
            'default' => null,
            'limit' => 225,
            'null' => true,
        ]);
        $table->addColumn('licenseNational', 'string', [
            'default' => null,
            'limit' => 225,
            'null' => true,
        ]);
        $table->create();
    }
}
