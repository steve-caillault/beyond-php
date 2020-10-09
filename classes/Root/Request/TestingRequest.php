<?php

/**
 * Gestion de l'appel d'un test en CLI
 */

namespace Root\Request;

use Root\{ Response, Test };
use Root\Route\TestingRoute as Route;

class TestingRequest extends AbstractRequest {
	
	protected const ROUTE_CLASS = Route::class;
	
	/**
	 * Test affecté à la requête
	 * @var Test
	 */
	protected Test $_test;
	
	/********************************************************************************/
	
	/**
	 * Retourne le test affecté à la requête
	 * @return Test
	 */
	public function test() : Test
	{
		return $this->_test;
	}
	
	/********************************************************************************/
	
	/**
	 * Réponse de la requête
	 * @return Response
	 */
	public function response() : ?Response
	{
		// Vérifie si le test existe
		$testClass = $this->_route->test();
		if(! class_exists($testClass))
		{
			exception(strtr('Le test :name n\'existe pas', [
				':name' => $this->_route->identifier(),
			]));
		}
		
		// Vérifie si la méthode existe
		$method = $this->_route->method();
		if(! method_exists($testClass, $method))
		{
			exception(strtr('La méthode :name n\'existe pas', [
				':name' => $this->_route->identifier(),
			]));
		}
		
		$test = new $testClass;
		$test->request($this);
		$this->_test = $test;
		
		$test->before();
		$test->execute();
		$test->after();
		
		return $test->response();
	}
	
	/********************************************************************************/
	
}