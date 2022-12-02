<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Component;

use App\Controller\Component\UpperComponent;
use Cake\Controller\ComponentRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\Component\UpperComponent Test Case
 */
class UpperComponentTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Controller\Component\UpperComponent
     */
    protected $Upper;

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $registry = new ComponentRegistry();
        $this->Upper = new UpperComponent($registry);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Upper);

        parent::tearDown();
    }
}
