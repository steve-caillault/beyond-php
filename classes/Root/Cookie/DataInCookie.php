<?php

/**
 * Gestion d'objet sauvegardant des données en cookie
 */

namespace Root\Cookie;

use Root\{ Arr, URL, Validation, Config, Instanciable, Application };
use Root\Request\HTTPRequest as Request;

abstract class DataInCookie extends Instanciable {
	
	public const 
		OPTION_PATH = 'path',
		OPTION_DOMAIN = 'domain',
		OPTION_SECURE = 'secure',
		OPTION_HTTP_ONLY = 'httponly',
		OPTION_SAME_SITE = 'samesite',
		/***/
		PATH_ROOT = 'path-root',
		SECURE_FROM_REQUEST = 'from-request',
		DOMAIN_CURRENT = 'current',
		/***/
		SAME_SITE_NONE = 'None',
		SAME_SITE_LAX = 'Lax',
		SAME_SITE_STRICT = 'Strict'
	;
	
	protected const CONFIGURATION_PATH = '';
	
	/*****************************************************************/
	
	/**
	 * Options par défaut
	 * @var array
	 */
	private ?array $_default_options = NULL;
	
	/*****************************************************************/
	
	/**
	 * Retourne les options de base
	 * @return array
	 */
	protected function _baseOptionsCookie() : array
	{
		return [
			self::OPTION_PATH => '',
			self::OPTION_DOMAIN => '',
			self::OPTION_SECURE => FALSE,
			self::OPTION_HTTP_ONLY => FALSE,
		];
	}
	
	/**
	 * Retourne la configuration par défaut validée
	 * @return array
	 */
	protected function _defaultOptionsCookie(string $name = Config::DEFAULT) : array
	{
		if($this->_default_options === NULL)
		{
			$configurationPath = static::CONFIGURATION_PATH . '.' . $name;
			$config = (getConfig($configurationPath) ?? []);
			
			$options = array_merge($this->_baseOptionsCookie(), $config);
			$this->_formatOptionsCookie($options);
			$this->_validOptionsCookie($options);
			
			$this->_default_options = $options;
		}
		return $this->_default_options;
	}
	
	/**
	 * Formate les options
	 * @param array $options
	 * @return void
	 */
	protected function _formatOptionsCookie(array &$options) : void
	{
		$path = Arr::get($options, self::OPTION_PATH);
		if($path === self::PATH_ROOT)
		{
			$options[self::OPTION_PATH] = URL::root();
		}
		
		$secure = Arr::get($options, self::OPTION_SECURE, FALSE);
		if($secure === self::SECURE_FROM_REQUEST)
		{
			$options[self::OPTION_SECURE] = Request::current()->isSecure();
		}
		
		$domain = Arr::get($options, self::OPTION_DOMAIN);
		if($domain === self::DOMAIN_CURRENT)
		{
			$options[self::OPTION_DOMAIN] = Arr::get($_SERVER, 'SERVER_NAME', '');
		}
	}
	
	/*****************************************************************/
	
	/**
	 * Retourne les règles de validation des options cookies
	 * @return array
	 */
	protected function _validationOptionsCookieRules() : array
	{
		$allowedSameSite = [ self::SAME_SITE_NONE, self::SAME_SITE_LAX, self::SAME_SITE_STRICT, ];
		
		return [
			self::OPTION_PATH => [
				array('string'),
			],
			self::OPTION_DOMAIN => [
				array('http_domain'),
			],
			self::OPTION_SECURE => [
				array('boolean'),
			],
			self::OPTION_HTTP_ONLY => [
				array('boolean'),
			],
			self::OPTION_SAME_SITE => [
				array('in_array', [ 'array' => $allowedSameSite, ]),
			],
		];
	}
	
	/**
	 * Validation des options pour la création des cookies
	 * @param array $options
	 * @return void
	 */
	protected function _validOptionsCookie(array $options) : void
	{
		$validation = new Validation([
			'data' => $options,
			'rules' => $this->_validationOptionsCookieRules(),
		]);
		
		$validation->validate();
		
		if(! $validation->success())
		{
			exception('Options des cookies incorrectes.');
		}
		
	}
	
	/*****************************************************************/
	
	/**
	 * Crypte le nom d'un cookie
	 * @param string $name
	 * @return string
	 */
	protected function _cryptCookieName(string $name) : string
	{
		return hash_hmac('sha256', $name, Application::instance()->getKey());
	}
	
	/*****************************************************************/

	/**
	 * Récupére la valeur d'une donnée
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	abstract public function get(string $key, $default = NULL);

	/**
	 * Enregistre une valeur
	 * @param string $key
	 * @param mixed $value
	 * @param array $options : array(
	 * 		'path' => <string>, // Chemin où le cookie est accessible
	 * 		'domain' => <string>, // Domaine pour lequel le cookie est accessible
	 * 		'secure' => <bool>, // Cookie transmit en HTTPS seulement
	 * 		'httponly' => <bool>, // Accessible en HTTP seulement
	 * )
	 * @return bool
	 */
	abstract public function set(string $key, $value, array $options = []) : bool;

	/**
	 * Supprime une donnée
	 * @param string $key
	 * @return bool
	 */
	abstract public function delete(string $key) : bool;

	/*****************************************************************/
	
}