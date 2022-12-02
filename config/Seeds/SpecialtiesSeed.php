<?php
declare(strict_types=1);

use Migrations\AbstractSeed;

/**
 * TblUsers seed.
 */
class SpecialtiesSeed extends AbstractSeed
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
        $specialtiesString = 'Cardiología,Cirugía,Clinica Médica,Dermatología,Endocrinología,Gastroenterología,Ginecología y Obstetricia,Hematología,Infectología,Medicina Laboral,Medicina Legal,Nefrología,Neumonología,Neurología,Oftalmología,Oncología,Psiquiatría,Reumatología,Urología,Ortopedia,Traumatologia';
        $specialtiesArray = explode(',', $specialtiesString);
        $dataSpeciality = [];
        foreach ($specialtiesArray as $specialty) {
            $dataSpeciality[] = [
                'name' => $specialty,
            ];
        }
        $table = $this->table('specialties');
        $table->insert($dataSpeciality)->save();
    }
}
