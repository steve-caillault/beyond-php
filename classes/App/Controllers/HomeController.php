<?php

/**
 * Contrôleur racine
 */

namespace App\Controllers;

use Root\Controllers\HTMLController;

class HomeController extends HTMLController {
	
	/**
	 * Chemin de la vue de base à utiliser
	 * @var string
	 */
	protected string $_template_path = 'testing/view';
	
	public function index() : void
	{
		$this->_template->setVar('message', 'Home');
	}
	
}