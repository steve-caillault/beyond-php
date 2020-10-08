<?php

/**
 * Vérification qu'une valeur a une valeur maximale
 */

namespace Root\Validation\Rules;

use Root\Exceptions\Validation\Rules\ParameterException;

class MaxRule extends Rule {
	
	/**
	 * Message en cas d'erreur
	 * @var string
	 */
	protected string $_error_message = 'La valeur doit être inférieure ou égale à :max.';
	
	/********************************************************************************/
	
	/* VERIFICATION */
	
	/**
	 * Retourne si la valeur respecte la règle
	 * @return bool
	 */
	public function check() : bool
	{
		$value = $this->_getValue();
		$maximum = $this->_getParameter('max');
		
		if(! is_numeric($maximum))
		{
			throw new ParameterException('Le maximum doit être une valeur numérique.');
		}
		
		if(! is_numeric($value))
		{
			$this->_error_message = 'La valeur doit être une valeur numérique.';
			return FALSE;	
		}
		
		return ($value <= $maximum);
	}
	
	/********************************************************************************/
	
}