<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * CitiesFixture
 */
class CitiesFixture extends TestFixture
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
                'county_id' => 1,
                'name' => 'Lorem ipsum dolor sit amet',
                'oldID' => 'Lorem ipsum dolor sit amet',
                'created' => '2022-09-22 21:55:14',
                'modified' => '2022-09-22 21:55:14',
            ],
        ];
        parent::init();
    }
}
