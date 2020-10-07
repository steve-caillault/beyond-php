<?php

/**
 * Test sur la règle de validation email
 */

namespace Root\Testing\Validation;

class EmailRuleTest extends ExceptRequiredRuleTest {
	
	protected const CURRENT_RULE = 'email';
	
	/**
	 * Règles de validation
	 * @var array
	 */
	protected array $_rules = [
		'value' => [
			array(self::CURRENT_RULE),
		],
	];
	
	/*********************************************************/
	
	/**
	 * Adresse email invalide
	 * @return bool
	 */
	protected function _invalidEmailTest() : bool
	{
		$this->_validation->setData([
			'value' => 'pomme.poire',
		]);
		return $this->_valueWithRuleError();
	}
	
	/**
	 * Adresse email valide
	 * @return bool
	 */
	protected function _validEmailTest() : bool
	{
		$this->_validation->setData([
			'value' => 'ernest.hemingway@litterae.info',
		]);
		return $this->_isValid();
	}
	
	/*********************************************************/
	
	/* MESSAGES D'ERREUR */
	
	/**
	 * Test du message d'erreur par défaut
	 * @return bool
	 */
	protected function _defaultMessageTest() : bool
	{
		$expectedMessage = 'La valeur doit être une adresse email valide.';
		$this->_validation->setData([
			'value' => FALSE,
		]);
		$this->_validation->validate();
		$error = $this->_validation->error('value');
		return ($error === $expectedMessage);
	}
	
	/**
	 * Test du message d'erreur dans un fichier
	 * @return bool
	 */
	protected function _fileMessageTest() : bool
	{
		$expectedMessage = 'L\'adresse email n\'est pas valide.';
		$this->_validation->setFileErrors('testing');
		$this->_validation->setData([
			'value' => 1.6,
		]);
		$this->_validation->validate();
		$error = $this->_validation->error('value');
		return ($error === $expectedMessage);
	}
	
	/*********************************************************/
	
}