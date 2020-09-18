<?php

/**
 * Gestion de données en cache en fichier texte
 */

namespace Root\Cache;

use Root\Arr;

class FileCache extends BaseCache {
	
	public const TYPE = 'file';
	
	private const DIRECTORY = 'resources/cache/';
	
	/****************************************************/
	
	/**
	 * Retourne le chemin du fichier de cache de la clé en paramètre
	 * @param string $key
	 * @return string
	 */
	private function _filePath(string $key) : string
	{
		$key = $this->_getKey($key);
		return (self::DIRECTORY . hash('sha256', $key));
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
		if(! array_key_exists($key, $this->_data))
		{
			$filePath = $this->_filePath($key);
			
			$data = $default;
			// Le fichier de cache n'existe pas
			if(file_exists($filePath))
			{
				$fileContent = @ file_get_contents($filePath);
				
				if($fileContent !== FALSE)
				{
					try {
						$fileData = unserialize($fileContent);
						
						$expireAt = Arr::get($fileData, 'expireAt', time() - 10);
						
			
						// Les données de cache ont expirés
						if(time() >= $expireAt)
						{
							@ unlink($filePath);
						}
						else
						{
							$data = Arr::get($fileData, 'data', $default);
						}
					} catch(\Exception $exception) {
						
					}
				}
		
			}
			
			$this->_data[$key] = $data;
		}
		
		return Arr::get($this->_data, $key);
	}
	
	/****************************************************/
	
	/**
	 * Met les données en cache
	 * @param string $key Clé pour identifier le cache
	 * @param mixed $data Données à mettre en cache
	 * @param int $lifetime Durée de vie en seconde des données
	 * @return bool
	 */
	public function set(string $key, $data, int $lifetime) : bool
	{
		$filePath = $this->_filePath($key);
		
		$fileCacheData = [
			'expireAt' => time() + $lifetime,
			'data' => $data,
		];
		
		try {
			$fileData = serialize($fileCacheData);
			$written = file_put_contents($filePath, $fileData, LOCK_EX);
			$updated = (is_int($written) AND $written > 0);
			$this->_data[$key] = $data;
		} catch(\Exception $exception) {
			$updated = FALSE;
		}
		
		return $updated;
	}
	
	/****************************************************/
	
	/**
	 * Supprime une valeur du cache
	 * @param string $key
	 * @return bool
	 */
	public function delete(string $key) : bool
	{
		$response = FALSE;
		$filePath = $this->_filePath($key);
		if(file_exists($filePath))
		{
			$response = (@ unlink($filePath));
		}
		unset($this->_data[$key]);
		return $response;
	}
	
}