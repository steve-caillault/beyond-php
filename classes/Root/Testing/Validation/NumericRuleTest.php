<?php

/**
 * Test sur la règle de validation numeric
 */

namespace Root\Testing\Validation;

class NumericRuleTest extends WithParametersRuleTest {
	
	protected const CURRENT_RULE = 'numeric';
	
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
		return $this->_valueWithRuleError();
	}
	
	/**
	 * La valeur est un booléen
	 * @return bool
	 */
	protected function _isBooleanTest() : bool
	{
		$this->_validation->setData([
			'value' => TRUE,
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
			'value' => [ 'Philippe Lançon', ],
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
		return $this->_isValid();
	}
	
	/**
	 * La valeur est un nombre entier
	 * @return bool
	 */
	protected function _isInteger() : bool
	{
		$this->_validation->setData([
			'value' => 5,
		]);
		return $this->_isValid();
	}
	
	/**
	 * La valeur est un nombre négatif
	 * @return bool
	 */
	protected function _isNegative() : bool
	{
		$this->_validation->setData([
			'value' => -16,
		]);
		return $this->_isValid();
	}
	
	/**
	 * La valeur est égale à 0
	 * @return bool
	 */
	protected function _isZero() : bool
	{
		$this->_validation->setData([
			'value' => 0,
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
		$expectedMessage = 'La valeur doit être une valeur numérique.';
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
		$expectedMessage = 'La valeur doit être un nombre.';
		$this->_validation->setFileErrors('testing');
		$this->_validation->setData([
			'value' => 'test',
		]);
		$this->_validation->validate();
		$error = $this->_validation->error('value');
		return ($error === $expectedMessage);
	}
	
	/*********************************************************/
	
}