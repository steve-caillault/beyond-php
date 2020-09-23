<?php

/**
 * Gestion d'un contrôleur
 */

namespace Root\Controllers;

use Root\ExecutableRequest;

abstract class Controller extends ExecutableRequest {
		
	/**
	 * Méthode à éxécuter avant la méthode principale du contrôleur
	 * @return void
	 */
	public function before() : void
	{
	    // Rien de particulier
	}
	
	/**
	 * Méthode à éxécuter après la méthode principale du contrôleur
	 * @return void
	 */
	public function after() : void
	{
	    // Rien de particulier
	}
	
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