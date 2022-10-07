<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * PrivatedoctorsFixture
 */
class PrivatedoctorsFixture extends TestFixture
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
                'license' => 'Lorem ipsum dolor sit amet',
                'licenseNational' => 'Lorem ipsum dolor sit amet',
            ],
        ];
        parent::init();
    }
}
