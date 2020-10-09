<?php

/**
 * Appel tous les tests
 */

namespace Root\Testing;

use Root\Test;
use Root\Route\TestingRoute as Route;
use Root\Request\TestingRequest as Request;

class AllTest extends Test {
	
	/**
	 * Appel tous les tests
	 * @return bool
	 */
	protected function _indexTest() : bool
	{
		$success = TRUE;
		$currentClass = self::class;
		
		$routeClasses = array_filter(Route::routeClasses(), function($class) use($currentClass) {
			$allowed = (trim($class, '\\') != trim($currentClass, '\\'));
			$reflectionClass = new \ReflectionClass($class);
			$isInstantiable = $reflectionClass->isInstantiable();
			return ($allowed AND $isInstantiable);
		});
		
		foreach($routeClasses as $routeClass)
		{
			$response = $this->_testResponse($routeClass);
			if(! $response)
			{
				$success = FALSE;
			}
		}
		
		return $success;
	}
	
	/**
	 * Appel le test dont la classe est en paramètre
	 * @param string $routeClass
	 * @return bool
	 */
	private function _testResponse(string $routeClass) : bool
	{
		$name = strtr($routeClass, [
			'\App\Testing\\' => '',
			'\Root\Testing\\' => '',
		]);
		
		$route = new Route([
			'test' => $routeClass,
			'name' => $name,
		]);
		
		$request = new Request([
			'route' => $route,
		]);
		
		$message = (string) $request->response();
		$test = $request->test();
		$success = $test->success();
		$this->addMessage($message);
		
		return $success;
	}
	
}