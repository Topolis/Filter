<?php
/**
 * @package    SecureInput
 */

namespace Topolis\Filter\Types;

use Topolis\Filter\Filter;
use Topolis\Filter\IFilterType;

/**
 * siStrippedFilter
 * strips all tags except specifically allowed tags from input and
 * return cleaned string.
 * 
 * Options:
 *  - allowable_tags         string of allowed tags. Ex: "<a><b>"
 * 
 * @author tbulla
 * @package SecureInput
 */
class TestFilter implements IFilterType {
    
    protected $defaults = array(
        "expected" => null,
        "error" => Filter::ERR_INVALID,
        "strict" => true
    );
    protected $options = array();

    /**
     * construct a new SecureInput Filter
     * @param array $options         options for this filter
     */
    public function __construct($options = array()) {
        $this->options = $options + $this->defaults;
    }

    /**
     * execute the filter on a value
     * @param mixed $value
     * @return mixed
     */    
    public function filter($value) {
        return $this->match($value) ? $value : $this->options["error"];
    }
    
    /**
     * execute the filter on a value
     * @param mixed $value
     * @return mixed
     */    
    public function validate($value) {
        return $this->filter($value);
    }

    protected function match($value){
        $expected = is_array($this->options["expected"]) ? $this->options["expected"] : [$this->options["expected"]];

        return in_array($value, $expected, $this->options["strict"]);
    }
    
    
}