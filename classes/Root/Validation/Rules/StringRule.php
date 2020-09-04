<?php

/**
 * Vérification qu'une valeur représente une chaine de caractères
 */

namespace Root\Validation\Rules;

class StringRule extends Rule {
	
	/**
	 * Message en cas d'erreur
	 * @var string
	 */
	protected string $_error_message = 'La valeur doit être une chaine de caractères.';
	
	/********************************************************************************/
	
	/* VERIFICATION */
	
	/**
	 * Retourne si la valeur respecte la règle
	 * @return bool
	 */
	public function check() : bool
	{
		return is_string($this->_getValue());
	}
	
}