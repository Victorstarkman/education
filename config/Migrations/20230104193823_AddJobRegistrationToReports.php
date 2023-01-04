<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddJobRegistrationToReports extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change(): void
    {
        $table = $this->table('reports');
        $table->addColumn('job_registration', 'boolean', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('new_exam', 'boolean', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('eval_council', 'boolean', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('date_job_registration', 'date', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('date_new_exam', 'date', [
            'default' => null,
            'null' => true,
        ]);
       
        $table->update();
    }
}
