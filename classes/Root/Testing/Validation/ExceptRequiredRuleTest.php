<?php

/**
 * Test en d'une règle en dehors de required
 */

namespace Root\Testing\Validation;

abstract class ExceptRequiredRuleTest extends RuleTest {
	
	/**
	 * Test pour une valeur vide, si la valeur n'est pas requise
	 * @return bool
	 */
	protected function _emptyValueWhereasNotRequiredTest() : bool
	{
		return $this->_isValid();
	}
	
	/**
	 * Test pour une valeur vide, si la valeur est requise
	 * @return bool
	 */
	protected function _emptyValueWhereasRequiredTest() : bool
	{
		$this->_validation->addFieldRule('value', 'required');
		return $this->_valueWithRuleError('required');
	}
	
}