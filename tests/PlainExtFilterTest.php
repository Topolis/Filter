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

    public function testRegexpChars() {

        // Dash
        $this->Filter = new PlainExtFilter(array("characterset" => 0, "characters" => "12345-"));
        $this->assertEquals("34-21-5", $this->Filter->filter("3b4-2a1-5"), "Dash");

        // Dot
        $this->Filter = new PlainExtFilter(array("characterset" => 0, "characters" => "123456."));
        $this->assertEquals("34.21.5", $this->Filter->filter("3b4.2a1.5"), "Dot");

        // Space
        $this->Filter = new PlainExtFilter(array("characterset" => 0, "characters" => "123456 "));
        $this->assertEquals("34 21 5", $this->Filter->filter("3b4 2a1 5"), "Space");

        // Not a char range
        $this->Filter = new PlainExtFilter(array("characterset" => 0, "characters" => "12-56"));
        $this->assertEquals("1-25-6", $this->Filter->filter("1-2345-6"), "Char range");

        // Not a non-space
        $this->Filter = new PlainExtFilter(array("characterset" => 0, "characters" => "123\\S456"));
        $this->assertEquals("1S23456", $this->Filter->filter("1Sa2b,34-56"), "Non-space");
    }
}