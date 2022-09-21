<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MedicalCentersTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MedicalCentersTable Test Case
 */
class MedicalCentersTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\MedicalCentersTable
     */
    protected $MedicalCenters;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.MedicalCenters',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('MedicalCenters') ? [] : ['className' => MedicalCentersTable::class];
        $this->MedicalCenters = $this->getTableLocator()->get('MedicalCenters', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->MedicalCenters);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\MedicalCentersTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
