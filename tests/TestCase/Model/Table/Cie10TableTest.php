<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\Cie10Table;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\Cie10Table Test Case
 */
class Cie10TableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\Cie10Table
     */
    protected $Cie10;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.Cie10',
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
        $config = $this->getTableLocator()->exists('Cie10') ? [] : ['className' => Cie10Table::class];
        $this->Cie10 = $this->getTableLocator()->get('Cie10', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Cie10);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\Cie10Table::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
