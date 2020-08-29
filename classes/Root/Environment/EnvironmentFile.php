<?php

/**
 * Gestion du fichier d'environnement
 */

namespace Root\Environment;

use Root\{ Instanciable, Application };

final class EnvironmentFile extends Instanciable {
	
	public const
		KEY_NAME = 'name',
		KEY_MAINTENANCE = 'maintenance',
		KEY_APPLICATION_KEY = 'application-key'
	;
	
	private const FILEPATH = './.environment';
	
	/*****************************************************/
	
	/**
	 * Vrai si le fichier d'environnement existe
	 * @var bool
	 */
	private ?bool $_exists = NULL; 
	
	/**
	 * Données du fichier, ensemble de clé, valeur
	 * @var array
	 */
	private ?array $_data = NULL;
	
	/*****************************************************/
	
	/**
	 * Retourne si le fichier existe
	 * @return bool
	 */
	private function _exists() : bool
	{
		if($this->_exists === NULL)
		{
			$this->_exists = file_exists(self::FILEPATH);
		}
		return $this->_exists;
	}
	
	/*****************************************************/
	
	/**
	 * Création du fichier d'environnement
	 * @param array $data Données à écrire dans le fichier
	 * @return void
	 */
	private function _create(array $data) : bool
	{
		@ $file = fopen(self::FILEPATH, 'w');
		if(! $file)
		{
			return FALSE;
		}
		
		$environment = getArray($data, self::KEY_NAME);
		$maintenance = (int) getArray($data, self::KEY_MAINTENANCE);
		$applicationKey = getArray($data, self::KEY_APPLICATION_KEY);
		
		fwrite($file, self::KEY_NAME . '=' . $environment . PHP_EOL);
		fwrite($file, self::KEY_MAINTENANCE . '=' . $maintenance . PHP_EOL);
		fwrite($file, self::KEY_APPLICATION_KEY . '=' . $applicationKey);
		fclose($file);
		
		$this->_data = $data;
		
		return TRUE;
	}
	
	/**
	 * Change une valeur dans le fichier
	 * @param string $key
	 * @param string $value 
	 * @return bool
	 */
	public function changeValue(string $key, string $value) : bool
	{
		// Vérifie que la valeur à changer est différente de la valeur courante
		$currentValue = getArray($this->_data(), $key);
		if($currentValue == $value)
		{
			return TRUE;
		}
		
		$filepath = self::FILEPATH;
		
		$currentContent = file_get_contents($filepath);
		$currentLine = $key . '=' . $currentValue;
		$newLine = $key . '=' . $value;
		
		$newContent = strtr($currentContent, [
			$currentLine => $newLine,
		]);
		
		$written = file_put_contents($filepath, $newContent);
		
		return ($written > 0);
	}
	
	/*****************************************************/
	
	/**
	 * Chargement des données du fichier /.environment
	 * @return array
	 */
	private function _data() : array
	{
		if($this->_data === NULL)
		{
			$this->_data = [];
			$pattern = '/^[^\=]+\=[^\=]+$/D';
			
			// Création du fichier
			$fileExists = $this->_exists();
			if(! $fileExists)
			{
				$applicationKey = Application::instance()->generateKey();
				
				$this->_create([
					self::KEY_NAME => Environment::DEFAULT,
					self::KEY_MAINTENANCE => FALSE,
					self::KEY_APPLICATION_KEY => $applicationKey,
				]);
			}
			// Récupération des données dans le fichier
			else
			{
				$lines = explode(PHP_EOL, file_get_contents(self::FILEPATH));
				foreach($lines as $line)
				{
					$lineContent = trim($line);
					if(preg_match($pattern, $lineContent))
					{
						list($key, $value) = explode('=', $lineContent);
						$this->_data[$key] = $value;
					}
				}
			}
		}
		
		return $this->_data;
	}
	
	/*****************************************************/
	
	/**
	 * Retourne la valeur d'une données présente dans le fichier
	 * @param string $key
	 * @param string $default Valeur par defaut
	 * @return string
	 */
	public function getValue(string $key, ?string $default = NULL) : ?string
	{
		return getArray($this->_data(), $key, $default);
	}
	
	/*****************************************************/
	
}