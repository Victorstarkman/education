<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsersFixture
 */
class UsersFixture extends TestFixture
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
                'email' => 'Lorem ipsum dolor sit amet',
                'password' => 'Lorem ipsum dolor sit amet',
                'license' => 'Lorem ipsum dolor sit amet',
                'phone' => 'Lorem ipsum dolor sit amet',
                'document' => 'Lorem ipsum dolor sit amet',
                'area' => 'Lorem ipsum dolor sit amet',
                'group_id' => 1,
                'created' => '2022-09-11 23:20:51',
                'modified' => '2022-09-11 23:20:51',
                'deleted' => '2022-09-11 23:20:51',
            ],
        ];
        parent::init();
    }
}
