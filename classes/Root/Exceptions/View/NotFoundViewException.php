<?php

/**
 * Exception lorsque le fichier de la vue n'a pas été trouvé
 */

namespace Root\Exceptions\View;

use Exception;

class NotFoundViewException extends Exception {
	
	/**
	 * Code
	 * @var integer
	 */
	protected $code = 500;
	
}