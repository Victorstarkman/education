<?php
declare(strict_types=1);

use Migrations\AbstractSeed;

/**
 * TblUsers seed.
 */
class Cie10CorreoArgentinoSeed extends AbstractSeed
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
        $pathToJSON = ROOT . DS . 'config' . DS . 'Seeds' . DS . 'jsonToImport' . DS;
        $jsonFileCie10 = file_get_contents($pathToJSON . 'cie10Correo.json');
        $cie10Array = json_decode($jsonFileCie10, true);
        $dataCie10 = [];
        foreach ($cie10Array as $cie10) {
            $dataCie10[] = [
                'name' => ucwords($cie10['name']),
                'code' => $cie10['code'],
                'type' => 2,
            ];
        }
        $table = $this->table('cie10');
        $table->insert($dataCie10)->save();
    }
}
