<?php
/**
 * @package SecureInput
 * @subpackage UnitTest 
 */

require_once dirname(__FILE__).'/../src/IFilterType.php';
require_once dirname(__FILE__).'/../src/Types/DateTimeFilter.php';

use Topolis\Filter\Types\DateTimeFilter;

/**
 * DateTime Filter Test
 * 
 * @package SecureInput
 * @subpackage UnitTest
 * @author tbulla
 */
class DateTimeFilterTest extends PHPUnit_Framework_TestCase {

    /* @var \Topolis\Filter\IFilterType $filter */
    protected $Filter;
    
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp() {
        parent::setUp ();
        $this->Filter = new DateTimeFilter();
    }
    
    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown() {
        parent::tearDown ();
    }

    // ------------------------------------------------------------------------
    
    public function testConstruct() {
        $this->assertInstanceOf("\\Topolis\\Filter\\Types\\DateTimeFilter", $this->Filter);
    }
    
    public function testExecute() {
        $this->assertEquals("2000-12-31 01:02:03", $this->Filter->filter("2000-12-31 01:02:03")); 
        $this->assertEquals("2000-12-31 01:02:03", $this->Filter->filter("31.12.2000 01:02:03"));
        $this->assertEquals("2000-12-31 00:00:00", $this->Filter->filter("2000-12-31"));
        $this->assertEquals("2000-12-31 00:00:00", $this->Filter->filter("31.12.2000"));
        
        $this->assertEquals(false, $this->Filter->filter("33.12.2000"));
        $this->assertEquals(false, $this->Filter->filter("31.13.2000"));
        $this->assertEquals(false, $this->Filter->filter("31.12.2000 01:65:00"));
        $this->assertEquals(false, $this->Filter->filter("31.12.2000 25:59:00"));
        $this->assertEquals(false, $this->Filter->filter("35.13.2000 25:63:95"));
    }
    
    public function testExecute_Format() {
        $this->Filter = new DateTimeFilter( array("format" => "Y-m-d"));
        $this->assertEquals("2000-12-31", $this->Filter->filter("2000-12-31 01:02:03"));

        $this->Filter = new DateTimeFilter( array("format" => "H:i:s"));
        $this->assertEquals("01:02:03", $this->Filter->filter("2000-12-31 01:02:03"));         
    }

    public function testExecute_Timezone() {
        $this->Filter = new DateTimeFilter( array("timezone" => "Europe/Berlin", "format" => "H:i:s O"));
        $this->assertEquals("03:02:03 +0100", $this->Filter->filter("2000-12-31 04:02:03 GMT+2"));
    }    
    
}