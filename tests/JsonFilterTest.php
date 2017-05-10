<?php
/**
 * @package SecureInput
 * @subpackage UnitTest 
 */

use Topolis\Filter\Filter;
use Topolis\Filter\Types\JsonFilter;

require_once dirname(__FILE__).'/../src/IFilterType.php';
require_once dirname(__FILE__).'/../src/Filter.php';
require_once dirname(__FILE__).'/../src/Types/JsonFilter.php';


/**
 * Plain Filter Test
 * 
 * @package SecureInput
 * @subpackage UnitTest
 * @author tbulla
 */
class JsonFilterTest extends PHPUnit_Framework_TestCase {

    /* @var \Topolis\Filter\IFilterType $filter */
    protected $Filter;
    
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp() {
        parent::setUp ();
        $this->Filter = new JsonFilter();
    }
    
    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown() {
        parent::tearDown ();
    }

    // ------------------------------------------------------------------------
    
    public function testConstruct() {
        $this->assertInstanceOf("\\Topolis\\Filter\\Types\\JsonFilter", $this->Filter);
    }
    
    public function testExecute() {
        
        $data = array("a" => 1, "b" => "string", "c" => false, "d" => array("a",5,7,14));

        $this->Filter = new JsonFilter();
        $this->assertEquals(json_encode($data), $this->Filter->validate(json_encode($data)));

        $this->Filter = new JsonFilter();
        $this->assertEquals(Filter::ERR_INVALID, $this->Filter->validate('{Invalid}'));

        $this->Filter = new JsonFilter();
        $this->assertEquals($data, $this->Filter->filter(json_encode($data))); 
        
        $this->Filter = new JsonFilter(array("format" => JsonFilter::JSON));
        $this->assertEquals(json_encode($data), $this->Filter->filter(json_encode($data))); 
        
        $this->Filter = new JsonFilter(array("format" => JsonFilter::DECODED));
        $this->assertEquals($data, $this->Filter->filter(json_encode($data)));
        
        $this->Filter = new JsonFilter(array("format" => JsonFilter::SERIALIZED));
        $this->assertEquals(serialize($data), $this->Filter->filter(json_encode($data)));
    }
}