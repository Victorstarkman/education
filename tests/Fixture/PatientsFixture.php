<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * PatientsFixture
 */
class PatientsFixture extends TestFixture
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
                'medical_id' => 'Lorem ipsum dolor sit a',
                'address' => 'Lorem ipsum dolor sit a',
                'email' => 'Lorem ipsum dolor sit amet',
                'offitial_email' => 'Lorem ipsum dolor sit amet',
                'document' => 'Lorem ipsum dolor sit amet',
                'job' => 'Lorem ipsum dolor sit amet',
                'phone' => 'Lorem ipsum dolor sit amet',
                'created' => '2022-11-18 16:07:13',
                'modified' => '2022-11-18 16:07:13',
                'city_id' => 1,
                'seniority' => 1,
            ],
        ];
        parent::init();
    }
}
