<?php

/**
 * Gestion d'une route abstraite : HTTP ou ligne de commande
 */

namespace Root\Route;

abstract class AbstractRoute {
	
	/**
	 * Retourne les paramètres de la requête
	 * @var array
	 */
	protected array $_parameters = [];
	
	/**
	 * Route de la requête courante
	 * @var self
	 */
	private static ?self $_current;
	
	/**
	 * Vrai si la requête courante a été initialisé
	 * @var bool
	 */
	private static bool $_current_initialialized = FALSE;
	
	/********************************************************************************/
	
	/**
	 * Retourne la requête courante
	 * @return self
	 */
	public static function current() : ?self
	{
		if(! self::$_current_initialialized)
		{
			self::$_current_initialialized = TRUE;
			self::$_current = static::_retrieveCurrent();
		}
		return self::$_current;
	}
	
	/**
	 * Recherche la requête courante
	 * @return self
	 */
	abstract protected static function _retrieveCurrent() : ?self;
	
	/**
	 * Affecte les paramètres de la route
	 * @return void
	 */
	abstract protected function _retrieveParameters() : array;
	
	/********************************************************************************/
	
	/* GET */
	
	/**
	 * Retourne l'identifiant de la route, utilisé pour les logs
	 * @return string
	 */
	abstract public function identifier() : string;
	
	/**
	 * Retourne l'identifiant de la route actuelle
	 * @return string
	 */
	public static function currentIdentifier() : ?string
	{
		return (isset(self::$_current) ? self::$_current->identifier() : NULL);
	}
	
	/**
	 * Retourne les paramètres de la route
	 * @return array
	 */
	public function parameters() : array
	{
		return $this->_parameters;
	}
	
	/********************************************************************************/
	
}