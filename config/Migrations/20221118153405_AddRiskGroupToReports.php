<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddRiskGroupToReports extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('reports');
        $table->addColumn('risk_group', 'string', [
            'default' => null,
            'limit' => 200,
            'null' => false,
        ]);
        $table->update();
    }
}
