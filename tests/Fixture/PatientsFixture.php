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
                'lastname' => 'Lorem ipsum dolor sit amet',
                'address' => 'Lorem ipsum dolor sit a',
                'birthday' => 'Lorem ipsum dolor sit amet',
                'email' => 'Lorem ipsum dolor sit amet',
                'age' => 1,
                'document' => 'Lorem ipsum dolor sit amet',
                'job' => 'Lorem ipsum dolor sit amet',
                'created' => '2022-09-12 00:48:15',
                'modified' => '2022-09-12 00:48:15',
                'status' => 1,
            ],
        ];
        parent::init();
    }
}
