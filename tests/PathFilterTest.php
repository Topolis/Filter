<?php
/**
 * @package SecureInput
 * @subpackage UnitTest 
 */

require_once dirname(__FILE__).'/../src/IFilterType.php';
require_once dirname(__FILE__).'/../src/Types/PlainFilter.php';
require_once dirname(__FILE__).'/../src/Types/PlainExtFilter.php';
require_once dirname(__FILE__).'/../src/Types/PathFilter.php';

use Topolis\Filter\Types\PathFilter;

/**
 * Plain Filter Test
 * 
 * @package SecureInput
 * @subpackage UnitTest
 * @author tbulla
 */
class PathFilterTest extends PHPUnit_Framework_TestCase {

    /* @var \Topolis\Filter\IFilterType $filter */
    protected $Filter;
    
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp() {
        parent::setUp ();
        $this->Filter = new PathFilter();
    }
    
    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown() {
        parent::tearDown ();
    }
    
    // ------------------------------------------------------------------------
    
    public function testConstruct() {
        $this->assertInstanceOf("\\Topolis\\Filter\\Types\\PathFilter", $this->Filter);
    }
    
    public function testExecuteDefault() {
        
        $input  = "test/eiöünsz&wei/drei.ext";
        $output = "test/einszwei/drei.ext";
        
        $this->assertEquals($output, $this->Filter->filter($input)); 
    }
}