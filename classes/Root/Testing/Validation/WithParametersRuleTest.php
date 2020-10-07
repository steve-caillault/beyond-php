<?php

/**
 * Test sur la règle de validation in_array
 */

namespace Root\Testing\Validation;

use Root\Exceptions\Validation\Rules\ParameterException;

abstract class WithParametersRuleTest extends ExceptRequiredRuleTest {
	
	/**
	 * Test que les paramètres de la règle sont incorrects
	 * @return bool
	 */
	protected function _checkIncorrectRuleParameters() : bool
	{
		$success = FALSE;
		try {
			$this->_validation->validate();
		} catch(\Exception $exception) {
			$success = ($exception instanceof ParameterException);
		}
		return $success;
	}
	
}