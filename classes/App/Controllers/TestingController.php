<?php

/**
 * Contrôleur de test
 */

namespace App\Controllers;

use Root\{ Response };
use Root\Controllers\Controller;
use Root\Environment\Environment;

class TestingController extends Controller {
	
	public function before() : void
	{
		if(environment() != Environment::DEVELOPMENT)
		{
			exception(404);
		}
	}

	public function index() : void
	{
		$this->_response = new Response('Content');
	}
	
}