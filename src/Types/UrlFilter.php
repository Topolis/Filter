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
class UrlFilter implements IFilterType {
    
    protected $defaults = array(

        // allow an array of any of the following constants (@see http://php.net/manual/function.parse-url.php) AND the special constant "root" to check if "path" is absolute
        // - scheme, host, port, user, pass, root, path, query, fragment
        "require" => [],
        "disallow" => [],

        // the list of allowed schemes, if any scheme is found
        "schemes" => ["http","https"],

        // shortcuts for require/disallow. Can be any of:
        // - absolute, relative, root
        "type" => false,
    );
    protected $options = array();

    /**
     * construct a new SecureInput Filter
     * @param array $options         options for this filter
     */
    public function __construct($options = array()) {
        $this->options = $options + $this->defaults;

        // example: //www.hello.com/world/something.html
        if($this->options["type"] == "absolute"){
            $this->options["require"] = array_merge($this->options["require"], ["host", "root", "path"]);
        }
        // example: /world/something.html
        else if($this->options["type"] == "root"){
            $this->options["require"] = array_merge($this->options["require"], ["path", "root"]);
            $this->options["disallow"] = array_merge($this->options["disallow"], ["host"]);
        }
        // example: world/something.html
        else if($this->options["type"] == "relative"){
            $this->options["require"] = array_merge($this->options["require"], ["path"]);
            $this->options["disallow"] = array_merge($this->options["disallow"], ["host", "root"]);
        }
    }

    /**
     * execute the filter on a value
     * @param mixed $value
     * @return mixed
     */    
    public function filter($value) {
        return $this->validate($value);
    }
    
    /**
     * execute the filter on a value
     * @param mixed $value
     * @return mixed
     */    
    public function validate($value) {

        // Simple removal of invalid characters
        $value = filter_var($value, FILTER_SANITIZE_URL);

        $parts = parse_url($value);

        // parse_url failed for this url!
        if(!$parts)
            return Filter::ERR_INVALID;

        // add special root element
        if(isset($parts["path"]) && strpos($parts["path"], "/") === 0)
            $parts["root"] = true;

        // required parts present ?
        $diff = array_diff($this->options["require"], array_keys($parts));
        if(count($diff) > 0)
            return Filter::ERR_INVALID;

        // disallowed parts present ?
        $diff = array_intersect($this->options["disallow"], array_keys($parts));
        if(count($diff) > 0)
            return Filter::ERR_INVALID;

        // Is scheme present and to be validated ?
        if($this->options["schemes"] && isset($parts["scheme"]) && !in_array($parts["scheme"], $this->options["schemes"]))
            return Filter::ERR_INVALID;

        return $value;
    }
    
    
}