<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateUsers extends AbstractMigration
{
    public $autoId = false;

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
        $table = $this->table('users');
        $table->addColumn('id', 'integer', [
            'autoIncrement' => true,
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('name', 'string', [
            'default' => null,
            'limit' => 120,
            'null' => false,
        ]);
        $table->addColumn('lastname', 'string', [
            'default' => null,
            'limit' => 120,
            'null' => false,
        ]);
        $table->addColumn('email', 'string', [
            'default' => null,
            'limit' => 80,
            'null' => false,
        ]);
        $table->addColumn('password', 'string', [
            'default' => null,
            'limit' => 225,
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
        $table->addColumn('phone', 'string', [
            'default' => null,
            'limit' => 225,
            'null' => true,
        ]);
        $table->addColumn('document', 'string', [
            'default' => null,
            'limit' => 225,
            'null' => true,
        ]);
        $table->addColumn('area', 'string', [
            'default' => null,
            'limit' => 225,
            'null' => true,
        ]);
        $table->addColumn('signature', 'text', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('group_id', 'integer', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('modified', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('deleted', 'datetime', [
            'default' => null,
            'null' => true,
        ]);
        $table->addPrimaryKey([
            'id',
        ]);
        $table->create();
    }
}
