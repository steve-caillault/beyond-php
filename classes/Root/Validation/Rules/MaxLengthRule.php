<?php

/**
 * Vérification qu'une valeur a une longueur maximale
 */

namespace Root\Validation\Rules;

use Root\Exceptions\Validation\Rules\ParameterException;

class MaxLengthRule extends Rule {
	
	/**
	 * Message en cas d'erreur
	 * @var string
	 */
	protected string $_error_message = 'La valeur doit avoir au plus :max caractères.';
	
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
		
		if(! is_numeric($maximum) OR $maximum < 1)
		{
			throw new ParameterException('Le maximum doit être un entier positif.');
		}
		
		if(! is_string($value))
		{
			$this->_error_message = 'La valeur doit être une chaine de caractères.';
			return FALSE;
		}
		
		$length = mb_strlen($value);
		return ($length <= ((int) $maximum));
	}
	
	/********************************************************************************/
	
}