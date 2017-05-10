<?php
/**
 * @package SecureInput
 * @subpackage UnitTest 
 */

use Topolis\Filter\Filter;
use Topolis\Filter\Types\UrlFilter;

require_once dirname(__FILE__).'/../src/IFilterType.php';
require_once dirname(__FILE__).'/../src/Types/UrlFilter.php';

/**
 * Strip Filter Test
 * 
 * @package SecureInput
 * @subpackage UnitTest
 * @author tbulla
 */
class UrlFilterTest extends PHPUnit_Framework_TestCase {

    /* @var \Topolis\Filter\IFilterType $filter */
    protected $Filter;
    
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp() {
        parent::setUp ();
        $this->Filter = new UrlFilter();
    }
    
    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown() {
        parent::tearDown ();
    }

    // ------------------------------------------------------------------------
    
    public function testConstruct() {
        $this->assertInstanceOf("\\Topolis\\Filter\\Types\\UrlFilter", $this->Filter);
    }

    public function testExecute() {

        $this->assertNotEquals(Filter::ERR_INVALID, $this->Filter->validate("http://www.test.de"));
        $this->assertNotEquals(Filter::ERR_INVALID, $this->Filter->validate("https://www.test.de"));
        // $this->assertNotEquals(Filter::ERR_INVALID, $this->Filter->validate("//www.test.de"));
        $this->assertNotEquals(Filter::ERR_INVALID, $this->Filter->validate("http://test.de"));

        $this->assertEquals(Filter::ERR_INVALID, $this->Filter->validate("://www.test.de"));
        // $this->assertEquals(Filter::ERR_INVALID, $this->Filter->validate("nothing://www.test.de"));
        $this->assertEquals(Filter::ERR_INVALID, $this->Filter->validate("www.test.de"));
        $this->assertEquals(Filter::ERR_INVALID, $this->Filter->validate("www.test"));

    }
    
}