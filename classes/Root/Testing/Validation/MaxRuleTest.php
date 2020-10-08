<?php

/**
 * Test sur la règle de validation max
 */

namespace Root\Testing\Validation;

class MaxRuleTest extends WithParametersRuleTest {
	
	protected const CURRENT_RULE = 'max';
	
	private const MAX = 12.5;
	
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
			'value' => 5,
		]);
		return $this->_checkIncorrectRuleParameters();
	}
	
	/**
	 * Le paramètre max n'est pas une valeur numérique
	 * @return bool
	 */
	protected function _maxParameterNotNumericTest() : bool
	{
		$this->_validation->setRules([
			'value' => [
				array(self::CURRENT_RULE, [
					'max' => 'test',
				]),
			],
		]);
		$this->_validation->setData([
			'value' => 24,
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
			'value' => 'Léon Tolstoï',
		]);
		return $this->_valueWithRuleError();
	}
	
	/**
	 * La valeur est trop élevée
	 * @return bool
	 */
	protected function _valueTooBigTest() : bool
	{
		$this->_validation->setData([
			'value' => 12.56,
		]);
		return $this->_valueWithRuleError();
	}
	
	/**
	 * La valeur est identique au paramètre max
	 * @return bool
	 */
	protected function _sameValueTest() : bool
	{
		$this->_validation->setData([
			'value' => self::MAX,
		]);
		return $this->_isValid();
	}
	
	/**
	 * La valeur est inférieur au paramètre max
	 * @return bool
	 */
	protected function _valueLessThanMaxParameterTest() : bool
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
		$expectedMessage = strtr('La valeur doit être inférieure ou égale à :max.', [
			':max' => self::MAX,
		]);
		$this->_validation->setData([
			'value' => 25,
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
		$expectedMessage = strtr('La valeur ne doit pas être supérieure à :max.', [
			':max' => self::MAX,
		]);
		$this->_validation->setFileErrors('testing');
		$this->_validation->setData([
			'value' => [ 'test', ],
		]);
		$this->_validation->validate();
		$error = $this->_validation->error('value');
		return ($error === $expectedMessage);
	}
	
	/*********************************************************/
	
}