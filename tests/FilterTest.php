<?php
/**
 * @package SecureInput
 * @subpackage UnitTest 
 */

use Topolis\Filter\Filter;

require_once dirname(__FILE__).'/../src/IFilterType.php';
require_once dirname(__FILE__).'/../src/Filter.php';

/**
 * Filter Test
 * 
 * @package Filter
 * @subpackage UnitTest
 * @author tbulla
 */
class FilterTest extends PHPUnit_Framework_TestCase {
    
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp() {
        parent::setUp ();
    }
    
    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown() {
        $Filter = new Filter();
        
        parent::tearDown ();
    }
    
    // ------------------------------------------------------------------------

    /**
     * Test Filter->validate
     */
    public function testValidate_Ok() {
        $filters = "Passthrough";
        $options = array();
        $this->assertEquals("START", Filter::validate("START", $filters, $options));
    }

    /**
     * Test Filter->validate
     */
    public function testValidate_Fail() {
        $filters = "Passthrough";
        $options = array("append" => "1");
        $this->assertEquals(false, Filter::validate("START", $filters, $options));
    }

    /**
     * Test Filter->filter
     */
    public function testFilter() {
        $filters = "Passthrough";
        $options = array();
        $this->assertEquals("START", Filter::validate("START", $filters, $options));
    }

    /**
     * Test Filter->filter
     */
    public function testFilter_Types() {
        $filters = "Passthrough";
        $options = array("append" => "1");

        // Single
        $inputSingle = "A";
        $ouputSingle = "A1";

        // Array
        $inputArray = array("A", "B", "C");
        $ouputArray = array("A1", "B1", "C1");

        // Tree
        $inputTree = array("A", "B", "C", array("D", "E"));
        $ouputTree = array("A1", "B1", "C1", array("D1", "E1"));

        $this->assertEquals($ouputSingle, Filter::filter($inputSingle, $filters, $options));
        $this->assertEquals($ouputArray, Filter::filter($inputArray, $filters, $options));
        $this->assertEquals($ouputTree, Filter::filter($inputTree, $filters, $options));

        $this->assertEquals($ouputSingle, Filter::filter($inputSingle, $filters, $options + ["type" => Filter::TYPE_SINGLE]));
        $this->assertEquals(false, Filter::filter($inputArray, $filters, $options + ["type" => Filter::TYPE_SINGLE]));
        $this->assertEquals(false, Filter::filter($inputTree, $filters, $options + ["type" => Filter::TYPE_SINGLE]));

        $this->assertEquals(false, Filter::filter($inputSingle, $filters, $options + ["type" => Filter::TYPE_ARRAY]));
        $this->assertEquals($ouputArray, Filter::filter($inputArray, $filters, $options + ["type" => Filter::TYPE_ARRAY]));
        $this->assertEquals(false, Filter::filter($inputTree, $filters, $options + ["type" => Filter::TYPE_ARRAY]));

        $this->assertEquals(false, Filter::filter($inputSingle, $filters, $options + ["type" => Filter::TYPE_TREE]));
        $this->assertEquals($ouputArray, Filter::filter($inputArray, $filters, $options + ["type" => Filter::TYPE_TREE])); // Note: An aray is technically also a small tree
        $this->assertEquals($ouputTree, Filter::filter($inputTree, $filters, $options + ["type" => Filter::TYPE_TREE]));

        $this->assertEquals($ouputSingle, Filter::filter($inputSingle, $filters, $options + ["type" => Filter::TYPE_ANY]));
        $this->assertEquals($ouputArray, Filter::filter($inputArray, $filters, $options + ["type" => Filter::TYPE_ANY]));
        $this->assertEquals($ouputTree, Filter::filter($inputTree, $filters, $options + ["type" => Filter::TYPE_ANY]));
    }

    /**
     * Test Filter->filter
     */
    public function testValidate_Array() {
        $filters = "Plain";
        $options = array();
        
        $valid   = array("A", "B", "C", array("D", "E"));
        $invalid = array("A", "B", "C", array("D!", "E"));
        
        $this->assertEquals($valid, Filter::validate($valid, $filters, $options));
        $this->assertEquals(false,  Filter::validate($invalid, $filters, $options));
    }

    /**
     * Test Filter->filter
     */
    public function testFilterQueue() {
        $filters = ["Passthrough", "Passthrough", "Passthrough"];
        $options = [
            ["append" => "A1"],
            ["append" => "B2"],
            ["append" => "C3"],
        ];

        $valid     = ["X", "Y", "Z", ["V", "W"]];
        $expected  = ["XA1B2C3", "YA1B2C3", "ZA1B2C3", ["VA1B2C3", "WA1B2C3"]];

        $this->assertEquals($expected, Filter::filter($valid, $filters, $options));
    }
}