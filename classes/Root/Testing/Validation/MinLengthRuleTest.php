<?php

/**
 * Test sur la règle de validation min_length
 */

namespace Root\Testing\Validation;

class MinLengthRuleTest extends WithParametersRuleTest {
	
	protected const CURRENT_RULE = 'min_length';
	
	private const MIN =  5;
	
	/**
	 * Règles de validation
	 * @var array
	 */
	protected array $_rules = [
		'value' => [
			array(self::CURRENT_RULE, [
				'min' => self::MIN,
			]),
		],
	];
	
	/*********************************************************/
	
	/**
	 * Le paramètre min est inconnu
	 * @return bool
	 */
	protected function _missedMinParameterTest() : bool
	{
		$this->_validation->setRules([
			'value' => [
				array(self::CURRENT_RULE),
			],
		]);
		$this->_validation->setData([
			'value' => 'John Milton',
		]);
		return $this->_checkIncorrectRuleParameters();
	}
	
	/**
	 * Le paramètre min n'est pas un entier
	 * @return bool
	 */
	protected function _minParameterNotIntegerTest() : bool
	{
		$this->_validation->setRules([
			'value' => [
				array(self::CURRENT_RULE, [
					'min' => 'test',
				]),
			],
		]);
		$this->_validation->setData([
			'value' => 'Alexandre Pouchkine',
		]);
		return $this->_checkIncorrectRuleParameters();
	}
	
	/**
	 * Le paramètre min n'est pas positif
	 * @return bool
	 */
	protected function _minParameterNotPositiveTest() : bool
	{
		$this->_validation->setRules([
			'value' => [
				array(self::CURRENT_RULE, [
					'min' => -2,
				]),
			],
		]);
		$this->_validation->setData([
			'value' => 'Vassili Grossman',
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
			'value' => FALSE,
		]);
		$this->_validation->validate();
		$expectedError = 'La valeur doit être une chaine de caractères.';
		$error = $this->_validation->error('value');
		return ($error === $expectedError);
	}
	
	/**
	 * La valeur est trop courte
	 * @return bool
	 */
	protected function _tooShortLengthTest() : bool
	{
		$this->_validation->setData([
			'value' => 'test',
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
			'value' => 'William Faulkner',
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
		$expectedMessage = strtr('La valeur doit avoir au moins :min caractères.', [
			':min' => self::MIN,
		]);
		$this->_validation->setData([
			'value' => 'ail',
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
		$expectedMessage = strtr('La valeur ne doit pas avoir moins de :min caractères.', [
			':min' => self::MIN,
		]);
		$this->_validation->setFileErrors('testing');
		$this->_validation->setData([
			'value' => 'noix',
		]);
		$this->_validation->validate();
		$error = $this->_validation->error('value');
		return ($error === $expectedMessage);
	}
	
	/*********************************************************/
	
}