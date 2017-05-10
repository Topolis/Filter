<?php
/**
 * @package    SecureInput
 */

namespace Topolis\Filter\Types;

use Topolis\Filter\Filter;
use Topolis\Filter\FilterException;
use Topolis\Filter\IFilterType;

/**
 * siPassthroughFilter
 * returns everything as given. usefull for unit testing.
 * - append       a string to append to input value
 * 
 * @author tbulla
 * @package SecureInput
 */
class PassthroughFilter implements IFilterType {

    protected $defaults = [
        "append" => ""
    ];

    protected $options = [];
    
    /**
     * construct a new SecureInput Filter
     * @param array $options         ignored
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
        return $value.$this->options["append"];
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