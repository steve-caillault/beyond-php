<?php

/**
 * Tâche permettant de mettre le site en maintenance ou de réactiver le site
 * php cli environment name
 */

namespace Root\Tasks;

use Root\{ Task, Arr };
use Root\Environment\Environment;

class MaintenanceTask extends Task {
	
	/**
	 * Identifiant de la tâche
	 * @var string
	 */
	protected string $_identifier = 'maintenance';
	
	/*******************************************************/
	
	/**
	 * Règles de validation des paramètres
	 * @return array
	 */
	protected function _validationParametersRules() : array
	{
		return [
			[
				array('required'),
				array('in_array', [ 'array' => array('0', '1'), ]),
			],
		];
	}
	
	/**
	 * Exécute la tâche
	 * @return void
	 */
	protected function _execute() : void
	{
		$maintenance = (Arr::get($this->parameters(), 0) == 1);
		$success = Environment::instance()->maintenance($maintenance);
		
		if($success)
		{
			$message = ($maintenance) ? 'Le site a été mis en maintenance.' : 'Le site a été réactivé.';
		}
		else
		{
			$message = ($maintenance) ? 'Le site n\'a pas pu être mis en maintenance.' : 'Le site n\'a pas pu être réactivé.';
		}
		
		$this->_response = $message;
	}
	
	/*******************************************************/
	
	
}