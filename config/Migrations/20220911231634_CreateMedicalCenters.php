<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateMedicalCenters extends AbstractMigration
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
		$table = $this->table('medical_centers');
		$table->addColumn('id', 'integer', [
			'autoIncrement' => true,
			'default' => null,
			'limit' => 11,
			'null' => false,
		]);
		$table->addColumn('name', 'string', [
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
		$table->addPrimaryKey([
			'id',
		]);
		$table->create();
	}
}
