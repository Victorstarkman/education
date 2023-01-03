<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddRetirementToReports extends AbstractMigration
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
        $table->addColumn('retirement', 'boolean', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('reinstatement', 'date', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('interdiction', 'string', [
            'default' => null,
            'null' => true,
            'limit'=> 255,
        ]);
        $table->update();
    }
}
