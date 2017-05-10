<?php
/**
 * @package SecureInput
 */

namespace Topolis\Filter;

/**
 * interface for filters
 * 
 * @package SecureInput
 * @author ToBe
 */
interface IFilterType {
    
    /**
     * construct a new SecureInput Filter
     * @param array $options         options for this filter
     */
    public function __construct($options = array());
    
    /**
     * execute the filter on a value
     * @param mixed $value
     * @return mixed
     */
    public function filter($value);

    /**
     * validate a value
     * @param mixed $value
     * @return mixed
     */
    public function validate($value);
    
}