<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AlterJobOnPatients extends AbstractMigration
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
		$table = $this->table('patients');
		$table->changeColumn('job', 'string',[
			'default' => null,
			'limit' => 200,
			'null' => true,
		]);
		$table->update();
	}
}