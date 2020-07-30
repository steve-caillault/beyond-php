<?php

/**
 * Contrôleur de test
 */

namespace App\Controllers;

use Root\{ Controller, Response };

class TestingController extends Controller {
	
	public function index() : void
	{
		$this->_response = new Response('Content');
	}
	
}