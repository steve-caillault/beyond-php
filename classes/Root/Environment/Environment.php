<?php

/**
 * Gestion de l'environnement
 */

namespace Root\Environment;

use Root\{ Instanciable, Application };

final class Environment extends Instanciable {
	
	public const 
		DEVELOPMENT = 'DEVELOPMENT',
		TESTING = 'TESTING',
		DEMO = 'DEMO',
		PRODUCTION = 'PRODUCTION'
	;
	/***/
	public const DEFAULT = self::DEVELOPMENT;
	
	/**
	 * Tableau des environnements autorisés
	 * @var array
	 */
	private const ENVIRONMENTS = [
		self::DEVELOPMENT, self::TESTING, self::DEMO, self::PRODUCTION,
	];
	
	/**
	 * Fichier d'environnement
	 * @var EnvironmentFile
	 */
	private ?EnvironmentFile $_file = NULL;
	
	/**
	 * Nom de l'environement
	 * @var string
	 */
	private ?string $_name = NULL;
	
	/**
	 * Vrai si le site est en maintenance
	 * @var bool
	 */
	private ?bool $_maintenance = NULL;
	
	/**
	 * Clé de l'application
	 * @var string
	 */
	private ?string $_application_key = NULL;
	
	/****************************************************************/
	
	/* GET */
	
	/**
	 * Retourne le fichier d'environnement
	 * @return EnvironmentFile
	 */
	private function _file() : EnvironmentFile
	{
		if($this->_file === NULL)
		{
			$this->_file = EnvironmentFile::instance();
		}
		return $this->_file;
	}
	
	/**
	 * Retourne si le site est en maintenance
	 * @return bool
	 */
	public function inMaintenance() : bool
	{
		if($this->_maintenance === NULL)
		{
			$value = $this->_file()->getValue(EnvironmentFile::KEY_MAINTENANCE, FALSE);
			$this->_maintenance = ($value == 1);
		}
		return $this->_maintenance;
	}
	
	/**
	 * Détection de l'environnement du site
	 * @return string
	 */
	public function getName() : string
	{
		if($this->_name === NULL)
		{
			$name = $this->_file()->getValue(EnvironmentFile::KEY_NAME);	
			if(! in_array($name, self::ENVIRONMENTS))
			{
				exception('Environnement incorrect.');
			}
			$this->_name = $name;
		}
		return $this->_name;
	}
	
	/**
	 * Retourne la clé de l'application
	 * @return string
	 */
	public function getApplicationKey() : string
	{
		if($this->_application_key === NULL)
		{
			$this->_application_key = $this->_file()->getValue(EnvironmentFile::KEY_APPLICATION_KEY, Application::instance()->generateKey());
		}
		return $this->_application_key;
	}
	
	/****************************************************************/
	
	/**
	 * Met le site en maintenance, ou réactive le site
	 * @param bool $maintenance Vrai si le site doit être mis en maintenance, faux s'il faut réactiver le site
	 * @return bool
	 */
	public function maintenance(bool $maintenance) : bool
	{
		$newValue = ($maintenance) ? 1 : 0;
		$updated = $this->_file()->changeValue(EnvironmentFile::KEY_MAINTENANCE, $newValue);

		if($updated)
		{
			$this->_maintenance = $updated;
		}
		
		return $updated;
	}
	
	/**
	 * Modification de l'environnement
	 * @param string $environment
	 * @return bool
	 */
	public function change(string $environment) : bool
	{
		if(! in_array($environment, self::ENVIRONMENTS))
		{
			exception('Environnement incorrect.');
		}
		
		return $this->_file()->changeValue(EnvironmentFile::KEY_NAME, $environment);
	}
	
	/****************************************************************/
	
}
