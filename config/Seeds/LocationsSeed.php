<?php
declare(strict_types=1);

use Migrations\AbstractSeed;

/**
 * TblUsers seed.
 */
class LocationsSeed extends AbstractSeed
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
        $jsonFileStates = file_get_contents($pathToJSON . 'provincias.json');
        $states = json_decode($jsonFileStates, true);
        $dataState = [];
        $idState = 1;
        foreach ($states['provincias'] as $state) {
            $dataState[] = [
                'id' => $idState,
                'name' => ucwords($state['nombre']),
                'fullName' => ucwords($state['nombre_completo']),
                'oldID' => $state['id'],
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ];
            $idState++;
        }
        $table = $this->table('states');
        $table->insert($dataState)->save();

        $jsonFileCounties = file_get_contents($pathToJSON . 'municipios.json');

        $counties = json_decode($jsonFileCounties, true);
        $dataCounties = [];
        $idCounties = 1;

        foreach ($counties['municipios'] as $municipio) {
            $keyState = array_search($municipio['provincia']['id'], array_column($dataState, 'oldID'));
            $dataCounties[] = [
                'id' => $idCounties,
                'name' => ucwords($municipio['nombre']),
                'fullName' => ucwords($municipio['nombre_completo']),
                'state_id' => $dataState[$keyState]['id'],
                'oldID' => $municipio['id'],
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ];
            $idCounties++;
        }

        $table = $this->table('counties');
        $table->insert($dataCounties)->save();

        $jsonFileCities = file_get_contents($pathToJSON . 'localidades.json');

        $cities = json_decode($jsonFileCities, true);
        $dataCities = [];
        $idCities = 1;
        foreach ($cities['localidades'] as $localidad) {
            $keyMunicipio = array_search($localidad['municipio']['id'], array_column($dataCounties, 'oldID'));
            $dataCities[] = [
                'id' => $idCities,
                'name' => ucwords($localidad['nombre']),
                'county_id' => $dataCounties[$keyMunicipio]['id'],
                'oldID' => $municipio['id'],
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ];
            $idCities++;
        }

        $table = $this->table('cities');
        $table->insert($dataCities)->save();
    }
}
