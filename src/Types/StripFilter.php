<?php
/**
 * @package    SecureInput
 */

namespace Topolis\Filter\Types;

use Topolis\Filter\Filter;
use Topolis\Filter\IFilterType;

/**
 * StripFilter
 * strips all tags except specifically allowed tags from input and
 * return cleaned string.
 * 
 * Options:
 *  - allowable_tags         string of allowed tags. Ex: "<a><b>"
 * 
 * @author tbulla
 * @package SecureInput
 */
class StripFilter implements IFilterType {
    
    protected $defaults = [
        "allowable_tags" => "",
        "preserve_null" => false
    ];

    protected $options = [];

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

        if($value === null && $this->options["preserve_null"] )
            return null;

        return strip_tags($value, $this->options["allowable_tags"]);
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