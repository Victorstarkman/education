<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CompaniesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CompaniesTable Test Case
 */
class CompaniesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\CompaniesTable
     */
    protected $Companies;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.Companies',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Companies') ? [] : ['className' => CompaniesTable::class];
        $this->Companies = $this->getTableLocator()->get('Companies', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Companies);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\CompaniesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
