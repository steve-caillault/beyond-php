<?php

/**
 * Test sur la règle de validation max_length
 */

namespace Root\Testing\Validation;

class MaxLengthRuleTest extends WithParametersRuleTest {
	
	protected const CURRENT_RULE = 'max_length';
	
	private const MAX =  5;
	
	/**
	 * Règles de validation
	 * @var array
	 */
	protected array $_rules = [
		'value' => [
			array(self::CURRENT_RULE, [
				'max' => self::MAX,
			]),
		],
	];
	
	/*********************************************************/
	
	/**
	 * Le paramètre max est inconnu
	 * @return bool
	 */
	protected function _missedMaxParameterTest() : bool
	{
		$this->_validation->setRules([
			'value' => [
				array(self::CURRENT_RULE),
			],
		]);
		$this->_validation->setData([
			'value' => 'Albert Camus',
		]);
		return $this->_checkIncorrectRuleParameters();
	}
	
	/**
	 * Le paramètre max n'est pas un entier
	 * @return bool
	 */
	protected function _maxParameterNotIntegerTest() : bool
	{
		$this->_validation->setRules([
			'value' => [
				array(self::CURRENT_RULE, [
					'max' => 'test',
				]),
			],
		]);
		$this->_validation->setData([
			'value' => 'Jane Austen',
		]);
		return $this->_checkIncorrectRuleParameters();
	}
	
	/**
	 * Le paramètre max n'est pas positif
	 * @return bool
	 */
	protected function _maxParameterNotPositiveTest() : bool
	{
		$this->_validation->setRules([
			'value' => [
				array(self::CURRENT_RULE, [
					'max' => -12,
				]),
			],
		]);
		$this->_validation->setData([
			'value' => 'Brad Watson',
		]);
		return $this->_checkIncorrectRuleParameters();
	}
	
	/**
	 * La valeur n'est pas une chaine de caractères
	 * @return bool
	 */
	protected function _isNotStringTest() : bool
	{
		$this->_validation->setData([
			'value' => 12,
		]);
		$this->_validation->validate();
		$expectedError = 'La valeur doit être une chaine de caractères.';
		$error = $this->_validation->error('value');
		return ($error === $expectedError);
	}
	
	/**
	 * La valeur est trop longue
	 * @return bool
	 */
	protected function _tooLongLengthTest() : bool
	{
		$this->_validation->setData([
			'value' => 'Emily Brontë',
		]);
		return $this->_valueWithRuleError();
	}
	
	/**
	 * La valeur est correct
	 * @return bool
	 */
	protected function _correctLengthTest() : bool
	{
		$this->_validation->setData([
			'value' => 'pomme',
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
		$expectedMessage = strtr('La valeur doit avoir au plus :max caractères.', [
			':max' => self::MAX,
		]);
		$this->_validation->setData([
			'value' => 'chocolat',
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
		$expectedMessage = strtr('La valeur ne doit pas avoir plus de :max caractères.', [
			':max' => self::MAX,
		]);
		$this->_validation->setFileErrors('testing');
		$this->_validation->setData([
			'value' => 'rutabaga',
		]);
		$this->_validation->validate();
		$error = $this->_validation->error('value');
		return ($error === $expectedMessage);
	}
	
	/*********************************************************/
	
}