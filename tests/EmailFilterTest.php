<?php
/**
 * @package SecureInput
 * @subpackage UnitTest 
 */

require_once dirname(__FILE__).'/../src/IFilterType.php';
require_once dirname(__FILE__).'/../src/Types/EmailFilter.php';

use Topolis\Filter\Types\EmailFilter;

/**
 * Email Filter Test
 * 
 * @package SecureInput
 * @subpackage UnitTest
 * @author tbulla
 */
class EmailFilterTest extends PHPUnit_Framework_TestCase {

    /* @var \Topolis\Filter\IFilterType $filter */
    protected $Filter;
    
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp() {
        parent::setUp ();
        $this->Filter = new EmailFilter();
    }
    
    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown() {
        parent::tearDown ();
    }

    // ------------------------------------------------------------------------
    
    public function testConstruct() {
        $this->assertInstanceOf("\\Topolis\\Filter\\Types\\EmailFilter", $this->Filter);
    }
    
    public function testExecute_Ok() {
        
        $input  = array("test@test.de",
                        "max+mustermann@test.test2.info");
        foreach($input as $test)
            $this->assertEquals($test, $this->Filter->filter($test)); 
    }
    
    public function testExecute_Fail() {
        
        $input  = array("töst@test.de",
                        "max mustermann@test.test2.info",
                        "test.test.de");
        foreach($input as $test)
            $this->assertEquals(null, $this->Filter->filter($test)); 
    }    
}