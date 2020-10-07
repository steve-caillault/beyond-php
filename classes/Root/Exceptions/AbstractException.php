<?php

/**
 * Exception abstraite
 */

namespace Root\Exceptions;

use Exception;

abstract class AbstractException extends Exception {
	
	/**
	 * Code
	 * @var integer
	 */
	protected $code = 500;
	
}