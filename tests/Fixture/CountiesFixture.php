<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * CountiesFixture
 */
class CountiesFixture extends TestFixture
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
                'state_id' => 1,
                'name' => 'Lorem ipsum dolor sit amet',
                'fullName' => 'Lorem ipsum dolor sit amet',
                'oldID' => 'Lorem ipsum dolor sit amet',
                'created' => '2022-09-22 21:55:01',
                'modified' => '2022-09-22 21:55:01',
            ],
        ];
        parent::init();
    }
}
