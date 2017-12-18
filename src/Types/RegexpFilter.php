<?php
/**
 * @package    SecureInput
 */

namespace Topolis\Filter\Types;

use Topolis\Filter\Filter;
use Topolis\Filter\IFilterType;

/**
 * RegexpFilter
 * strips all tags except specifically allowed tags from input and
 * return cleaned string.
 * 
 * Options:
 *  - allowable_tags         string of allowed tags. Ex: "<a><b>"
 * 
 * @author tbulla
 * @package SecureInput
 */
class RegexpFilter implements IFilterType {
    
    protected $defaults = array("pattern" => "//");
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
        return preg_match($this->options["pattern"], $value) ? $value : "";
    }

    /**
     * execute the filter on a value
     * @param mixed $value
     * @return mixed
     */    
    public function validate($value) {
        return preg_match($this->options["pattern"], $value) ? $value : Filter::ERR_INVALID;
    }    
    
}
