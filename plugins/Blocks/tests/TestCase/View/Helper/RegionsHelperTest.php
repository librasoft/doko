<?php
namespace Blocks\Test\TestCase\View\Helper;

use Blocks\View\Helper\RegionsHelper;
use Cake\TestSuite\TestCase;
use Cake\View\View;

/**
 * Blocks\View\Helper\RegionsHelper Test Case
 */
class RegionsHelperTest extends TestCase
{

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $view = new View();
        $this->Regions = new RegionsHelper($view);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Regions);

        parent::tearDown();
    }

    /**
     * Test initial setup
     *
     * @return void
     */
    public function testInitialization()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
