<?php
/**
 * BooleanFilter
 * apply a boolean filter to a value
 * @author Tobias Bulla
 * @copyright ToBe - 2017
 * @package Topolis
 * @subpackage Filter
 */

namespace Topolis\Filter\Types;

use Topolis\Filter\Filter;
use Topolis\Filter\IFilterType;

/**
 * BooleanFilter
 *
 * Options:
 *  - true       an array of valid values for a "true" result. Default: 1, "true", true
 *  - strict     do a strict type checking. Default: false
 * 
 */
class BooleanFilter implements IFilterType {
    
    protected $defaults = [
        "true" => [ 1, "true", true ],
        "strict" => false
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

        if(in_array($value, $this->options["true"], true))
            return true;

        if(!$this->options["strict"])
            return (boolean)$value;

        return false;
    }
    
    /**
     * execute the filter on a value
     * @param mixed $value
     * @return mixed
     */    
    public function validate($value) {
        return $value === $this->filter($value) ? $value : Filter::ERR_INVALID;
    }
    
    
}