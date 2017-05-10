<?php
/**
 * DateTimeFilter
 * apply a date time filter to a value
 * @author Tobias Bulla
 * @copyright ToBe - 2017
 * @package Topolis
 * @subpackage Filter
 */

namespace Topolis\Filter\Types;

use Topolis\Filter\Filter;
use Topolis\Filter\FilterException;
use Topolis\Filter\IFilterType;
use \Exception;
use \DateTime;
use \DateTimeZone;

/**
 * DateTimeFilter
 * apply a date time filter to a value
 * return formatted string.
 *
 * Options:
 *  - format         valid format string. Default: "Y-m-d h:i:d"
 *  - timezone       timezone for output time. Ouput time will be calculated relative to TZ
 *                   given in input value (or locale) and TZ given in this option (or locale)
 *
 * @author tbulla
 * @package SecureInput
 */
class DateTimeFilter implements IFilterType {

    protected $defaults = [
        "format" => "Y-m-d H:i:s",
        "timezone" => false
    ];

    protected $options = [];

    /**
     * construct a new SecureInput Filter
     * @param array $options         options for this filter
     */
    public function __construct($options = []) {
        $this->options = $options + $this->defaults;
    }

    /**
     * execute the filter on a value
     * @param mixed $value
     * @return mixed
     */
    public function filter($value) {
        if($value === null)
            return null;

        try {
            $sourceDT = new DateTime($value);
            $targetTZ = new DateTimeZone($this->options["timezone"] ? $this->options["timezone"] : date_default_timezone_get());
            $targetDT = $sourceDT;
            $targetDT->setTimeZone($targetTZ);

            return $targetDT->format($this->options["format"]);
        }
        catch(Exception $e) {
            return false;
        }
    }

    /**
     * execute the filter on a value
     * @param mixed $value
     * @return mixed
     */
    public function validate($value) {
        return date_create($value) ? $this->filter($value) : Filter::ERR_INVALID;
    }


}