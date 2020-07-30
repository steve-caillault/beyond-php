<?php

/**
 * Contrôleur racine
 */

namespace App\Controllers;

use Root\{ Controller, Response };

class HomeController extends Controller {
	
	public function index() : void
	{
		$this->_response = new Response('Home');
	}
	
}