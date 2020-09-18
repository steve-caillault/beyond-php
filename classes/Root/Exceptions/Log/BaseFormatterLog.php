<?php

/**
 * Classe de formatage de base d'une exception
 */

namespace Root\Exceptions\Log;

use Throwable;

class BaseFormatterLog {
	
	/**
	 * Exception à gérer
	 * @var Throwable
	 */
	protected Throwable $_exception;
	
	/***************************************************/
	
	/**
	 * Constructeur
	 * @param Throwable $exception
	 */
	public function __construct(Throwable $exception)
	{
		$this->_exception = $exception;
	}
	
	/***************************************************/
	
	/**
	 * Retourne le message formaté
	 * @return string
	 */
	public function formattedMessage() : string
	{
		$exception = $this->_exception;
		
		return implode(PHP_EOL, [
			'Message : ' . $exception->getMessage(),
			'Fichier : ' . $exception->getFile(),
			'Ligne : ' . $exception->getLine(),
			'---',
			'Traces : ' . $exception->getTraceAsString(),
		]);
	}
	
	/***************************************************/
}