<?php

namespace Topolis\Filter\Types;

use Topolis\Filter\IFilterType;

class PathFilter extends PlainExtFilter implements IFilterType {
	
	const BASIC   = 1;
	const UNIX    = 2;
	const WINDOWS = 4;
	
	const ALL     = 7;
	
	protected static $charactersets = array(self::BASIC  => "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890\\.\\_\\-",
											self::UNIX => "\\/",
											self::WINDOWS => "\\\\");
	
	protected $defaults = array("characters" => "", "characterset" => self::ALL);

    /** @noinspection PhpMissingParentConstructorInspection */

    /**
     * construct a new SecureInput Filter
     * @param array $options         options for this filter
     */
    public function __construct($options = array()) {
        $this->options = $options + $this->defaults;

        foreach(self::$charactersets as $bit => $characters)
            if((int)$this->options["characterset"] & $bit)
                $this->options["characters"] .= $characters;
    }
}