<?php

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