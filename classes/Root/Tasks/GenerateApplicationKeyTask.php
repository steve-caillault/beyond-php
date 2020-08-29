<?php

/**
 * Tâche permettant de re générer la clé de l'application
 * php cli generate-application-key
 */

namespace Root\Tasks;

use Root\Task;
use Root\Environment\Environment;

class GenerateApplicationKeyTask extends Task {
	
	/**
	 * Identifiant de la tâche
	 * @var string
	 */
	protected string $_identifier = 'generate-application-key';
	
	/*******************************************************/
	
	/**
	 * Exécute la tâche
	 * @return void
	 */
	protected function _execute() : void
	{
		$success = Environment::instance()->generateApplicationKey();
		$this->_response = ($success) ? 'La clé de l\'application a été modifié.' : 'La clé de l\'application n\'a pas été modifié.';
	}
	
	/*******************************************************/
	
}