<?php
/**
 * Filter
 * Filter/Validate a value by one or more filters
 * @author Tobias Bulla
 * @copyright ToBe - 2017
 * @package Topolis
 * @subpackage Filter
 */

namespace Topolis\Filter;

/**
 * Methods to securely filter input values from untrusted sources
 * 
 * @package SecureInput
 * @author tbulla
 */

class Filter {
    
    const ERR_NONEXISTANT    = "sinonexistant";
    const ERR_INVALID        = "siinvalid";
    
    /**
     * Constructor
     */
    public function __construct() {
    }

    /**
     * 
     * same as get() method, but validates input and returns either value or false
     * 
     * @param mixed $input             input data to validate
     * @param string|array $filter     (Optional) either one filter name or array of filter names. Default: "Plain"
     * @param array $options           (Optional) either one options array or array of options array for filters, 
     *                                 depending if we specified one or mode filters above. Default: array()
     * @return mixed                   filtered data
     */
    public static function validate($input, $filter = "plain", $options = []) {
        $queue = self::loadFilters($filter, $options);
        return self::execValidateQueue($input, $queue);
    }

    /**
     * 
     * filter a given value. Usefull when input is not from some external source but from an app itself.
     * 
     * @param mixed $input               input variable to filter
     * @param string|array $filter     (Optional) either one filter name or array of filter names. Default: "Plain"
     * @param array $options           (Optional) either one options array or array of options array for filters, 
     *                                 depending if we specified one or mode filters above. Default: array()
     * @return mixed                   filtered data
     */
    public static function filter($input, $filter = "Plain", $options = []) {

        $queue = self::loadFilters($filter, $options);
        return self::execFilterQueue($input, $queue);
    }    
    
    /**
     * load needed filters
     * @param string|array $filters    either one filter name or array of filter names. Default: "Plain"
     * @param array $options           (Optional) either one options array or array of options array for filters, 
     *                                 depending if we specified one or mode filters above. Default: array()
     * @throws FilterException
     * @return IFilterType[]           array of initialized filter objects
     */
    protected static function loadFilters($filters, $options = []) {
        
        $queue = array();
        
        if(!is_array($filters)) {
            $filters = [$filters];
            $options = [$options];
        }

        foreach($filters as $idx => $filtername) {
            
            $classname = __NAMESPACE__."\\Types\\".preg_replace("/[^A-z0-9]/", "", ucfirst($filtername))."Filter";

            if(!class_exists($classname))
                throw new FilterException("Filter class not found");
            
            $filter = new $classname($options[$idx]);
            
            if(!$filter instanceof IFilterType)
                throw new FilterException("Filter is not instance of IFilterType");
                    
            $queue[] = $filter;
        }

        return $queue;
    }
    
    /**
     * execute filter queue on a value
     * @param mixed $value               unfiltered value
     * @param IFilterType[] $queue       array of instantiated filter objects
     * @return mixed                     either filtered value or self::ERR_INVALID
     */
    protected static function execFilterQueue($value, array $queue) {
        foreach($queue as $filter) {
            $value = self::execFilter($value, $filter);

            if($value === self::ERR_INVALID)
                return false;
        }
        return $value;
    }

    /**
     * Execute a filter on a value (recursively if it's an array)
     * @param $value
     * @param IFilterType $filter
     * @return array|mixed
     */
    protected static function execFilter($value, IFilterType $filter) {
        if(is_array($value)){
            foreach($value as $idx => $item)
                $value[$idx] = self::execFilter($item, $filter);
            return $value;
        }

        return $filter->filter($value);
    }
    
    /**
     * execute filters on value
     * @param mixed $value               unfiltered value
     * @param IFilterType[] $queue               array of instantiated filter objects
     * @return mixed                     either filtered value or self::ERR_INVALID
     */
    protected function execValidateQueue($value, array $queue) {
        foreach($queue as $filter) {
            $value = self::execValidate($value, $filter);

            if($value === self::ERR_INVALID)
                return false;
        }
        return $value;
    }

    /**
     * @param $value
     * @param IFilterType $filter
     * @return array|string
     */
    protected function execValidate($value, IFilterType $filter) {
        if(is_array($value)){
            foreach($value as $idx => $item){
                $value[$idx] = self::execValidate($item, $filter);
                if($value[$idx] === self::ERR_INVALID)
                    return self::ERR_INVALID;
            }
        }
        else
            $value = $filter->validate($value);
            
        return $value;
    }    
}
