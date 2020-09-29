<?php

/**
 * Test sur une règle de validation
 */

namespace Root\Testing\Validation;

use Root\{ Test, Validation };

abstract class RuleTest extends Test {
	
	protected const CURRENT_RULE = NULL;
	
	/**
	 * Données à valider
	 * @var array
	 */
	protected array $_data = [];
	
	/**
	 * Règles de validation
	 * @var array
	 */
	protected array $_rules = [];
	
	/**
	 * Objet Validation
	 * @var Validation
	 */
	protected Validation $_validation;
	
	/**************************************************/
	
	/**
	 * Méthode à exécuter avant la méthode principale
	 * @return void
	 */
	public function before() : void
	{
		$this->_validation = new Validation([
			'data' => $this->_data,
			'rules' => $this->_rules,
		]);
	}
	
	/**************************************************/
	
	/**
	 * Retourne si la valeur est valide
	 * @return bool
	 */
	protected function _isValid() : bool
	{
		$this->_validation->validate();
		$error = $this->_validation->error('value', Validation::ERROR_RULE);
		return ($error === NULL);
	}
	
	/**
	 * Retourne si la valeur à une erreur de la règle en paramètre
	 * @return bool
	 */
	protected function _valueWithRuleError(?string $rule = NULL) : bool
	{
		$rule ??= static::CURRENT_RULE;
		$this->_validation->validate();
		$error = $this->_validation->error('value', Validation::ERROR_RULE);
		return ($error !== NULL AND $error === $rule);
	}
	
	/**************************************************/
	
	/* MESSAGES D'ERREUR */
	
	/**
	 * Test du message d'erreur par défaut
	 * @return bool
	 */
	abstract protected function _defaultMessageTest() : bool;
	
	/**
	 * Test du message d'erreur dans un fichier
	 * @return bool
	 */
	abstract protected function _fileMessageTest() : bool;
	
	/**
	 * Test du message d'erreur lorsque le fichier d'erreur n'existe pas
	 * @return bool
	 */
	protected function _fileNotFoundMessageTest() : bool
	{
		$this->_validation->setFileErrors('beyond-php/not-found');
		return $this->_defaultMessageTest();
	}
	
	/**
	 * Test du message d'erreur lorsqu'il n'est pas présent dans le fichier
	 * @return bool
	 */
	protected function _fileMessageNotFoundTest() : bool
	{
		$this->_validation->setFileErrors('default');
		return $this->_defaultMessageTest();
	}
	
	/**************************************************/
	
}