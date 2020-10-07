<?php

/**
 * Test sur la règle de validation exact_length
 */

namespace Root\Testing\Validation;

class ExactLengthRuleTest extends ExceptRequiredRuleTest {
	
	protected const CURRENT_RULE = 'exact_length';
	
	private const LENGTH = 8;
	
	/**
	 * Règles de validation
	 * @var array
	 */
	protected array $_rules = [
		'value' => [
			array(self::CURRENT_RULE, [
				'length' => self::LENGTH,
			]),
		],
	];
	
	/*********************************************************/

	/**
	 * Chaine de caractères trop courte
	 * @return bool
	 */
	protected function _valueTooShortTest() : bool
	{
		$this->_validation->setData([
			'value' => 'poire',
		]);
		return $this->_valueWithRuleError();
	}
	
	/**
	 * Chaine de caractères trop longue
	 * @return bool
	 */
	protected function _valueTooLongTest() : bool
	{
		$this->_validation->setData([
			'value' => 'Pimprenelle',
		]);
		return $this->_valueWithRuleError();
	}
	
	/**
	 * Chaine de caractères correcte
	 * @return bool
	 */
	protected function _validValueTest() : bool
	{
		$this->_validation->setData([
			'value' => 'azertyui',
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
		$expectedMessage = strtr('La valeur doit avoir exactement :length caractères.', [
			':length' => self::LENGTH,
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
		$expectedMessage = strtr('La valeur doit avoir :length caractères.', [
			':length' => self::LENGTH,
		]);
		$this->_validation->setFileErrors('testing');
		$this->_validation->setData([
			'value' => 'pomme',
		]);
		$this->_validation->validate();
		$error = $this->_validation->error('value');
		return ($error === $expectedMessage);
	}
	
	/*********************************************************/
	
}