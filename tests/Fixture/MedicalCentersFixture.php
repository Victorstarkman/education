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
                'name' => 'Lorem ipsum dolor sit amet',
                'created' => '2022-09-11 23:22:24',
                'modified' => '2022-09-11 23:22:24',
            ],
        ];
        parent::init();
    }
}
