<?php

/**
 * Test sur la règle de validation string
 */

namespace Root\Testing\Validation;

class StringRuleTest extends ExceptRequiredRuleTest {
	
	protected const CURRENT_RULE = 'string';
	
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
	 * La valeur est une chaine de caractères
	 * @return bool
	 */
	protected function _isStringTest() : bool
	{
		$this->_validation->setData([
			'value' => 'Pascale Fautrier',
		]);
		return $this->_isValid();
	}
	
	/**
	 * La valeur est un booléen
	 * @return bool
	 */
	protected function _isBooleanTest() : bool
	{
		$this->_validation->setData([
			'value' => FALSE,
		]);
		return $this->_valueWithRuleError();
	}
	
	/**
	 * La valeur est un tableau
	 * @return bool
	 */
	protected function _isArrayTest() : bool
	{
		$this->_validation->setData([
			'value' => [ 'Robert Louis Stevenson', ],
		]);
		return $this->_valueWithRuleError();
	}
	
	/**
	 * La valeur est un objet
	 * @return bool
	 */
	protected function _isObjectTest() : bool
	{
		$this->_validation->setData([
			'value' => new class {},
			]);
		return $this->_valueWithRuleError();
	}
	
	/**
	 * La valeur est un nombre décimal
	 * @return bool
	 */
	protected function _isDecimalTest() : bool
	{
		$this->_validation->setData([
			'value' => 12.5,
		]);
		return $this->_valueWithRuleError();
	}
	
	/**
	 * La valeur est un nombre entier
	 * @return bool
	 */
	protected function _isIntegerTest() : bool
	{
		$this->_validation->setData([
			'value' => 5,
		]);
		return $this->_valueWithRuleError();
	}
	
	
	/*********************************************************/
	
	/* MESSAGES D'ERREUR */
	
	/**
	 * Test du message d'erreur par défaut
	 * @return bool
	 */
	protected function _defaultMessageTest() : bool
	{
		$expectedMessage = 'La valeur doit être une chaine de caractères.';
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
		$expectedMessage = 'La valeur n\'est pas une chaine de caractères.';
		$this->_validation->setFileErrors('testing');
		$this->_validation->setData([
			'value' => 56,
		]);
		$this->_validation->validate();
		$error = $this->_validation->error('value');
		return ($error === $expectedMessage);
	}
	
	/*********************************************************/
	
}