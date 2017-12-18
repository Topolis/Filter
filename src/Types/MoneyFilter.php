<?php
/**
 * @package    SecureInput
 */

namespace Topolis\Filter\Types;
use Topolis\Filter\IFilterType;

/**
 * MoneyFilter
 * A short hand filter for a NumberFilter with two decimals.
 * 
 * @author tbulla
 * @package SecureInput
 */
class MoneyFilter extends NumberFilter implements IFilterType {
    
    /**
     * default options
     * @var array
     */
    protected $defaults = array("min" => false,                    // no minimum border
                                "max" => false,                    // no maximum border
                                "adjust" => true,                  // adjust value to min/max borders (if specified)
                                "decimals" => 2,                   // Unlimited decimals
                                "round" => self::SI_NUMBER_ROUND,  // normal round method (if decimals specified)
                                "validate" => false);              // dont throw exception if rules are violated
}