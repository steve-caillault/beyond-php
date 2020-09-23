<?php

/**
 * Gestion d'une requête HTTP
 */

namespace Root\Request;

use Root\{ Arr, Response, Instanciable };
use Root\Route\AbstractRoute as Route;

abstract class AbstractRequest extends Instanciable {
	
	protected const ROUTE_CLASS = NULL; 
	
	/**
	 * Route de la requête
	 * @var Route
	 */
	protected ?Route $_route = NULL;
	
	/**
	 * Requête courante
	 * @var self
	 */
	private static ?self $_current = NULL;
	
	/********************************************************************************/
	
	/* GET */
	
	/**
	 * Retourne / affecte la requête courante
	 * @param self $request Si renseigné, la requête a affecter
	 * @return self
	 */
	public static function current(?self $request = NULL) : self
	{
		if($request !== NULL)
		{
			self::$_current = $request;
		}
		elseif(self::$_current === NULL)
		{
			self::$_current = new static;
		}
		return self::$_current;
	}
	
	/**
	 * Constructeur
	 * @param array $params : array(
	 * 		'route': <AbstractRoute>, // Route de la requête
	 * )
	 */
	protected function __construct(array $params = [])
	{
		$routeClass = static::ROUTE_CLASS;
		$this->_route = Arr::get($params, 'route', $routeClass::current());
		if(! $this->_route)
		{
			exception('Route introuvable.', 404);
		}
	}
	
	/**
	 * Retourne les paramètres de la route
	 * @return array
	 */
	public function parameters() : array
	{
		return $this->_route->parameters();
	}
	
	/**
	 * Retourne le paramètre de la route dont la clé est en paramètre
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function parameter(string $key, $default = NULL)
	{
		return Arr::get($this->parameters(), $key, $default);
	}
	
	/**
	 * Retourne la route de la requête
	 * @return Route
	 */
	public function route() : Route
	{
		return $this->_route;
	}
	
	/**
	 * Retourne si la requête est appelé en ligne de commande
	 * @return bool
	 */
	public static function isCLI() : bool
	{
		return (strtolower(php_sapi_name()) == 'cli');
	}
	
	/********************************************************************************/
	
	/**
	 * Réponse de la requête
	 * @return string
	 */
	abstract public function response() : ?Response;
	
	/********************************************************************************/
		
}