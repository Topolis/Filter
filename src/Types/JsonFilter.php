<?php
/**
 * @package    SecureInput
 */

namespace Topolis\Filter\Types;

use Topolis\Filter\Filter;
use Topolis\Filter\FilterException;
use Topolis\Filter\IFilterType;

/**
 * JsonFilter
 * validate Json string
 * return serialized string
 * 
 * Options:
 *  - validate
 *  - serialize
 * 
 * @author skendlba
 * @package SecureInput
 */
class JsonFilter implements IFilterType {

    const JSON         = 1;  // Leave input as jason, only validate
    const DECODED      = 2;  // decode to native array/object types
    const SERIALIZED   = 3;  // decode and serialize to string
    
    protected $defaults = array("format" => self::DECODED);
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
     * @throws FilterException
     */
    public function filter($value) {
    	    	
    	$value   = str_replace(["\r", "\r\n", "\n"], '', $value);
    	$decoded = json_decode($value,true);
    	if(is_null($decoded)){
    		return null;
    	}    	
    	
    	switch($this->options["format"]){
    	    case self::DECODED:
    	        return $decoded;
    	    case self::JSON:
    	        return $value;
    	    case self::SERIALIZED:
    	        return serialize($decoded);
    	    default:
    	        throw new FilterException("unknown format for JSON filter specified");
    	}
    }

    /**
     * execute the filter on a value
     * @param mixed $value
     * @return mixed
     */      
    public function validate($value) {
        return !is_null(json_decode($value)) ? $value : Filter::ERR_INVALID;
    }    
    
}