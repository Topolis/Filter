<?php
/**
 * @package SecureInput
 * @subpackage UnitTest 
 */

require_once dirname(__FILE__).'/../src/IFilterType.php';
require_once dirname(__FILE__).'/../src/Types/PlainFilter.php';
require_once dirname(__FILE__).'/../src/Types/PlainExtFilter.php';

use Topolis\Filter\Types\PlainExtFilter;

/**
 * Plain Filter Test
 * 
 * @package SecureInput
 * @subpackage UnitTest
 * @author tbulla
 */
class PlainExtFilterTest extends PHPUnit_Framework_TestCase {

    /* @var \Topolis\Filter\IFilterType $filter */
    protected $Filter;
    
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp() {
        parent::setUp ();
        $this->Filter = new PlainExtFilter();
    }
    
    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown() {
        parent::tearDown ();
    }
    
    // ------------------------------------------------------------------------
    
    public function testConstruct() {
        $this->assertInstanceOf("\\Topolis\\Filter\\Types\\PlainExtFilter", $this->Filter);
    }
    
    public function testExecuteDefault() {
        
        $input  = "abcdefghijklmnobqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890-_.,!\"§$%&/()=?´`<*+#'>@|~:;\\";
        $output = "abcdefghijklmnobqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
        
        $this->assertEquals($output, $this->Filter->filter($input)); 
    }
    
    public function testExecuteConstants() {
        
        $input  = "abcdefghijklmnobqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890-äöüÄÖÜ-áéíóúàèìòùâêîôûÁÉÍÓÚÀÈÌÒÙÂÊÎÔÛ_.,!\"§$%&/()=?´`<*+#'>@|~:;\\";
        $basic  = "abcdefghijklmnobqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
        $german = "äöüÄÖÜ";
        $french = "áéíóúàèìòùâêîôûÁÉÍÓÚÀÈÌÒÙÂÊÎÔÛ";
        $all    = $basic.$german.$french;
        
        $this->Filter = new PlainExtFilter(array("characterset" => PlainExtFilter::BASIC));
        $this->assertEquals($basic, $this->Filter->filter($input), "Basic");

        $this->Filter = new PlainExtFilter(array("characterset" => PlainExtFilter::GERMAN));
        $this->assertEquals($german, $this->Filter->filter($input), "German"); 
        
        $this->Filter = new PlainExtFilter(array("characterset" => PlainExtFilter::FRENCH));
        $this->assertEquals($french, $this->Filter->filter($input), "French"); 

        $this->Filter = new PlainExtFilter(array("characterset" => PlainExtFilter::INTERNATIONAL));
        $this->assertEquals($all, $this->Filter->filter($input), "International"); 
    }    
}