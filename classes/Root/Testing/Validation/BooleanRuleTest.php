<?php

/**
 * Test sur la règle de validation boolean
 */

namespace Root\Testing\Validation;

class BooleanRuleTest extends ExceptRequiredRuleTest {
	
	protected const CURRENT_RULE = 'boolean';
	
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
	 * Test pour un entier qui n'est pas considéré comme un booléen
	 * @return bool
	 */
	protected function _integerValueTest() : bool
	{
		$this->_validation->setData([
			'value' => 32,
		]);
		return $this->_valueWithRuleError();
	}
	
	/**
	 * Test pour une chaine de caractères
	 * @return bool
	 */
	protected function _stringValueTest() : bool
	{
		$this->_validation->setData([
			'value' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
		]);
		return $this->_valueWithRuleError();
	}
	
	/**
	 * Test pour un tableau
	 * @return bool
	 */
	protected function _arrayValueTest() : bool
	{
		$this->_validation->setData([
			'value' => [ TRUE, FALSE ],
		]);
		return $this->_valueWithRuleError();
	}
	
	/**
	 * Test pour un objet
	 * @return bool
	 */
	protected function _objectValueTest() : bool
	{
		$this->_validation->setData([
			'value' => new class {},
		]);
		return $this->_valueWithRuleError();
	}
	
	/*********************************************************/
	
	/**
	 * Test pour la valeur TRUE
	 * @return bool
	 */
	protected function _trueValueTest() : bool
	{
		$this->_validation->setData([
			'value' => TRUE,
		]);
		return $this->_isValid();
	}
	
	/**
	 * Test pour la valeur FALSE
	 * @return bool
	 */
	protected function _falseValueTest() : bool
	{
		$this->_validation->setData([
			'value' => FALSE,
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
		$expectedMessage = 'La valeur doit être une valeur booléenne.';
		$this->_validation->setData([
			'value' => 0,
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
		$expectedMessage = 'La valeur est doit est vrai ou faux.';
		$this->_validation->setFileErrors('testing');
		$this->_validation->setData([
			'value' => 1,
		]);
		$this->_validation->validate();
		$error = $this->_validation->error('value');
		return ($error === $expectedMessage);
	}
	
	/*********************************************************/
	
}