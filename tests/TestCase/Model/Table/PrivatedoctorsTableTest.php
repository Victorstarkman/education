<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PrivatedoctorsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PrivatedoctorsTable Test Case
 */
class PrivatedoctorsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\PrivatedoctorsTable
     */
    protected $Privatedoctors;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.Privatedoctors',
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
        $config = $this->getTableLocator()->exists('Privatedoctors') ? [] : ['className' => PrivatedoctorsTable::class];
        $this->Privatedoctors = $this->getTableLocator()->get('Privatedoctors', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Privatedoctors);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\PrivatedoctorsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
