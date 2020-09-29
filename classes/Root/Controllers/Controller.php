<?php

/**
 * Gestion d'un contrôleur
 */

namespace Root\Controllers;

use Root\ExecutableRequest;

abstract class Controller extends ExecutableRequest {
	
	/**
	 * Exécute la méthode principale du contrôleur
	 * @return void
	 */
	public function execute() : void
	{
		$method = $this->_request->route()->method();
		$this->{ $method }();
	}
	
	/********************************************************************************/
	
}