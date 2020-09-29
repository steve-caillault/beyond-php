<?php

/**
 * Gestion de la ligne de commande d'un test
 */

namespace Root\Route;

use Root\{ Arr, Directory };

class TestingRoute extends CommandLine {
	
	/**
	 * Classe du test
	 * @var string
	 */
	private string $_test;
	
	/**
	 * Nom du test
	 * @var string
	 */
	private string $_name;
	
	/**
	 * Nom de la méthode
	 * @var string
	 */
	private string $_method = '_executeAll';
	
	/************************************************************************/
	
	/**
	 * Constructeur
	 * @param array $params
	 */
	private function __construct(array $params)
	{
		$test = Arr::get($params, 'test');
		if($test === NULL)
		{
			exception('Nom du test inconnu.');
		}
		$this->_test = $test;
		
		$name = Arr::get($params, 'name');
		if($name === NULL)
		{
			exception('Nom du test inconnu.');
		}
		$this->_name = $name;
		
		$this->_method = Arr::get($params, 'method', $this->_method);
	}
	
	/************************************************************************/
	
	/**
	 * Recherche la requête courante
	 * @return self
	 */
	protected static function _retrieveCurrent() : ?self
	{
		global $argv;
		$parameter = strtr((Arr::get($argv, 1)), [
			'/' => '\\',
			';' => '::',
		]);
		
		if($parameter === NULL)
		{
			exception('Nom du test manquant.', 404);
		}
		
		
		// Récupération de la méthode du test à appeler
		$methodPosition = strpos($parameter, '::');
		$method = ($methodPosition !== FALSE) ? ltrim(substr($parameter, $methodPosition), '::') : NULL;
		
		$expectedName = ($methodPosition !== FALSE) ? substr($parameter, 0, $methodPosition) : $parameter;
		
	
		$files = array_merge(
			Directory::files('classes/Root/Testing/'),
			Directory::files('classes/App/Testing/')
		);
		
		// Fonction retournant le nom du test à partir de sa classe
		$getName = function($class) {
			$directories = [ '\App\Testing\\', '\Root\Testing\\', ];
			$name = $class;
			foreach($directories as $directory)
			{
				$name = ltrim($name, $directory);
			}
			return $name;
		};
		
		foreach($files as $file)
		{
			$class = '\\' . strtr(rtrim(ltrim($file, 'classes/'), '.php'), [ '/' => '\\' ]);
			
			$name = $getName($class);
			if($name != $expectedName)
			{
				continue;
			}
			
			return new static([
				'test' => $class,
				'name' => $name,
				'method' => $method,
			]);
		}
		
		return NULL;
	}
	
	/************************************************************************/
	
	/**
	 * Retourne le nom de la classe du test
	 * @return string
	 */
	public function test() : string
	{
		return $this->_test;
	}
	
	/**
	 * Retourne le nom du test
	 * @var string
	 */
	public function name() : string
	{
		return $this->_name;
	}
	
	/**
	 * Retourne la méthode à exécuter
	 * @return string
	 */
	public function method() : string
	{
		return $this->_method;
	}
	
	/**
	 * Retourne l'identifiant de la route, utilisé pour les logs
	 * @return string
	 */
	public function identifier() : string
	{
		return implode('::', [
			$this->_name, $this->_method,
		]);
	}
	
	/************************************************************************/
	
}