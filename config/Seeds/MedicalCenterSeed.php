<?php
declare(strict_types=1);

use Migrations\AbstractSeed;

/**
 * MedicalCenter seed.
 */
class MedicalCenterSeed extends AbstractSeed
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
                'zone'=>null,
                'district'=>'NO DEFINIDO'

            ],
            [
                'zone'=>'2',
                'district'=>'BAHIA BLANCA'

            ],
            [
                'zone'=>'2',
                'district'=>'BAHIA BLANCA'

            ],
            [
                'zone'=>'2',
                'district'=>'BAHIA BLANCA'

            ],
            [
                'zone'=>'2',
                'district'=>'ALMIRANTE BROWN'

            ],
            [
                'zone'=>'2',
                'district'=>'QUILMES'

            ],
            [
                'zone'=>'2',
                'district'=>'ALDOLFO ALSINA'

            ],
            [
                'zone'=>'2',
                'district'=>'CNEL. SUAREZ'

            ],
            [
                'zone'=>'2',
                'district'=>'PATAGONES'

            ],
            [
                'zone'=>'2',
                'district'=>'CNEL. DORREGO'

            ],
            [
                'zone'=>'2',
                'district'=>'TRES ARROYOS'

            ],
            [
                'zone'=>'2',
                'district'=>'ESTEBAN ECHEVERRIA'

            ],
            [
                'zone'=>'2',
                'district'=>'BERAZATEGUI'

            ],
            [
                'zone'=>'3',
                'district'=>'TRENQUE LAUQUEN'

            ],
            [
                'zone'=>'3',
                'district'=>'NUEVE DE JULIO'

            ],
            [
                'zone'=>'3',
                'district'=>'JUNIN'

            ],
            [
                'zone'=>'3',
                'district'=>'MERLO'

            ],
            [
                'zone'=>'3',
                'district'=>'GRAL. VILLEGAS'

            ],
            [
                'zone'=>'3',
                'district'=>'CHIVILCOY'

            ],
            [
                'zone'=>'3',
                'district'=>'PEHUAJO'

            ],
            [
                'zone'=>'3',
                'district'=>'LINCOLN'

            ],
            [
                'zone'=>'3',
                'district'=>'MORENO'

            ],
            [
                'zone'=>'3',
                'district'=>'MALVINAS ARGENTINAS'

            ],
            [
                'zone'=>'4',
                'district'=>'PERGAMINO'

            ],
            [
                'zone'=>'4',
                'district'=>'LA MATANZA'

            ],
            [
                'zone'=>'4',
                'district'=>'AVELLANEDA'

            ],
            [
                'zone'=>'4',
                'district'=>'NECOCHEA'

            ],
            [
                'zone'=>'4',
                'district'=>'TANDIL'
            ],
            [
                'zone'=>'4',
                'district'=>'DOLORES'
            ],
            [
                'zone'=>'4',
                'district'=>'GRAL. MADARIAGA'

            ],
            [
                'zone'=>'4',
                'district'=>'PINAMAR'

            ],
            [
                'zone'=>'5',
                'district'=>'OLAVARRIA'

            ],
            [
                'zone'=>'5',
                'district'=>'SALADILLO'

            ],
            [
                'zone'=>'5',
                'district'=>'LA PLATA'

            ],
            [
                'zone'=>'5',
                'district'=>'BOLIVAR'

            ],
            [
                'zone'=>'5',
                'district'=>'ROQUE PEREZ'

            ],
            [
                'zone'=>'5',
                'district'=>'VEINTICINCO DE MAYO'

            ],
            [
                'zone'=>'5',
                'district'=>'CHASCOMUS'

            ],
            [
                'zone'=>'5',
                'district'=>'SAN MIGUEL DEL MONTE'

            ],
            [
                'zone'=>'5',
                'district'=>'RAUCH'

            ],
            [
                'zone'=>'5',
                'district'=>'LOMAS DE ZAMORA'

            ],
            [
                'zone'=>'5',
                'district'=>'LANUS'

            ]


        ];
        
        $table = $this->table('medical_centers');
        $table->insert($data)->save();
    }
}
