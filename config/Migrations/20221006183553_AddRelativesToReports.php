<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddRelativesToReports extends AbstractMigration
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
        $table->addColumn('relativeLastname', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
		$table->addColumn('relativeRelationship', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->update();
    }
}
