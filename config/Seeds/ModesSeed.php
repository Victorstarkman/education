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
                'name' => 'Auditoria Médica Ambulatoria',
            ],
            [
                'name' => 'Auditoria Médica Domiciliaria',
            ],
            [
                'name' => 'Atenciones Médicas Especializadas',
            ],
            [
                'name' => 'Juntas Médicas',
            ],
            [
                'name' => 'Telemedicina Auditorias',
            ],
            [
                'name' => 'Telemedicina Juntas Médicas',
            ],
            [
                'name' => 'Telemedicina Auditores Especializada',
            ],

        ];

        $table = $this->table('modes');
        $table->insert($data)->save();
    }
}
