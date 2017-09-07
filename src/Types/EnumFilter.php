<?php
/**
 * @package    SecureInput
 */

namespace Topolis\Filter\Types;

use Topolis\Filter\Filter;
use Topolis\Filter\IFilterType;

/**
 * PlainFilter
 * removes anything but plain a-Z and 0-9 chars from input
 *
 * @author tbulla
 * @package SecureInput
 */
class EnumFilter implements IFilterType {

    protected $defaults = [
        "values" => [],
        "strict" => true,         // do a type safe check
        "insensitive" => false,   // allow case insensitive checks for strings
        "autocorrect" => true,    // return value from enumeration instead of tested value on passed check (possibly correcting wrong case)
    ];
    protected $options = [];

    // Actual test array with insensitive values if needed
    protected $enumeration = [];

    /**
     * construct a new Filter
     * @param array $options         options for this filter
     */
    public function __construct($options = array()) {
        $this->options = $options + $this->defaults;

        $this->enumeration = $this->options["values"];

        // Make test values lowercase if insensitive
        if($this->options["insensitive"]) {
            array_walk($this->enumeration, function(&$item, $key){
               $item = strtolower($item);
            });
        }
    }

    /**
     * execute the filter on a value
     * @param mixed $value
     * @return mixed
     */
    public function filter($value) {

        $testvalue = $this->options["insensitive"] ? strtolower($value): $value;

        // Value is not in enumeration
        if (!in_array($testvalue, $this->enumeration, $this->options["strict"]))
            return Filter::ERR_INVALID;

        $found = array_search($value, $this->enumeration, $this->options["strict"]);

        return $this->options["values"][$found];
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