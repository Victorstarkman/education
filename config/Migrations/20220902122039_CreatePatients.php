<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreatePatients extends AbstractMigration
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
        $table = $this->table('patients');
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
        $table->addColumn('address', 'string', [
            'default' => null,
            'limit' => 25,
            'null' => false,
        ]);
        $table->addColumn('birthday', 'string', [
            'default' => null,
            'limit' => 120,
            'null' => false,
        ]);
        $table->addColumn('email', 'string', [
            'default' => null,
            'limit' => 180,
            'null' => true,
        ]);
        $table->addColumn('age', 'integer', [
            'default' => null,
            'limit' => 4,
            'null' => false,
        ]);
        $table->addColumn('document', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('job', 'string', [
            'default' => null,
            'limit' => 120,
            'null' => false,
        ]);
        $table->addColumn('seniority', 'integer', [
            'default' => null,
            'limit' => 4,
            'null' => false,
        ]);
        $table->addColumn('phone', 'string', [
            'default' => null,
            'limit' => 120,
            'null' => false,
        ]);

        $table->addColumn('company_id', 'integer', [
            'default' => null,
            'limit' => 4,
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
        $table->addPrimaryKey([
            'id',
        ]);
        $table->create();
    }
}
