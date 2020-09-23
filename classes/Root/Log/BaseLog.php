<?php

/**
 * Enregistrement de messages
 */

namespace Root\Log;

use DateTime, DateTimeZone;
use Root\Route\AbstractRoute as Route; 
use Root\Manager\BaseManager;

abstract class BaseLog extends BaseManager {
	
	public const 
		LEVEL_EMERGENCY = 'emergency',
		LEVEL_ALERT = 'alert',
		LEVEL_CRITICAL = 'critical',
		LEVEL_ERROR = 'error',
		LEVEL_WARNING = 'warning',
		LEVEL_NOTICE = 'notice',
		LEVEL_INFO = 'info',
		LEVEL_DEBUG = 'debug'
	;
	
	/**
	 * Date d'enregistrement
	 * @var Datetime
	 */
	protected Datetime $_datetime;
	
	/**
	 * URI où le message a été enregistré
	 * @var string
	 */
	protected string $_uri;
	
	/************************************************************/
	
	/**
	 * Constructeur
	 * @param array $params
	 */
	protected function __construct(array $params)
	{
		$this->_datetime = new DateTime('now', new DateTimeZone('UTC'));
		$this->_uri = (Route::currentIdentifier() ?: '');
	}
	
	/************************************************************/
	
	/**
	 * Ajoute un message
	 * @param string $message
	 * @param string $level Niveau d'urgence
	 * @return bool
	 */
	abstract public function add(string $message, string $level = self::LEVEL_DEBUG) : bool;
	
	/************************************************************/
	
}