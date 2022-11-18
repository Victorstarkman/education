<?php
declare(strict_types=1);

use Migrations\AbstractSeed;

/**
 * TblUsers seed.
 */
class CompaniesSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'razon' => 'Razon 1',
                'name' => 'Ministerio de Educacion',
                'cuit' => '2011111112',
                'no_dienst' => 1,
                'status' => 1,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
                'deleted' => null,
            ],
        
        ];

        $table = $this->table('companies');
        $table->insert($data)->save();
    }
}
