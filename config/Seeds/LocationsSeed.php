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
        $statesTable = \Cake\ORM\TableRegistry::getTableLocator()->get('States');
        foreach ($states['provincias'] as $state) {
            $stateEntity = $statesTable->find()->where(['name' => ucwords($state['nombre'])])->where(['oldID' => $state['id']])->first();
            if (empty($stateEntity)) {
                $dataState[] = [
                    'name' => ucwords($state['nombre']),
                    'fullName' => ucwords($state['nombre_completo']),
                    'oldID' => $state['id'],
                    'created' => date('Y-m-d H:i:s'),
                    'modified' => date('Y-m-d H:i:s'),
                ];
            }
        }

        if (!empty($dataState)) {
            $table = $this->table('states');
            $table->insert($dataState)->save();
        }
        $dataState = $statesTable->find()->toArray();
        $jsonFileCounties = file_get_contents($pathToJSON . 'municipios.json');

        $counties = json_decode($jsonFileCounties, true);
        $dataCounties = [];
        $countiesTable = \Cake\ORM\TableRegistry::getTableLocator()->get('counties');
        foreach ($counties['municipios'] as $municipio) {
            $countyEntity = $countiesTable->find()->where(['name' => ucwords($municipio['nombre'])])->where(['oldID' => $municipio['id']])->first();
            if (empty($countyEntity)) {
                $keyState = array_search($municipio['provincia']['id'], array_column($dataState, 'oldID'));
                if ($keyState === false) {
                } else {
                    $dataCounties[] = [
                        'name' => ucwords($municipio['nombre']),
                        'fullName' => ucwords($municipio['nombre_completo']),
                        'state_id' => $dataState[$keyState]['id'],
                        'oldID' => $municipio['id'],
                        'created' => date('Y-m-d H:i:s'),
                        'modified' => date('Y-m-d H:i:s'),
                    ];
                }
            }
        }

        if (!empty($dataCounties)) {
            $table = $this->table('counties');
            $table->insert($dataCounties)->save();
        }
        $dataCounties = $countiesTable->find()->toArray();

        $jsonFileCities = file_get_contents($pathToJSON . 'localidades.json');

        $cities = json_decode($jsonFileCities, true);
        $dataCities = [];
        $citiesTable = \Cake\ORM\TableRegistry::getTableLocator()->get('cities');
        foreach ($cities['localidades'] as $localidad) {
            $cityEntity = $citiesTable->find()->where(['name' => ucwords($localidad['nombre'])])->where(['oldID' => $localidad['id']])->first();
            if (empty($cityEntity)) {
                $keyMunicipio = array_search($localidad['municipio']['id'], array_column($dataCounties, 'oldID'));
                if ($keyMunicipio === false) {
                    if (!is_null($localidad['provincia']['id'])) {
                        $stateEntitySearching = $statesTable->find()->where(['oldID' => $localidad['provincia']['id']])->first();
                        if (empty($stateEntitySearching)) {
                            $newState = [
                                'name' => ucwords($localidad['provincia']['nombre']),
                                'fullName' => ucwords($localidad['provincia']['nombre']),
                                'oldID' => $localidad['provincia']['id'],
                                'created' => date('Y-m-d H:i:s'),
                                'modified' => date('Y-m-d H:i:s'),
                            ];
                            $newStateEntity = $statesTable->newEmptyEntity();
                            $newStateEntity = $statesTable->patchEntity($newStateEntity, $newState);
                            $statesTable->save($newStateEntity);
                            $stateEntitySearching = $newStateEntity;
                        }

                        if (!is_null($localidad['municipio']['id']) || !is_null($localidad['departamento']['id'])) {
                            $idToFind = !is_null($localidad['municipio']['id']) ? $localidad['municipio']['id'] : $localidad['departamento']['id'];
                            $name = !is_null($localidad['municipio']['id']) ? $localidad['municipio']['nombre'] : $localidad['departamento']['nombre'];
                            $countyEntitySearching = $countiesTable->find()->where(['oldID' => $idToFind])->first();
                            if (empty($countyEntitySearching)) {
                                $newDataCounties = [
                                    'name' => ucwords($name),
                                    'fullName' => ucwords($name),
                                    'state_id' => $stateEntitySearching->id,
                                    'oldID' => $idToFind,
                                    'created' => date('Y-m-d H:i:s'),
                                    'modified' => date('Y-m-d H:i:s'),
                                ];
                                $newCountyEntity = $countiesTable->newEmptyEntity();
                                $newCountyEntity = $countiesTable->patchEntity($newCountyEntity, $newDataCounties);
                                $countiesTable->save($newCountyEntity);
                                $countyEntitySearching = $newCountyEntity;
                                $dataCounties = $countiesTable->find()->toArray();
                            }
	                        $keyMunicipio = array_search($idToFind, array_column($dataCounties, 'oldID'));
                            $dataCities[] = [
                                'name' => ucwords($localidad['nombre']),
                                'county_id' => $dataCounties[$keyMunicipio]['id'],
                                'oldID' => $localidad['id'],
                                'created' => date('Y-m-d H:i:s'),
                                'modified' => date('Y-m-d H:i:s'),
                            ];
                        }
                    }
                } else {
                    $dataCities[] = [
                        'name' => ucwords($localidad['nombre']),
                        'county_id' => $dataCounties[$keyMunicipio]['id'],
                        'oldID' => $localidad['id'],
                        'created' => date('Y-m-d H:i:s'),
                        'modified' => date('Y-m-d H:i:s'),
                    ];
                }
            }
        }

        if (!empty($dataCities)) {
            $table = $this->table('cities');
            $table->insert($dataCities)->save();
        }
    }
}
