<?php

/**
 * Test sur la règle de validation min
 */

namespace Root\Testing\Validation;

class MinRuleTest extends WithParametersRuleTest {
	
	protected const CURRENT_RULE = 'min';
	
	private const MIN = 3.5;
	
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
			'value' => 6,
		]);
		return $this->_checkIncorrectRuleParameters();
	}
	
	/**
	 * Le paramètre min n'est pas une valeur numérique
	 * @return bool
	 */
	protected function _minParameterNotNumericTest() : bool
	{
		$this->_validation->setRules([
			'value' => [
				array(self::CURRENT_RULE, [
					'min' => 'test',
				]),
			],
		]);
		$this->_validation->setData([
			'value' => 3,
		]);
		return $this->_checkIncorrectRuleParameters();
	}
	
	/**
	 * La valeur n'est pas une valeur numérique
	 * @return bool
	 */
	protected function _valueNotNumericTest() : bool
	{
		$this->_validation->setData([
			'value' => 'George Eliot',
		]);
		return $this->_valueWithRuleError();
	}
	
	/**
	 * La valeur est trop petite
	 * @return bool
	 */
	protected function _valueTooBigTest() : bool
	{
		$this->_validation->setData([
			'value' => -1,
		]);
		return $this->_valueWithRuleError();
	}
	
	/**
	 * La valeur est identique au paramètre min
	 * @return bool
	 */
	protected function _sameValueTest() : bool
	{
		$this->_validation->setData([
			'value' => self::MIN,
		]);
		return $this->_isValid();
	}
	
	/**
	 * La valeur est supérieur au paramètre min
	 * @return bool
	 */
	protected function _valueGreaterThanMinParameterTest() : bool
	{
		$this->_validation->setData([
			'value' => 5,
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
		$expectedMessage = strtr('La valeur doit être supérieur ou égale à :min.', [
			':min' => self::MIN,
		]);
		$this->_validation->setData([
			'value' => 1,
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
		$expectedMessage = strtr('La valeur ne doit pas être inférieur à :min.', [
			':min' => self::MIN,
		]);
		$this->_validation->setFileErrors('testing');
		$this->_validation->setData([
			'value' => 2.3,
		]);
		$this->_validation->validate();
		$error = $this->_validation->error('value');
		return ($error === $expectedMessage);
	}
	
	/*********************************************************/
	
}