<?php
/**
 * @package    SecureInput
 */

namespace Topolis\Filter\Types;

use Topolis\Filter\Filter;
use Topolis\Filter\IFilterType;

/**
 * PlainFilter
 * removes anything but plain a-Z and 0-9 chars from input
 * 
 * @author tbulla
 * @package SecureInput
 */
class ReplaceFilter implements IFilterType {
    
    protected static $defaults = ['map' => [], 'sets' => 0];
    protected $options = array();

    public const QUOTES = 1;
    public const EDITORIAL = 2;
    public const UMLAUT = 4;

    /**
     * construct a new SecureInput Filter
     * @param array $options         options for this filter
     */
    public function __construct($options = array()) {
        $this->options = $options + self::$defaults;

        // Quotes and their variants
        if(self::QUOTES & $this->options['sets']){
            $this->options['map'] += [
                mb_chr(8221) => '"',            // “ - &rdquo;
                mb_chr(8220) => '"',            // ” - &ldquo;
                mb_chr(8222) => '"',            // „ - &bdquo;
                mb_chr(8243) => '"',            // ″ - &Prime;
                mb_chr(8217) => '\'',           // ’ - &rsquo;
                mb_chr(8216) => '\'',           // ‘ - &lsquo;
                mb_chr(8218) => '\'',           // ‚ - &sbquo;
                mb_chr(8242) => '\'',           // ′ - &prime;
            ];
        }

        // Special editorial characters
        if(self::EDITORIAL & $this->options['sets']){
            $this->options['map'] += [
                mb_chr(8722) => '-',            // − - &minus;
                mb_chr(8211) => '-',            // – - &ndash;
                mb_chr(8212) => '-',            // — - &mdash;
                mb_chr(8230) => '...',          // … - &hellip;
                mb_chr(8250) => '>',            // › - &rsaquo;
                mb_chr(8249) => '<',            // ‹ - &lsaquo;
                mb_chr(8194) => ' ',            // &ensp;
                mb_chr(8195) => ' ',            // &emsp;
                mb_chr(8201) => ' ',            // &thinsp;
                mb_chr(8239) => ' ',            // Small protected space;
                mb_chr(8204) => '',             // &zwnj;
                mb_chr(8205) => '',             // &zwj;
            ];
        }

        // Umlauts (FIXME: Add any international replacements if known)
        if(self::UMLAUT & $this->options['sets']){
            $this->options['map'] += [
                mb_chr(228) => 'ae',   // ä - &auml;
                mb_chr(246) => 'oe',   // ö - %ouml;
                mb_chr(252) => 'ue',   // ü - &uuml;
                mb_chr(196) => 'Ae',   // Ä - &Auml;
                mb_chr(214) => 'Oe',   // Ö - %Ouml;
                mb_chr(220) => 'Ue',   // Ü - &Uuml;
                mb_chr(223) => 'ss',   // ß - &szlig;
            ];
        }
    }

    /**
     * execute the filter on a value
     * @param mixed $value
     * @return mixed
     */    
    public function filter($value) {
        $search = array_keys($this->options['map']);
        $replace = array_values($this->options['map']);
        return str_replace($search, $replace, $value);
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