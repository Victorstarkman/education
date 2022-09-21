<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateFiles extends AbstractMigration
{
	public $autoId = false;

	/**
	 * Change Method.
	 *
	 * More information on this method is available here:
	 * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
	 * @return void
	 */
	public function change()
	{
		$table = $this->table('files');
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
		$table->addColumn('type', 'string', [
			'default' => null,
			'limit' => 120,
			'null' => false,
		]);
		$table->addColumn('report_id', 'integer', [
			'default' => 1,
			'limit' => 5,
			'null' => false,
		]);
		$table->addPrimaryKey([
			'id',
		]);
		$table->create();
	}
}
