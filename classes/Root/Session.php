<?php

/**
 * Gestionnaire de session
 */

namespace Root;

use Root\Cookie\ValidationCookie;

class Session extends ValidationCookie {
	
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
	 * Retourne la valeur de la clé en session
	 * @param string $key
	 * @param mixed $default Valeur à retourner si la clé n'a pas été trouvé
	 * @return mixed
	 */
	public function retrieve(string $key, $default = NULL)
	{
		return getArray($this->_data, $key, $default);
	}
	
	/**
	 * Modfifit la valeur de la clé en session
	 * @param string $key
	 * @param mixed $value Valeur à affecter
	 * @return void
	 */
	public function change(string $key, $value) : void
	{
		$_SESSION[$key] = $value;
		$this->_data[$key] = $value;
	}
	
	/**
	 * Supprime la valeur de la clé en paramètre
	 * @param string $key
	 * @return void
	 */
	public function delete(string $key) : void
	{
		unset($_SESSION[$key], $this->_data[$key]);
		
	}
	
	/**************************************************************/
	
}