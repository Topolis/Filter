<?php
/**
 * @package SecureInput
 * @subpackage UnitTest 
 */

use Topolis\Filter\Types\StripFilter;

require_once dirname(__FILE__).'/../src/IFilterType.php';
require_once dirname(__FILE__).'/../src/Types/StripFilter.php';

/**
 * Strip Filter Test
 * 
 * @package SecureInput
 * @subpackage UnitTest
 * @author tbulla
 */
class StripFilterTest extends PHPUnit_Framework_TestCase {

    /* @var \Topolis\Filter\IFilterType $filter */
    protected $Filter;
    
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp() {
        parent::setUp ();
        $this->Filter = new StripFilter();
    }
    
    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown() {
        parent::tearDown ();
    }

    // ------------------------------------------------------------------------
    
    public function testConstruct() {
        $this->assertInstanceOf("\\Topolis\\Filter\\Types\\StripFilter", $this->Filter);
    }
    
    public function testExecute() {
        $this->assertEquals("ABC", $this->Filter->filter("A<a>BC")); 
        $this->assertEquals("ABC", $this->Filter->filter("AB<b>C"));
        $this->assertEquals("ABC", $this->Filter->filter("ABC<c>"));
        $this->assertEquals("ABC", $this->Filter->filter("<d>ABC</d>"));
    }
    
    public function testExecute_allowed() {
        $this->Filter = new StripFilter(array("allowable_tags" => "<a><b><b/><a/>"));
        $this->assertEquals("ABC",                $this->Filter->filter("ABC"));
        $this->assertEquals("A<a>BC",             $this->Filter->filter("A<a>B<c>C")); 
        $this->assertEquals("AB<b/>C",            $this->Filter->filter("A<c/>B<b/>C"));
        $this->assertEquals("A<a>B</a>C",         $this->Filter->filter("A<a><c>B</a>C")); 
        $this->assertEquals("A<a>B<b>C</b></a>",  $this->Filter->filter("A<a>B<b><c/>C</b></a>"));
    }
    
}