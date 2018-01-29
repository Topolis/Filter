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

    const TYPE_SINGLE        = "single";
    const TYPE_ARRAY         = "array";
    const TYPE_TREE          = "tree";
    const TYPE_ANY           = "any";

    const TYPE_DEFAULT = self::TYPE_ANY;
    
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
        $type = isset($options["type"]) ? $options["type"] : static::TYPE_DEFAULT;
        $queue = self::loadFilters($filter, $options);
        return self::execValidateQueue($input, $queue, $type);
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
        $type = isset($options["type"]) ? $options["type"] : static::TYPE_DEFAULT;
        $queue = self::loadFilters($filter, $options);
        return self::execFilterQueue($input, $queue, $type);
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
                throw new FilterException("Filter class '$filtername' not found");
            
            $filter = new $classname($options[$idx]);
            
            if(!$filter instanceof IFilterType)
                throw new FilterException("Filter is not instance of IFilterType");
                    
            $queue[] = $filter;
        }

        return $queue;
    }

    /**
     * execute filter queue on a value
     * @param mixed $value unfiltered value
     * @param IFilterType[] $queue array of instantiated filter objects
     * @param $type
     * @return mixed either filtered value or self::ERR_INVALID
     */
    protected static function execFilterQueue($value, array $queue, $type) {
        foreach($queue as $filter) {
            $value = self::executeValueMethod($value, $filter, $type, "filter");

            if($value === self::ERR_INVALID)
                return false;
        }
        return $value;
    }

    /**
     * execute filters on value
     * @param mixed $value unfiltered value
     * @param IFilterType[] $queue array of instantiated filter objects
     * @param $type
     * @return mixed either filtered value or self::ERR_INVALID
     */
    protected static function execValidateQueue($value, array $queue, $type) {
        foreach($queue as $filter) {
            $value = self::executeValueMethod($value, $filter, $type, "validate");

            if($value === self::ERR_INVALID)
                return false;
        }
        return $value;
    }

    protected static  function executeValueMethod($value, $filter, $type, $method, $level = 0) {

        // Array/Tree of values
        if(is_array($value)){

            if($type === self::TYPE_SINGLE) // type single expects single value
                return self::ERR_INVALID;

            if($type === self::TYPE_ARRAY && $level > 0) // type array expects single value after first level
                return self::ERR_INVALID;

            foreach($value as $idx => $item){
                $value[$idx] = self::executeValueMethod($item, $filter, $type, $method, $level+1);
                if($value[$idx] === self::ERR_INVALID)
                    return self::ERR_INVALID;
            }
            return $value;
        }

        // Single value
        if($type === self::TYPE_TREE && $level == 0) // type tree expects array at least at first level
            return self::ERR_INVALID;

        if($type === self::TYPE_ARRAY && $level == 0) // type array expects array at first level
            return self::ERR_INVALID;

        // Execute method
        return $filter->$method($value);

    }
}
