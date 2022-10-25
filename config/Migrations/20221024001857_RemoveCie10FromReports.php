<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class RemoveCie10FromReports extends AbstractMigration
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
        $table->removeColumn('cie10');
        $table->update();
    }
}
