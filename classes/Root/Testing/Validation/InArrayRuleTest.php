<?php

/**
 * Test sur la règle de validation in_array
 */

namespace Root\Testing\Validation;

class InArrayRuleTest extends WithParametersRuleTest {
	
	protected const CURRENT_RULE = 'in_array';
	
	private const ARRAY =  [ 1, 2, 3, ];
	
	/**
	 * Règles de validation
	 * @var array
	 */
	protected array $_rules = [
		'value' => [
			array(self::CURRENT_RULE, [
				'array' => self::ARRAY,
			]),
		],
	];
	
	/*********************************************************/
	
	/**
	 * Le paramètre array est inconnu
	 * @return bool
	 */
	protected function _missedArrayParameterTest() : bool
	{
		$this->_validation->setData([
			'value' => 'tomate',
		]);
		$this->_validation->setRules([
			'value' => [
				array(self::CURRENT_RULE),
			], 
		]);
		return $this->_checkIncorrectRuleParameters();
	}
	
	/**
	 * Le paramètre array n'est pas un tableau
	 * @return bool
	 */
	protected function _notArrayParameterTest() : bool
	{
		$this->_validation->setData([
			'value' => 'pomme de terre',
		]);
		$this->_validation->setRules([
			'value' => [
				array(self::CURRENT_RULE, [
					'array' => new class {},
				]),
			],
		]);
		return $this->_checkIncorrectRuleParameters();
	}
	
	/**
	 * Le paramètre array est un tableau vide
	 * @return bool
	 */
	protected function _emptyArrayParameterTest() : bool
	{
		$this->_validation->setData([
			'value' => 'poivron',
		]);
		$this->_validation->setRules([
			'value' => [
				array(self::CURRENT_RULE, [
					'array' => [],
				]),
			],
		]);
		return $this->_checkIncorrectRuleParameters();
	}
	
	/**
	 * Le paramètre array posséde des clés qui sont des objets
	 * @return bool
	 */
	protected function _arrayParameterWithObjectTest() : bool
	{
		try {
			$array = [
				new class {} => [
					'v1' => 1, 
					'v2' => -12,
				],
				[ 'test' ] => new class {},
				[
					'value1' => 10,
					'value2' => TRUE,
				],
				'test-value' => new class {},
			];
		} catch(\Error $exception) {
			$array = [];
		}
		
		$this->_validation->setData([
			'value' => 'piment',
		]);
		$this->_validation->setRules([
			'value' => [
				array(self::CURRENT_RULE, [
					'array' => $array,
				]),
			],
		]);
		
		return $this->_checkIncorrectRuleParameters();
	}
	
	/**
	 * La valeur n'est pas présente dans le tableau
	 * @return bool
	 */
	protected function _notInArrayTest() : bool
	{
		$this->_validation->setData([
			'value' => -1,
		]);
		return $this->_valueWithRuleError();
	}
	
	/**
	 * La valeur est présente dans le tableau
	 * @return bool
	 */
	protected function _inArrayTest() : bool
	{
		$this->_validation->setData([
			'value' => 2,
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
		$expectedMessage = strtr('La valeur doit être présente dans le tableau :array.', [
			':array' => implode(', ', self::ARRAY),
		]);
		$this->_validation->setData([
			'value' => 'Test',
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
		$expectedMessage = 'La valeur n\'est pas autorisée.';
		$this->_validation->setFileErrors('testing');
		$this->_validation->setData([
			'value' => FALSE,
		]);
		$this->_validation->validate();
		$error = $this->_validation->error('value');
		return ($error === $expectedMessage);
	}
	
	/*********************************************************/
	
}