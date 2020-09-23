<?php

/**
 * Gestion d'une la ligne de commande d'une tâche
 */

namespace Root\Route;

use Root\{ Arr, Directory, ClassPHP };

class TaskRoute extends CommandLine {
	
	/**
	 * Classe de la tâche correspondant à la route
	 * @var string
	 */
	private string $_task;
	
	/**
	 * Identifiant de la tâche
	 * @var string
	 */
	private string $_identifier;
	
	/************************************************************************/
	
	/**
	 * Constructeur
	 * @param array $params
	 */
	private function __construct(array $params)
	{
		$task = Arr::get($params, 'task');
		if($task === NULL)
		{
			exception('Nom de la tâche inconnue.');
		}
		$this->_task = $task;
		
		$identifier = Arr::get($params, 'identifier');
		if($identifier === NULL)
		{
			exception('Identifiant de la tâche inconnu.');
		}
		$this->_identifier = $identifier;
	}
	
	/************************************************************************/
	
	/**
	 * Recherche la requête courante
	 * @return self
	 */
	protected static function _retrieveCurrent() : ?self
	{
		global $argv;
		$expectedIdentifier = Arr::get($argv, 1);
		if($expectedIdentifier === NULL)
		{
			exception('Nom de la tâche manquante.', 404);
		}
		
		$files = array_merge(
			Directory::files('classes/Root/Tasks/'),
			Directory::files('classes/App/Tasks/')
		);
		
		foreach($files as $file)
		{
			$class = '\\' . strtr(rtrim(ltrim($file, 'classes/'), '.php'), [ '/' => '\\' ]);
			$taskClass = new ClassPHP($class);
			$identifier = $taskClass->getPropertyValue('_identifier');
			
			if($identifier != $expectedIdentifier)
			{
				continue;
			}
			
			$route = new static([
				'task' => $class,
				'identifier' => $identifier, 
			]);
			$route->_retrieveParameters();
			return $route;
		}
		
		return NULL;
	}
	
	/************************************************************************/
	
	/**
	 * Retourne le nom de la classe de la tâche
	 * @return string
	 */
	public function task() : string
	{
		return $this->_task;
	}
	
	/**
	 * Retourne l'identifiant de la route, utilisé pour les logs
	 * @return string
	 */
	public function identifier() : string
	{
		return $this->_identifier;
	}

	/************************************************************************/
	
}