<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ModesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ModesTable Test Case
 */
class ModesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ModesTable
     */
    protected $Modes;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.Modes',
        'app.Reports',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Modes') ? [] : ['className' => ModesTable::class];
        $this->Modes = $this->getTableLocator()->get('Modes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Modes);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\ModesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
