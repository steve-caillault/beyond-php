<?php

/**
 * Gestionnaire de session
 */

namespace Root;

use Root\Cookie\DataInCookie;

class Session extends DataInCookie {
	
	public const 
		LIFETIME_SESSION = 0,
		/***/
		OPTION_LIFETIME = 'lifetime'
	; 
	
	/**
	 * Données en session
	 * @var array
	 */
	private $_data = [];
	
	protected const CONFIGURATION_PATH = 'session';
	
	/**************************************************************/
	
	/**
	 * Constructeur
	 */
	protected function __construct()
	{
		session_name($this->_cryptCookieName('session'));
		session_set_cookie_params($this->_defaultOptionsCookie());
		session_start();
		$this->_data = $_SESSION;
	}
	
	/**************************************************************/
	
	/**
	 * Retourne les règles de validation des options cookies
	 * @return array
	 */
	protected function _validationOptionsCookieRules() : array
	{
		return array_merge(parent::_validationOptionsCookieRules(), [
			self::OPTION_LIFETIME => [
				array('required'),
				array('numeric'),
				array('min', [ 'min' => 0, ]),
			],
		]);
	}
	
	/**
	 * Retourne les options de base
	 * @return array
	 */
	protected function _baseOptionsCookie() : array
	{
		return array_merge(parent::_baseOptionsCookie(), [
			self::OPTION_LIFETIME => self::LIFETIME_SESSION,
		]);
	}
	
	/**************************************************************/

	/**
	 * Récupére la valeur d'une donnée
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function get(string $key, $default = NULL)
	{
		return getArray($this->_data, $key, $default);
	}
	
	/**
	 * Enregistre une valeur
	 * @param string $key
	 * @param mixed $value
	 * @param array $options
	 * @return bool
	 */
	public function set(string $key, $value, array $options = []) : bool
	{
		$_SESSION[$key] = $value;
		$this->_data[$key] = $value;
		return TRUE;
	}
	
	/**
	 * Supprime une donnée
	 * @param string $key
	 * @return bool
	 */
	public function delete(string $key) : bool
	{
		unset($_SESSION[$key], $this->_data[$key]);
		return TRUE;
	}
	
	/**************************************************************/
	
}