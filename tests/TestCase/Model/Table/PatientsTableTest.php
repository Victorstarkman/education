<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PatientsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PatientsTable Test Case
 */
class PatientsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\PatientsTable
     */
    protected $Patients;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.Patients',
        'app.Reports',
        'app.ReportsWithoutCheck',
        'app.Companies',
        'app.Cities',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Patients') ? [] : ['className' => PatientsTable::class];
        $this->Patients = $this->getTableLocator()->get('Patients', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Patients);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\PatientsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
