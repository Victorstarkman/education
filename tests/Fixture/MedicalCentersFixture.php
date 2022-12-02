<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * MedicalCentersFixture
 */
class MedicalCentersFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'zone' => 'Lorem ipsum dolor sit amet',
                'district' => 'Lorem ipsum dolor sit amet',
                'created' => '2022-11-18 15:56:58',
                'modified' => '2022-11-18 15:56:58',
            ],
        ];
        parent::init();
    }
}
