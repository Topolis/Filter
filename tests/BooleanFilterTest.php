<?php
/**
 * @package SecureInput
 * @subpackage UnitTest 
 */

require_once dirname(__FILE__).'/../src/IFilterType.php';
require_once dirname(__FILE__).'/../src/Types/BooleanFilter.php';

use Topolis\Filter\Types\BooleanFilter;

/**
 * Plain Filter Test
 * 
 * @package SecureInput
 * @subpackage UnitTest
 * @author tbulla
 */
class BooleanFilterTest extends PHPUnit_Framework_TestCase {

    /* @var \Topolis\Filter\IFilterType $filter */
    protected $Filter;
    
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp() {
        parent::setUp ();
        $this->Filter = new BooleanFilter();
    }
    
    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown() {
        parent::tearDown ();
    }
    
    /**
     * Constructs the test case.
     */
    public function __construct() {
    }
    
    // ------------------------------------------------------------------------
    
    public function testConstruct() {
        $this->assertInstanceOf("\\Topolis\\Filter\\Types\\BooleanFilter", $this->Filter);
    }
    
    public function testExecute() {
        
        $this->assertEquals(true, $this->Filter->filter(true));
        $this->assertEquals(true, $this->Filter->filter(1));
        $this->assertEquals(true, $this->Filter->filter("Hello"));

        $this->assertEquals(false, $this->Filter->filter(false));
        $this->assertEquals(false, $this->Filter->filter(0));
        $this->assertEquals(false, $this->Filter->filter(""));
    }
}