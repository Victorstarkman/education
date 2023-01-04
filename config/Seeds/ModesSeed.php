<?php
declare(strict_types=1);

use Migrations\AbstractSeed;

/**
 * TblUsers seed.
 */
class ModesSeed extends AbstractSeed
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
                'name' => 'Auditorías',
            ],
            [
                'name' => 'Visita Médica Domiciliaria',
            ],
            [
                'name' => 'Juntas Especializadas',
            ],
            [
                'name' => 'Juntas Médicas',
            ],
            [
                'name' => 'Auditorías Virtuales',
            ],
            
        ];

        $table = $this->table('modes');
        $table->insert($data)->save();
    }
}
