<?php

/**
 * Gestion de données en cache avec APCu
 * @see https://www.php.net/manual/fr/book.apcu.php
 */

namespace Root\Cache;

class ApcuCache extends BaseCache {
	
	public const TYPE = 'apcu';
	
	/**********************************************************/
	
	/**
	 * Constructeur
	 * @param array $configuration
	 */
	protected function __construct(array $configuration)
	{
		if(! function_exists('apcu_enabled') OR ! apcu_enabled())
		{
			exception('APCu n\'est pas disponible.');
		}
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
		$apcuKey = $this->_getKey($key);
		$exists = apcu_exists($apcuKey);
		if(! $exists)
		{
			$data = $default;
		}
		else
		{
			$data = apcu_fetch($apcuKey);
		}
		
		$this->_data[$key] = $data;
		
		return $data;
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
		$apcuKey = $this->_getKey($key);
		$stored = apcu_store($apcuKey, $data, $lifetime);
		if($stored)
		{
			$this->_data[$key] = $data;
		}
		return $stored;
	}
	
	/**
	 * Supprime une valeur du cache
	 * @param string $key
	 * @return bool
	 */
	public function delete(string $key) : bool
	{
		$apcuKey = $this->_getKey($key);
		return (apcu_delete($apcuKey) === TRUE);
	}
	
	/****************************************************/
	
}