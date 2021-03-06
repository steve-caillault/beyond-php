<?php

/**
 * Gestion de données en cache avec Memcache
 */

namespace Root\Cache;

use Memcache;
use Root\Arr;

class MemcacheCache extends BaseCache {
	
	public const TYPE = 'memcache';
	
	/**
	 * Connexion au serveur de cache
	 * @var Memcache
	 */
	private Memcache $_memcache;
	
	/****************************************************/
	
	/**
	 * Constructeur
	 * @param array $configuration
	 */
	protected function __construct(array $configuration)
	{
		$connection = Arr::get($configuration, 'connection');
		
		// Récupération des paramètres de connexion
		$host = Arr::get($connection, 'host');
		$port = Arr::get($connection, 'port');
		if($host === NULL)
		{
			exception('Hôte inconnu.');
		}
		if($port === NULL)
		{
			exception('Port inconnu.');
		}
		
		// Tentative de connexion
		$memcache = new Memcache;
		$connected = @ $memcache->connect($host, $port);
		if(! $connected)
		{
			exception('Impossible de se connecter à Memcached.');
		}
		
		$this->_memcache = $memcache;
		
		parent::__construct($configuration);
	}
	
	/****************************************************/
	
	/**
	 * Retourne les données du cache dont la clé est en paramètre
	 * @param string $key Clé pour identifier le cache
	 * @param mixed $default Valeur à retourner par défaut
	 * @return mixed
	 */
	public function get(string $key, $default = NULL)
	{
		$value = $this->_memcache->get($this->_getKey($key));
		
		if($value === FALSE)
		{
			return $default;
		}
		
		$this->_data[$key] = $value;
		
		return $value;
	}
	
	/**
	 * Met les données en cache
	 * @param string $key Clé pour identifier le cache
	 * @param mixed $data Données à mettre en cache
	 * @param int $lifetime Durée de vie en seconde des données
	 * @return bool
	 */
	public function set(string $key, $data, int $lifetime) : bool
	{
		$updated = $this->_memcache->set($this->_getKey($key), $data, MEMCACHE_COMPRESSED, $lifetime);
		
		$this->_data[$key] = $data;
		
		return $updated;
	}
	
	/**
	 * Supprime une valeur du cache
	 * @param string $key
	 * @return bool
	 */
	public function delete(string $key) : bool
	{
		$deleted = $this->_memcache->delete($this->_getKey($key));
		
		if($deleted)
		{
			unset($this->_data[$key]);
		}
		
		return $deleted;
	}
	
	/****************************************************/
	
}