<?php
/**
 * @package    SecureInput
 */

namespace Topolis\Filter\Types;

use Topolis\Filter\Filter;
use Topolis\Filter\IFilterType;

/**
 * PlainExtFilter
 * removes illegal characters from the input string.
 * Uses common character sets and or additional characters
 * 
 * Options:
 *  - characters         a string of allowed chars
 *  - characterset       common sets of characters, can be combined: PlainExtFilter::BASIC, PlainExtFilter::SIMPLE, PlainExtFilter::GERMAN, PlainExtFilter::FRENCH,
 * 
 * @author tbulla
 * @package SecureInput
 */
class PlainExtFilter implements IFilterType {
    
    const BASIC         = 1;
    const SIMPLE        = 2;    
    const GERMAN        = 4;
    const FRENCH        = 4;
    
    const INTERNATIONAL = 4;
    const ALL           = 15;
    
    protected static $charactersets = array(self::BASIC  => "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890",
                                            self::SIMPLE => ",.:\\-_()?! ",
                                            self::INTERNATIONAL => "\p{L}\p{N}");
    
    protected $defaults = array("characters" => "", "characterset" => self::INTERNATIONAL);
    protected $options = array();

    /**
     * construct a new SecureInput Filter
     * @param array $options         options for this filter
     */
    public function __construct($options = array()) {
        $this->options = $options + $this->defaults;
        $this->options["characters"] = preg_quote($this->options["characters"], '/');

        foreach(self::$charactersets as $bit => $characters)
            if((int)$this->options["characterset"] & $bit)
                $this->options["characters"] .= $characters;
    }

    /**
     * execute the filter on a value
     * @param mixed $value
     * @return mixed
     */    
    public function filter($value) {
        mb_internal_encoding("UTF-8");
        mb_regex_encoding("UTF-8");

        $pattern = '[^'.$this->options["characters"].']';

        return mb_ereg_replace($pattern, "", $value);
    }

    /**
     * execute the filter on a value
     * @param mixed $value
     * @return mixed
     */    
    public function validate($value) {
        return $value == $this->filter($value) ? $value : Filter::ERR_INVALID;
    }    
    
}