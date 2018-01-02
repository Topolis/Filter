<?php
/**
 * @package SecureInput
 * @subpackage UnitTest 
 */

use Topolis\Filter\Filter;
use Topolis\Filter\Types\UrlFilter;

require_once dirname(__FILE__).'/../src/IFilterType.php';
require_once dirname(__FILE__).'/../src/Types/UrlFilter.php';

/**
 * Strip Filter Test
 * 
 * @package SecureInput
 * @subpackage UnitTest
 * @author tbulla
 */
class UrlFilterTest extends PHPUnit_Framework_TestCase {

    // ------------------------------------------------------------------------
    
    public function testConstruct() {
        $filter = new UrlFilter();
        $this->assertInstanceOf("\\Topolis\\Filter\\Types\\UrlFilter", $filter);
    }

    public function testExecute() {

        $filter = new UrlFilter();

        $this->assertNotEquals(Filter::ERR_INVALID, $filter->validate("http://www.test.de"));
        $this->assertNotEquals(Filter::ERR_INVALID, $filter->validate("https://www.test.de"));
        $this->assertNotEquals(Filter::ERR_INVALID, $filter->validate("://www.test.de"));           // Note: Old version of this relied on filter_var(FILTER_VALIDATE_URL) which failed here
        $this->assertNotEquals(Filter::ERR_INVALID, $filter->validate("//www.test.de"));
        $this->assertNotEquals(Filter::ERR_INVALID, $filter->validate("http://test.de"));
        $this->assertNotEquals(Filter::ERR_INVALID, $filter->validate("www.test.de"));
        $this->assertNotEquals(Filter::ERR_INVALID, $filter->validate("www.test"));                 // Note: Old version of this relied on filter_var(FILTER_VALIDATE_URL) which tested top-level domains somehow

        $this->assertNotEquals(Filter::ERR_INVALID, $filter->validate("http://www.test.de/hello/world"));
        $this->assertNotEquals(Filter::ERR_INVALID, $filter->validate("http://www.test.de/hello/world?some=thing#else"));
        $this->assertNotEquals(Filter::ERR_INVALID, $filter->validate("http://www.test.de/hello/world#else"));
        $this->assertNotEquals(Filter::ERR_INVALID, $filter->validate("http://www.test.de/hello/world.htmld#else"));

        $this->assertEquals(Filter::ERR_INVALID, $filter->validate("///hello/world"));
        $this->assertEquals(Filter::ERR_INVALID, $filter->validate("nothing://www.test.de/hello/world"));
    }

    public function testRequired() {

        // scheme
        $filter = new UrlFilter(["require" => ["scheme"]]);
        $this->assertNotEquals(Filter::ERR_INVALID, $filter->validate("http://www.test.de/hello/world"));
        $this->assertEquals(Filter::ERR_INVALID, $filter->validate("//www.test.de/hello/world"));

        // host
        $filter = new UrlFilter(["require" => ["host"]]);
        $this->assertNotEquals(Filter::ERR_INVALID, $filter->validate("http://www.test.de/hello/world"));
        $this->assertEquals(Filter::ERR_INVALID, $filter->validate("/hello/world"));

        // port
        $filter = new UrlFilter(["require" => ["port"]]);
        $this->assertNotEquals(Filter::ERR_INVALID, $filter->validate("http://www.test.de:8081/hello/world"));
        $this->assertEquals(Filter::ERR_INVALID, $filter->validate("http://www.test.de/hello/world"));

        // user
        $filter = new UrlFilter(["require" => ["user"]]);
        $this->assertNotEquals(Filter::ERR_INVALID, $filter->validate("http://testuser@www.test.de/hello/world"));
        $this->assertEquals(Filter::ERR_INVALID, $filter->validate("http://www.test.de/hello/world"));

        // pass
        $filter = new UrlFilter(["require" => ["pass"]]);
        $this->assertNotEquals(Filter::ERR_INVALID, $filter->validate("http://testuser:testpass@www.test.de/hello/world"));
        $this->assertEquals(Filter::ERR_INVALID, $filter->validate("http://testuser@www.test.de/hello/world"));

        // root
        $filter = new UrlFilter(["require" => ["root"]]);
        $this->assertNotEquals(Filter::ERR_INVALID, $filter->validate("/hello/world"));
        $this->assertEquals(Filter::ERR_INVALID, $filter->validate("hello/world"));

        // path
        $filter = new UrlFilter(["require" => ["path"]]);
        $this->assertNotEquals(Filter::ERR_INVALID, $filter->validate("http://www.test.de/hello/world"));
        $this->assertEquals(Filter::ERR_INVALID, $filter->validate("http://www.test.de"));

        // query
        $filter = new UrlFilter(["require" => ["query"]]);
        $this->assertNotEquals(Filter::ERR_INVALID, $filter->validate("http://www.test.de/hello/world?hello=world"));
        $this->assertEquals(Filter::ERR_INVALID, $filter->validate("http://www.test.de/hello/world"));

        // fragment
        $filter = new UrlFilter(["require" => ["fragment"]]);
        $this->assertNotEquals(Filter::ERR_INVALID, $filter->validate("http://www.test.de/hello/world#somewhere"));
        $this->assertEquals(Filter::ERR_INVALID, $filter->validate("http://www.test.de/hello/world"));

        // multiple requirements
        $filter = new UrlFilter(["require" => ["host", "path", "fragment"]]);
        $this->assertNotEquals(Filter::ERR_INVALID, $filter->validate("http://www.test.de/hello/world?some=where#else"));
        $this->assertEquals(Filter::ERR_INVALID, $filter->validate("/hello/world?some=where#else"));
        $this->assertEquals(Filter::ERR_INVALID, $filter->validate("http://www.test.de?some=where#else"));
        $this->assertEquals(Filter::ERR_INVALID, $filter->validate("http://www.test.de/hello/world?some=where"));

    }

    public function testDisallow() {

        // scheme
        $filter = new UrlFilter(["disallow" => ["scheme"]]);
        $this->assertNotEquals(Filter::ERR_INVALID, $filter->validate("//www.test.de/hello/world"));
        $this->assertEquals(Filter::ERR_INVALID, $filter->validate("http://www.test.de/hello/world"));

        // host
        $filter = new UrlFilter(["disallow" => ["host"]]);
        $this->assertNotEquals(Filter::ERR_INVALID, $filter->validate("/hello/world"));
        $this->assertEquals(Filter::ERR_INVALID, $filter->validate("http://www.test.de/hello/world"));

        // port
        $filter = new UrlFilter(["disallow" => ["port"]]);
        $this->assertNotEquals(Filter::ERR_INVALID, $filter->validate("http://www.test.de/hello/world"));
        $this->assertEquals(Filter::ERR_INVALID, $filter->validate("http://www.test.de:8081/hello/world"));

        // user
        $filter = new UrlFilter(["disallow" => ["user"]]);
        $this->assertNotEquals(Filter::ERR_INVALID, $filter->validate("http://www.test.de/hello/world"));
        $this->assertEquals(Filter::ERR_INVALID, $filter->validate("http://testuser@www.test.de/hello/world"));

        // pass
        $filter = new UrlFilter(["disallow" => ["pass"]]);
        $this->assertNotEquals(Filter::ERR_INVALID, $filter->validate("http://testuser@www.test.de/hello/world"));
        $this->assertEquals(Filter::ERR_INVALID, $filter->validate("http://testuser:testpass@www.test.de/hello/world"));

        // root
        $filter = new UrlFilter(["disallow" => ["root"]]);
        $this->assertNotEquals(Filter::ERR_INVALID, $filter->validate("hello/world"));
        $this->assertEquals(Filter::ERR_INVALID, $filter->validate("/hello/world"));

        // path
        $filter = new UrlFilter(["disallow" => ["path"]]);
        $this->assertNotEquals(Filter::ERR_INVALID, $filter->validate("http://www.test.de"));
        $this->assertEquals(Filter::ERR_INVALID, $filter->validate("http://www.test.de/hello/world"));

        // query
        $filter = new UrlFilter(["disallow" => ["query"]]);
        $this->assertNotEquals(Filter::ERR_INVALID, $filter->validate("http://www.test.de/hello/world"));
        $this->assertEquals(Filter::ERR_INVALID, $filter->validate("http://www.test.de/hello/world?hello=world"));

        // fragment
        $filter = new UrlFilter(["disallow" => ["fragment"]]);
        $this->assertNotEquals(Filter::ERR_INVALID, $filter->validate("http://www.test.de/hello/world"));
        $this->assertEquals(Filter::ERR_INVALID, $filter->validate("http://www.test.de/hello/world#somewhere"));

        // multiple requirements
        $filter = new UrlFilter(["disallow" => ["host", "path", "fragment"]]);
        $this->assertNotEquals(Filter::ERR_INVALID, $filter->validate("http:?some=where"));
        $this->assertEquals(Filter::ERR_INVALID, $filter->validate("http://www.test.de?some=where"));
        $this->assertEquals(Filter::ERR_INVALID, $filter->validate("/hello/world?some=where"));
        $this->assertEquals(Filter::ERR_INVALID, $filter->validate("http:?some=where#else"));

    }

    public function testSchemes() {
        $filter = new UrlFilter(["schemes" => ["test1", "test2"]]);
        $this->assertNotEquals(Filter::ERR_INVALID, $filter->validate("//www.test.de"));
        $this->assertNotEquals(Filter::ERR_INVALID, $filter->validate("test1://www.test.de"));
        $this->assertNotEquals(Filter::ERR_INVALID, $filter->validate("test2://www.test.de"));
        $this->assertEquals(Filter::ERR_INVALID, $filter->validate("test3://www.test.de"));
    }

    public function testTypes(){
        // absolute
        $filter = new UrlFilter(["type" => "absolute"]);
        $this->assertEquals(Filter::ERR_INVALID, $filter->validate("hello/world"));
        $this->assertEquals(Filter::ERR_INVALID, $filter->validate("/hello/world"));
        $this->assertNotEquals(Filter::ERR_INVALID, $filter->validate("http://www.test.de/hello/world"));

        // relative
        $filter = new UrlFilter(["type" => "relative"]);
        $this->assertEquals(Filter::ERR_INVALID, $filter->validate("http://www.test.de/hello/world"));
        $this->assertEquals(Filter::ERR_INVALID, $filter->validate("/hello/world"));
        $this->assertNotEquals(Filter::ERR_INVALID, $filter->validate("hello/world"));

        // root
        $filter = new UrlFilter(["type" => "root"]);
        $this->assertEquals(Filter::ERR_INVALID, $filter->validate("http://www.test.de/hello/world"));
        $this->assertEquals(Filter::ERR_INVALID, $filter->validate("hello/world"));
        $this->assertNotEquals(Filter::ERR_INVALID, $filter->validate("/hello/world"));
    }
    
}