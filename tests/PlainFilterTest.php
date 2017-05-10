<?php
/**
 * @package SecureInput
 * @subpackage UnitTest 
 */

require_once dirname(__FILE__).'/../src/IFilterType.php';
require_once dirname(__FILE__).'/../src/Types/PlainFilter.php';

use Topolis\Filter\Types\PlainFilter;

/**
 * Plain Filter Test
 * 
 * @package SecureInput
 * @subpackage UnitTest
 * @author tbulla
 */
class PlainFilterTest extends PHPUnit_Framework_TestCase {

    /* @var \Topolis\Filter\IFilterType $filter */
    protected $Filter;
    
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp() {
        parent::setUp ();
        $this->Filter = new PlainFilter();
    }
    
    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown() {
        parent::tearDown ();
    }

    // ------------------------------------------------------------------------
    
    public function testConstruct() {
        $this->assertInstanceOf("\\Topolis\\Filter\\Types\\PlainFilter", $this->Filter);
    }
    
    public function testExecute() {
        
        $input  = "abcdefghijklmnobqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXQZ1234567890-_.,!\"§$%&/()=?´`öäüÖÄÜß<*+#'>@|~:;\\";
        $output = "abcdefghijklmnobqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXQZ1234567890";
        
        $this->assertEquals($output, $this->Filter->filter($input)); 
    }
}