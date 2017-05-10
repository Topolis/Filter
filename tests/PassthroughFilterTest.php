<?php
/**
 * @package SecureInput
 * @subpackage UnitTest 
 */

require_once dirname(__FILE__).'/../src/IFilterType.php';
require_once dirname(__FILE__).'/../src/Types/PassthroughFilter.php';

use Topolis\Filter\Types\PassthroughFilter;

/**
 * Passthrough Filter Test
 * 
 * @package SecureInput
 * @subpackage UnitTest
 * @author tbulla
 */
class PassthroughFilterTest extends PHPUnit_Framework_TestCase {

    /* @var \Topolis\Filter\IFilterType $filter */
    protected $Filter;
    
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp() {
        parent::setUp ();
        $this->Filter = new PassthroughFilter();
    }
    
    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown() {
        parent::tearDown ();
    }

    // ------------------------------------------------------------------------
    
    public function testConstruct() {
        $this->assertInstanceOf("\\Topolis\\Filter\\Types\\PassthroughFilter", $this->Filter);
    }
    
    public function testExecute() {
        $this->assertEquals("ABC", $this->Filter->filter("ABC")); 
    }
    
    public function testExecute_append() {
        $this->Filter = new PassthroughFilter(array("append" => "suffix"));
        $this->assertEquals("ABCsuffix", $this->Filter->filter("ABC")); 
    }
    
}