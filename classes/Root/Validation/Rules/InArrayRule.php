<?php

/**
 * Vérification qu'une valeur est présent dans un tableau de valeur
 */

namespace Root\Validation\Rules;

use Root\Exceptions\Validation\Rules\ParameterException;

class InArrayRule extends Rule {
	
	/**
	 * Message en cas d'erreur
	 * @var string
	 */
	protected string $_error_message = 'La valeur doit être présente dans le tableau :array.';
	
	/********************************************************************************/
	
	/* VERIFICATION */
	
	/**
	 * Retourne si la valeur respecte la règle
	 * @return bool
	 */
	public function check() : bool
	{
		$value = $this->_getValue();
		$allowedValues = $this->_getParameter('array');
		if(! is_array($allowedValues) OR count($allowedValues) == 0)
		{
			throw new ParameterException('Le tableau de valeur est invalide ou vide.');
		}
		
		return in_array($value, $allowedValues);
	}
	
	/********************************************************************************/
	
}