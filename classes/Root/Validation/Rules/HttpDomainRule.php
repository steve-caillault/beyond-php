<?php

/**
 * Vérification qu'une valeur représente un domaine HTTP
 */

namespace Root\Validation\Rules;

class HttpDomainRule extends Rule {
	
	/**
	 * Message en cas d'erreur
	 * @var string
	 */
	protected string $_error_message = 'La valeur doit être un domaine HTTP.';
	
	/********************************************************************************/
	
	/* VERIFICATION */
	
	/**
	 * Retourne si la valeur respecte la règle
	 * @return bool
	 */
	public function check() : bool
	{
		$value = $this->_getValue();
		// $pattern = '/^[^\.]+(\.[^\.]+)*\.[a-zA-Z]{2,10}$/D';
		return (filter_var($value, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME) !== FALSE);
	}
	
	/********************************************************************************/
	
}