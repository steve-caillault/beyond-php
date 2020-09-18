<?php

/**
 * Gestion des exceptions
 */

namespace Root\Exceptions;

class CLIException {
	
	/**
	 * Gestionnaire d'exception
	 * @param \Throwable $exception
	 * @return void
	 */
	public static function handler(\Throwable $exception) : void
	{	
		Exception::log($exception);
		
		$modeDebug = getConfig('beyond.debug');
		if($modeDebug)
		{
			debug($exception, TRUE);
		}
		
		$message = $exception->getMessage();
		debug($message, TRUE);
	}
	
}