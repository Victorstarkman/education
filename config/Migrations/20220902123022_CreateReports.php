<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateReports extends AbstractMigration
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
        $table = $this->table('reports');
        $table->addColumn('id', 'integer', [
            'autoIncrement' => true,
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('patient_id', 'integer', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('medicalCenter', 'integer', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('doctor_id', 'integer', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('user_id', 'integer', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('pathology', 'string', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('startPathology', 'date', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('comments', 'text', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('type', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('askedDays', 'integer', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('recommendedDays', 'integer', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('startLicense', 'date', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('cie10', 'string', [
            'default' => null,
            'limit' => 120,
            'null' => true,
        ]);
        $table->addColumn('relativeName', 'string', [
            'default' => null,
            'limit' => 120,
            'null' => true,
        ]);
        $table->addColumn('observations', 'text', [
            'default' => null,
            'limit' => 120,
            'null' => true,
        ]);
        $table->addColumn('status', 'integer', [
            'default' => 1,
            'limit' => 11,
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
