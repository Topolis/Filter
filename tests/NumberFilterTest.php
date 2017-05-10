<?php
/**
 * @package SecureInput
 * @subpackage UnitTest 
 */

use Topolis\Filter\Filter;
use Topolis\Filter\Types\NumberFilter;

require_once dirname(__FILE__).'/../src/IFilterType.php';
require_once dirname(__FILE__).'/../src/Filter.php';
require_once dirname(__FILE__).'/../src/Types/NumberFilter.php';

/**
 * Number Filter Test
 * 
 * @package SecureInput
 * @subpackage UnitTest
 * @author tbulla
 */
class NumberFilterTest extends PHPUnit_Framework_TestCase {

    /* @var \Topolis\Filter\IFilterType $filter */
    protected $Filter;
    
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp() {
        parent::setUp ();
        $this->Filter = new NumberFilter();
    }
    
    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown() {
        parent::tearDown ();
    }
    
    // ------------------------------------------------------------------------
    
    public function testConstruct() {
        $this->assertInstanceOf("\\Topolis\\Filter\\Types\\NumberFilter", $this->Filter);
    }
    
    public function testExecute() {
        $this->assertEquals(289137.82376, $this->Filter->filter("289137.82376")); 
        $this->assertEquals(2891, $this->Filter->filter("2891df37.82376"));
    }

    public function testExecuteZero() {
        
        $result = $this->Filter->filter("0");
        $this->assertEquals(0, $result); 
        $this->assertInternalType("numeric", $result);
    }
    
    /*
     */
    public function testExecute_Invalid() {
        $this->Filter = new NumberFilter(array("validate" => true));
        $this->assertEquals(Filter::ERR_INVALID, $this->Filter->filter("2891df37.82376"));
    }

    /*
     */
    public function testExecute_Adjust() {
        $this->Filter = new NumberFilter(array("min" => 50, "max" => 100));
        $this->assertEquals(76, $this->Filter->filter("76")); 
        $this->assertEquals(50, $this->Filter->filter("35"));
        $this->assertEquals(100, $this->Filter->filter("112"));
    }
    
    /*
     */
    public function testExecute_NoAdjust() {
        $this->Filter = new NumberFilter(array("min" => 50, "max" => 100, "adjust" => false));
        $this->assertEquals(Filter::ERR_INVALID, $this->Filter->filter("32"));
        $this->assertEquals(Filter::ERR_INVALID, $this->Filter->filter("112"));
    }    
    
    /*
     */
    public function testExecute_DecimalsRound() {
        $this->Filter = new NumberFilter(array("decimals" => 3, "round" => NumberFilter::SI_NUMBER_ROUND));
        $this->assertEquals(32.123, $this->Filter->filter("32.123"));
        $this->assertEquals(32.124, $this->Filter->filter("32.1236")); 
        $this->assertEquals(32.123, $this->Filter->filter("32.1234"));
    }      

    /*
     */
    public function testExecute_DecimalsFloor() {
        $this->Filter = new NumberFilter(array("decimals" => 3, "round" => NumberFilter::SI_NUMBER_FLOOR));
        $this->assertEquals(32.123, $this->Filter->filter("32.123"));
        $this->assertEquals(32.123, $this->Filter->filter("32.1236")); 
        $this->assertEquals(32.123, $this->Filter->filter("32.1234"));
    }

    /*
     */
    public function testExecute_DecimalsCeil() {
        $this->Filter = new NumberFilter(array("decimals" => 3, "round" => NumberFilter::SI_NUMBER_CEIL));
        $this->assertEquals(32.123, $this->Filter->filter("32.123"));
        $this->assertEquals(32.124, $this->Filter->filter("32.1236")); 
        $this->assertEquals(32.124, $this->Filter->filter("32.1234"));
    }    

    /*
     */
    public function testExecute_DecimalsInvalid() {
        $this->Filter = new NumberFilter(array("decimals" => 3, "round" => false));
        $this->assertEquals(Filter::ERR_INVALID, $this->Filter->filter("32.12345"));
        $this->assertEquals(32.123, $this->Filter->filter("32.123"));
    }      
    
}