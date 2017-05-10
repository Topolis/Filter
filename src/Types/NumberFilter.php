<?php
/**
 * @package    SecureInput
 */

namespace Topolis\Filter\Types;
use Topolis\Filter\Filter;
use Topolis\Filter\IFilterType;

/**
 * StrippedFilter
 * strips all tags except specifically allowed tags from input and
 * return cleaned string.
 * 
 * Options:
 *  - min                  minimum value. Default: None (false)
 *  - max                  maximum value. Default: None (false)
 *  - adjust               adjust value to minum and maximum (if specified) or fail. Default: true
 *  - decimals             number of decimals. Default: Unlimited (false) 
 *  - round                round method to apply on value if "decimals" option is not false. 
 *                         Default: self::SI_NUMBER_ROUND
 *  - validate             Fail if value was not a valid Number as defined in options. Default: false
 *
 * TODO: a way to fix/translate foreign number formats (german , as deimal) would be a great option. It could damage compatibility of the filter function however...
 *
 * @author tbulla
 * @package SecureInput
 */
class NumberFilter implements IFilterType {
    
    /**
     * types of rounding methods available for option "round"
     */
    const SI_NUMBER_ROUND = "round";
    const SI_NUMBER_FLOOR = "floor";
    const SI_NUMBER_CEIL  = "ceil";
    
    /**
     * current options
     * @var array
     */
    protected $options = array();
    
    /**
     * default options
     * @var array
     */
    protected $defaults = array("min" => false,                    // no minimum border
                                "max" => false,                    // no maximum border
                                "adjust" => true,                  // adjust value to min/max borders (if specified)
                                "decimals" => false,               // Unlimited decimals
                                "round" => self::SI_NUMBER_ROUND,  // normal round method (if decimals specified)
                                "validate" => false);              // dont throw exception if rules are violated

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
        
        //Validate
        if(!is_numeric($value) && $this->options["validate"]) {
            return Filter::ERR_INVALID;
        }
        $value = (double)$value;
        
        // Validate Values to Min/Max/Decimals
        if( (!$this->options["adjust"] && $value < $this->options["min"]) ||
            (!$this->options["adjust"] && $value > $this->options["max"]) ) {
            return Filter::ERR_INVALID;
        }
        
        //Validate decimal count
        if($this->options["decimals"] !== false && 
           $this->options["round"] === false &&
           strlen($value) - strpos($value,".") - 1 > $this->options["decimals"]) {
            return Filter::ERR_INVALID;
        }
        
        // Adjust values to Min/Max
        if($this->options["adjust"] && $this->options["min"] !== false) {
            $value = max($this->options["min"], $value);
        }
        if($this->options["adjust"] && $this->options["max"] !== false) {
            $value = min($this->options["max"], $value);
        }
        
        //Round/Floor if needed
        if($this->options["decimals"] !== false && $this->options["round"] !== false) {
            switch($this->options["round"]) {
                case self::SI_NUMBER_CEIL:    $value = ceil($value * pow(10,$this->options["decimals"])) / pow(10,$this->options["decimals"]);    break;
                case self::SI_NUMBER_FLOOR:   $value = self::floor($value, $this->options["decimals"]);    break;
                case self::SI_NUMBER_ROUND:   $value = round($value, $this->options["decimals"]);    break;
            }
        }
        
        return $value;        
    }
    
    /**
     * execute the filter on a value
     * @param mixed $value
     * @return mixed
     */    
    public function validate($value) {
        return (string)$value == (string)$this->filter($value) ? $value : Filter::ERR_INVALID;
    }

    /**
     * cut of (round down) decimals with optional precision.
     * Replacement for floor with higher precision to avoid round errors in standard floor
     *
     * <code>
     * $x = Math::floor(123.2345344);    // $x will be 123
     * $x = Math::floor(2823.787214, 2); // $x will be 2823.28
     * </code>
     *
     * @param int|float $number        input number
     * @param int $prec                     (Optional) number of decimals in result. Default: 0
     * @return int;
     */
    static function floor($number, $prec = 0) {
        $number = $number * pow(10,$prec);
        $number = bcdiv($number, pow(10,$prec), $prec);
        return $number;
    }
    
}