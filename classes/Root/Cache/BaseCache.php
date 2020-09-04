<?php

/**
 * Classe de base pour les gestionnaires de cache
 */

namespace Root\Cache;

use Root\Manager\BaseManager;

abstract class BaseCache extends BaseManager {
	
	/**
	 * Données en cache
	 * @var array
	 */
	protected array $_data = [];
	
	/**
	 * Prefixe des clés
	 * @var string
	 */
	private string $_prefix_key;
	
	/**********************************************************/
	
	/**
	 * Constructeur
	 * @param array $configuration
	 */
	protected function __construct(array $configuration)
	{
		$this->_prefix_key = getArray($configuration, 'prefix_key', '');
	}
	
	/**********************************************************/
	
	/**
	 * Retourne la clé dans Memcache
	 * @param string $key
	 * @return string
	 */
	protected function _getKey(string $key) : string
	{
		return ($this->_prefix_key . $key);
	}
	
	/**
	 * Retourne les données du cache dont la clé est en paramètre
	 * @param string $key Clé pour identifier le cache
	 * @param mixed $default Valeur à retourner par défaut
	 * @return mixed
	 */
	abstract public function get(string $key, $default = NULL);
	
	/**
	 * Met les données en cache
	 * @param string $key Clé pour identifier le cache
	 * @param mixed $data Données à mettre en cache
	 * @param int $lifetime Durée de vie en seconde des données
	 * @return bool
	 */
	abstract public function set(string $key, $data, int $lifetime) : bool;
	
	/**
	 * Supprime une valeur du cache
	 * @param string $key
	 * @return bool
	 */
	abstract public function delete(string $key) : bool;
	
	/**********************************************************/
	
}