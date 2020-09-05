<?php

/**
 * Gestionnaire de cookie
 */

namespace Root\Cookie;

use Root\Application;

final class Cookie extends DataInCookie {
	
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
	 * Récupére la valeur d'une donnée
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function get(string $key, $default = NULL)
	{
		$keySaved = $this->_cryptCookieName($key);
		
		$value = $default;
		if(array_key_exists($key, $this->_data))
		{
			$value = $this->_data[$key];
		}
		elseif(array_key_exists($keySaved, $_COOKIE))
		{
			$valueSavedCrypted = $_COOKIE[$keySaved];
			$valueSavedDecrypted = base64_decode($valueSavedCrypted);
			
			$applicationKey = Application::instance()->getKey();
			$applicationKeyPosition = strpos($valueSavedDecrypted, $applicationKey);
			$valuePosition = ($applicationKeyPosition !== FALSE) ? ($applicationKeyPosition + strlen($applicationKey)) : NULL;
			
			if($valuePosition !== NULL)
			{
				$value = substr($valueSavedDecrypted, $valuePosition);
				$this->_data[$key] = $value;
			}
		}
		
		return $value;
	}
	
	/**********************************************************************/
	
	/**
	 * Création d'un cookie
	 * @param string $key
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
	public function set(string $key, $value, array $options = []) : bool
	{
		if(! is_string($value))
		{
			exception('La valeur du cookie doit être une chaine de caractère.');
		}
		
		$cookieOptions = array_merge($this->_defaultOptionsCookie(), $options);
		$this->_formatOptionsCookie($cookieOptions);
		$this->_validOptionsCookie($cookieOptions);
		
		$expireIn = getArray($cookieOptions, 'expireIn');
		unset($cookieOptions['expireIn']);
		$cookieOptions[self::OPTION_EXPIRES] = time() + (int) $expireIn;
		
		$cookieOptions = array_filter($cookieOptions, function($optionKey) {
			return (in_array($optionKey, self::OPTIONS));
		}, ARRAY_FILTER_USE_KEY);
		
		$keyToSaved = $this->_cryptCookieName($key);
		$valueToSaved = $this->_cryptValue($value);
		
		$success = setcookie($keyToSaved, $valueToSaved, $cookieOptions);
		
		if($success)
		{
			$this->_data[$key] = $value;
		}
		
		return $success;
	}
	
	/**********************************************************************/
	
	/**
	 * Supprime la valeur de la clé en paramètre
	 * @param string $key
	 * @return bool
	 */
	public function delete(string $key) : bool
	{
		$existing = ($this->get($key) !== NULL);
		
		if($existing)
		{
			$success = $this->set($key, '', [
				'expireIn' => -3600,
			]);
		}
		else
		{
			$success = TRUE;
		}
		
		if($success)
		{
			$keySaved = $this->_cryptCookieName($key);
			unset($this->_data[$key], $_COOKIE[$keySaved]);
		}
		
		return $success;
	}
	
	/**********************************************************************/
	
}