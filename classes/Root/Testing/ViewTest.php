<?php

/**
 * Test sur les vues
 */

namespace Root\Testing;

use Root\{ Test, View };
use Root\Exceptions\View\NotFoundViewException;

class ViewTest extends Test {
	
	/**
	 * Test lorsque le fichier de la vue n'existe pas
	 * @return bool
	 */
	protected function _viewNotExistsTest() : bool
	{
		$success = FALSE;
		
		try {
			new View('not_found');
		} catch(\Exception $exception) {
			$success = ($exception instanceof NotFoundViewException);
		}
		
		return $success;
	}
	
	/**
	 * Test lorsque des valeurs n'ont pas été transmises, mais que la vue y fait référence
	 * @return bool
	 */
	protected function _callUnknownVarTest() : bool
	{
		$success = FALSE;
		
		try {
			(new View('testing/view'))->render();
		} catch(\Exception $exception) {
			$success = TRUE;
		}
		
		return $success;
	}
	
	/**
	 * Test la récupération de variables
	 * @return bool
	 */
	protected function _getVariablesTest() : bool
	{
		$data = [
			'string' => 'test',
			'integer' => 1,
			'float' => 12.2,
			'array' => array_combine(range(1, 10), range(10, 1, -1)),
		];
		
		$view = new View('testing/view', $data);
		
		foreach($data as $key => $value)
		{
			$viewValue = $view->getVar($key);
			if($viewValue != $value)
			{
				return FALSE;
			}
		}
		
		return TRUE;
	}
	
	/**
	 * Test la modification de variables
	 * @return bool
	 */
	protected function _setVariablesTest() : bool 
	{
		$view = new View('testing/view', [
			'value1' => 1,
			'value2' => 2,
		]);
		
		// Test la modification d'une variable
		$newValue = 'Valeur 2 modifiée';
		$view->setVar('value2', $newValue);
		$modified = $view->getVar('value2');
		if($modified != $newValue)
		{
			return FALSE;
		}
		
		// Test de la modification de plusieurs variable
		$data = [
			'value1' => 'Valeur 1 modifiée',
			'value3' => 'Valeur 3 ajoutée',
		];
		$dataExpected = [
			'value1' => 'Valeur 1 modifiée',
			'value2' => 'Valeur 2 modifiée',
			'value3' => 'Valeur 3 ajoutée',
		];
		$view->setVars($data);
		foreach($dataExpected as $key => $value)
		{
			$viewValue = $view->getVar($key);
			if($viewValue != $value)
			{
				return FALSE;
			}
		}
		
		return TRUE;
	}
	
	/**
	 * Test d'affichage de la vue sui fonctionne correctement
	 * @return bool
	 */
	protected function _renderSuccessTest() : bool
	{
		$view = new View('testing/view', [
			'message' => 'success',
		]);
		$render = $view->render();
		return ($render === 'success');
	}
}