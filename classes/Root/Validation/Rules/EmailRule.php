<?php

/**
 * Vérification qu'une valeur représente une adresse email
 */

namespace Root\Validation\Rules;

class EmailRule extends Rule {
	
	/**
	 * Message en cas d'erreur
	 * @var string
	 */
	protected string $_error_message = 'La valeur doit être une adresse email valide.';
	
	/********************************************************************************/
	
	/* VERIFICATION */
	
	/**
	 * Retourne si la valeur respecte la règle
	 * @return bool
	 */
	public function check() : bool
	{
		$value = $this->_getValue();
		/*$pattern = strtr('/^:sub(\.:sub)*\@:sub+\.[a-zA-Z]{2,10}$/D', [
			':sub' => '[^\@\.\ ]+'
		]);*/
		return filter_var($value, FILTER_VALIDATE_EMAIL);
	}
	
	/********************************************************************************/
	
}