<?php

/**
 * Vérification qu'une valeur a une longueur minimale
 */

namespace Root\Validation\Rules;

use Root\Exceptions\Validation\Rules\ParameterException;

class MinLengthRule extends Rule {
	
	/**
	 * Message en cas d'erreur
	 * @var string
	 */
	protected string $_error_message = 'La valeur doit avoir au moins :min caractères.';
	
	/********************************************************************************/
	
	/* VERIFICATION */
	
	/**
	 * Retourne si la valeur respecte la règle
	 * @return bool
	 */
	public function check() : bool
	{
		$value = $this->_getValue();
		$minimum = $this->_getParameter('min');
	
		if(! is_numeric($minimum) OR $minimum < 1)
		{
			throw new ParameterException('Le minimum doit être un entier positif.');
		}
		
		if(! is_string($value))
		{
			$this->_error_message = 'La valeur doit être une chaine de caractères.';
			return FALSE;
		}

		$length = mb_strlen($value);
		return ($length >= ((int) $minimum));
	}
	
	/********************************************************************************/
	
}