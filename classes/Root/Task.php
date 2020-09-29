<?php

/**
 * Gestion d'une tâche appelée en ligne de commande
 */

namespace Root;

abstract class Task extends ExecutableRequest {
	
	/**
	 * Identifiant de la tâche
	 * @var string
	 */
	protected string $_identifier;

	/********************************************/
	
	/**
	 * Appel la tâche en paramètre
	 * @param string $class Classe de la tâche à appeler
	 * @param array $parameters Paramètres de la tâche
	 * @return void
	 */
	public static function call(string $class, array $parameters = []) : void
	{
		$identifier = (new ClassPHP($class))->getPropertyValue('_identifier');
		if($identifier === NULL)
		{
			exception('Tâche inconnue.');
		}
		
		$commandPattern = 'php task :identifier :parameters';
		
		$command = escapeshellcmd(trim(strtr($commandPattern, [
			':identifier' => $identifier,
			':parameters' => implode(' ', $parameters),
		])));
		
		$system = strtolower(php_uname('s'));
		$isWindowsSystem = (strpos($system, 'windows') !== FALSE);
		
		if($isWindowsSystem)
		{
			pclose(popen('start /B ' . $command, 'r'));
		}
		else
		{
			$command .= ' > /dev/null &';
			exec($command);
		}
	}
	
	/********************************************/
	
	/**
	 * Règles de validation des paramètres
	 * @return array
	 */
	protected function _validationParametersRules() : array
	{
		return [];
	}
	
	/**
	 * Validation des paramètres
	 * @return void
	 */
	protected function _validatationParameters() : void
	{
		$validation = new Validation([
			'data' => $this->request()->parameters(),
			'rules' => $this->_validationParametersRules(),
		]);
		
		$validation->validate();
		
		if(! $validation->success())
		{
			exception('Paramètres incorrects.');
		}
	}
	
	/********************************************/
	
	/**
	 * Réponse de la tâche
	 * @return mixed
	 */
	public function response()
	{
		$this->_validatationParameters();
		$this->execute();
		return $this->_response;
	}
	
	/********************************************/
	
}