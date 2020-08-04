<?php
/**
 * @package SecureInput
 * @subpackage UnitTest 
 */

require_once dirname(__FILE__).'/../src/IFilterType.php';

use Topolis\Filter\Types\PlainFilter;
use Topolis\Filter\Types\ReplaceFilter;

/**
 * Plain Filter Test
 * 
 * @package SecureInput
 * @subpackage UnitTest
 * @author tbulla
 */
class ReplaceTest extends PHPUnit_Framework_TestCase {

    public function testExecute() {

        // No map
        $Filter = new ReplaceFilter();
        $input  = "abcdefghijklmnobqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXQZ1234567890-_.,!\"§$%&/()=?´`öäüÖÄÜß<*+#'>@|~:;\\";
        $this->assertEquals($input, $Filter->filter($input));

        // Manual Map
        $Filter = new ReplaceFilter(['map' => ['a' => 'b', 1 => 2]]);
        $input  = 'aäbcdefghijklmnopqrstuvwxyz’1234567890';
        $output = 'bäbcdefghijklmnopqrstuvwxyz’2234567890';
        $this->assertEquals($output, $Filter->filter($input));

        // Manual Map + Set
        $Filter = new ReplaceFilter(['map' => ['a' => 'b', 1 => 2], 'sets' => ReplaceFilter::QUOTES]);
        $input  = 'aäbcdefghijklmnopqrstuvwxyz’1234567890';
        $output = 'bäbcdefghijklmnopqrstuvwxyz\'2234567890';
        $this->assertEquals($output, $Filter->filter($input));

        // Quotes
        $Filter = new ReplaceFilter(['sets' => ReplaceFilter::QUOTES]);
        $input  = 'abc\'a"a′a″a’a‚a“a”a„a';
        $output = 'abc\'a"a\'a"a\'a\'a"a"a"a';
        $this->assertEquals($output, $Filter->filter($input));

        // Editorials
        $Filter = new ReplaceFilter(['sets' => ReplaceFilter::EDITORIAL]);
        $input  = 'abc–a—a…a›a‹a a a a a';
        $output = 'abc-a-a...a>a<a a a a a';
        $this->assertEquals($output, $Filter->filter($input));

        // Umlauts
        $Filter = new ReplaceFilter(['sets' => ReplaceFilter::UMLAUT]);
        $input  = 'abcäaÄaöaÖaüaÜaßa';
        $output = 'abcaeaAeaoeaOeaueaUeassa';
        $this->assertEquals($output, $Filter->filter($input));

        // Multiples
        $Filter = new ReplaceFilter(['sets' => ReplaceFilter::UMLAUT + ReplaceFilter::EDITORIAL]);
        $input  = 'abcöa…a’a';
        $output = 'abcoea...a’a';
        $this->assertEquals($output, $Filter->filter($input));

        $Filter = new ReplaceFilter(['sets' => ReplaceFilter::QUOTES + ReplaceFilter::EDITORIAL]);
        $input  = 'abcöa…a’a';
        $output = 'abcöa...a\'a';
        $this->assertEquals($output, $Filter->filter($input));
    }
}