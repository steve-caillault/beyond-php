<?php

/**
 * Vérification d'une règle de validation
 */

namespace Root\Validation\Rules;

use Root\Arr;

abstract class Rule {
	
	/**
	 * Valeur à vérifier
	 * @var mixed
	 */
	private $_value = NULL;
	
	/**
	 * Paramètre de la règle
	 * @var array
	 */
	private array $_parameters = [];
	
	/**
	 * Message en cas d'erreur
	 * @var string
	 */
	protected string $_error_message;
	
	/********************************************************************************/
	
	/* CONSTRUCTEUR /*
	
	/**
	 * Constructeur
	 * @param array $parameters : [
	 * 		'value' 		=> <mixed>, // Valeur à vérifier
	 * 		'parameters'	=> <array>, // Paramètres de la règle
	 * ]
	 */
	public function __construct(array $parameters = [])
	{
		$this->_value = Arr::get($parameters, 'value', $this->_value);
		$this->_parameters = Arr::get($parameters, 'parameters', $this->_parameters);
	}
	
	/********************************************************************************/
	
	/* VERIFICATION */
	
	/**
	 * Retourne si la valeur respecte la règle
	 * @return bool
	 */
	abstract public function check() : bool;
	
	/********************************************************************************/
	
	/* GET */
	
	/**
	 * Retourne le message d'erreur
	 * @return string
	 */
	public function errorMessage() : string
	{
		$keys = array_keys($this->_parameters);
		array_walk($keys, function(&$item, $key) {
			$item = ':' . $item;
		});

		$values = array_values($this->_parameters);
		array_walk($values, function(&$item, $key) {
			if(is_array($item))
			{
				try {
					$item = implode(', ', $item);
				} catch(\Exception $exception) {
					$item = json_encode($item);
				}
				
			}
			if(! is_object($item))
			{	
				$item = print_r($item, TRUE);
			}
			else
			{
				$item = NULL;
			}
		});
		
		$messageParameters = array_combine($keys, $values);
		$messageParameters[':value'] = print_r($this->_getValue(), TRUE);
		return strtr($this->_error_message, $messageParameters);
	}
	
	/**
	 * Modification du message d'erreur
	 * @param string $message
	 * @return void
	 */
	public function setMessage(string $message) : void
	{
		$this->_error_message = $message;
	}
	
	/**
	 * Retourne la valeur
	 * @return mixed
	 */
	protected function _getValue()
	{
		return $this->_value;
	}
	
	/**
	 * Retourne le paramètre de la règle dont on donne la clé en paramètre
	 * @param string $key
	 * @param mixed $defaultValue Valeur par défaut
	 * @return mixed
	 */
	protected function _getParameter(string $key, $defaultValue = NULL)
	{
		return Arr::get($this->_parameters, $key, $defaultValue);
	}
	
	/********************************************************************************/
	
}