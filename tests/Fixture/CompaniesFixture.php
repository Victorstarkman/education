<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * CompaniesFixture
 */
class CompaniesFixture extends TestFixture
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
                'razon' => 'Lorem ipsum dolor sit amet',
                'name' => 'Lorem ipsum dolor sit amet',
                'cuit' => 'Lorem ipsum dolor sit amet',
                'no_dienst' => 1,
                'status' => 1,
                'created' => '2022-09-11 23:21:31',
                'modified' => '2022-09-11 23:21:31',
                'deleted' => '2022-09-11 23:21:31',
            ],
        ];
        parent::init();
    }
}
