<?php

/**
 * Gestionnaire de cookie
 */

namespace Root\Cookie;

use Root\Application;

final class Cookie extends ValidationCookie {
	
	public const
		OPTION_EXPIRES = 'expires',
		OPTIONS = [ 
			self::OPTION_EXPIRES, 
			self::OPTION_PATH, 
			self::OPTION_DOMAIN, 
			self::OPTION_SECURE, 
			self::OPTION_HTTP_ONLY, 
			self::OPTION_SAME_SITE,
		]
	;
	
	protected const CONFIGURATION_PATH = 'cookie';
		
	/**
	 * Liste des données
	 * @var array
	 */
	private array $_data = [];
	
	/**********************************************************************/
	
	/**
	 * Retourne les règles de validation des options cookies
	 * @return array
	 */
	protected function _validationOptionsCookieRules() : array
	{
		return array_merge(parent::_validationOptionsCookieRules(), [
			'expireIn' => [
				array('required'),
				array('numeric'),
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
			'expireIn' => 3600,
		]);
	}
	
	/**********************************************************************/
	
	/**
	 * Crypte la valeur d'un cookie
	 * @param string $string
	 * @return string
	 */
	private function _cryptValue(string $string) : string
	{
		return base64_encode(Application::instance()->getKey() . $string);
	}
	
	/**********************************************************************/
	
	/**
	 * Création d'un cookie
	 * @param string $name
	 * @param string $value
	 * @param array $options : array(
	 * 		'expireIn' => <int>, // Durée de vie du Cookie
	 * 		'path' => <string>, // Chemin où le cookie est accessible
	 * 		'domain' => <string>, // Domaine pour lequel le cookie est accessible
	 * 		'secure' => <bool>, // Cookie transmit en HTTPS seulement
	 * 		'httponly' => <bool>, // Accessible en HTTP seulement
	 * )
	 * @return bool
	 */
	public function make(string $name, string $value, array $options = []) : bool
	{
		$cookieOptions = array_merge($this->_defaultOptionsCookie(), $options);
		$this->_formatOptionsCookie($cookieOptions);
		$this->_validOptionsCookie($cookieOptions);
		
		$expireIn = getArray($cookieOptions, 'expireIn');
		unset($cookieOptions['expireIn']);
		$cookieOptions[self::OPTION_EXPIRES] = time() + (int) $expireIn;
		
		$cookieOptions = array_filter($cookieOptions, function($optionKey) {
			return (in_array($optionKey, self::OPTIONS));
		}, ARRAY_FILTER_USE_KEY);
		
		$nameToSaved = $this->_cryptCookieName($name);
		$valueToSaved = $this->_cryptValue($value);
		
		$success = setcookie($nameToSaved, $valueToSaved, $cookieOptions);
		
		if($success)
		{
			$this->_data[$name] = $value;
		}
		
		return $success;
	}
	
	/**********************************************************************/
	
	/**
	 * Supprime un cookie
	 * @param string $name
	 * @return bool
	 */
	public function delete(string $name) : bool
	{
		$existing = ($this->get($name) !== NULL);
		
		if($existing)
		{
			$success = $this->make($name, '', [
				'expireIn' => -3600,
			]);
		}
		else
		{
			$success = TRUE;
		}
		
		if($success)
		{
			$nameSaved = $this->_cryptCookieName($name);
			unset($this->_data[$name], $_COOKIE[$nameSaved]);
		}
		
		return $success;
	}
	
	/**********************************************************************/
	
	/**
	 * Récupére la valeur d'un cookie
	 * @param string $name
	 * @param mixed $default
	 * @return mixed
	 */
	public function get(string $name, $default = NULL)
	{
		$nameSaved = $this->_cryptCookieName($name);
		
		$value = $default;
		if(array_key_exists($name, $this->_data))
		{
			$value = $this->_data[$name];
		}
		elseif(array_key_exists($nameSaved, $_COOKIE))
		{
			$valueSavedCrypted = $_COOKIE[$nameSaved];
			$valueSavedDecrypted = base64_decode($valueSavedCrypted);
			
			$applicationKey = Application::instance()->getKey();
			$applicationKeyPosition = strpos($valueSavedDecrypted, $applicationKey);
			$valuePosition = ($applicationKeyPosition !== FALSE) ? ($applicationKeyPosition + strlen($applicationKey)) : NULL;
		
			if($valuePosition !== NULL)
			{
				$value = substr($valueSavedDecrypted, $valuePosition);
				$this->_data[$name] = $value;
			}
		}
		
		return $value;
	}
	
	/**********************************************************************/
	
}