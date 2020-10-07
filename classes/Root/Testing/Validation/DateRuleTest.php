<?php

/**
 * Test sur la règle de validation d'une date
 */

namespace Root\Testing\Validation;

class DateRuleTest extends ExceptRequiredRuleTest {
	
	protected const CURRENT_RULE = 'date';
	
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
	 * Test pour un format incorrect
	 * @return bool
	 */
	protected function _invalidFormatTest() : bool
	{
		$this->_validation->setData([
			'value' => 'test',
		]);
		$this->_validation->setRules([
			'value' => [
				array('date', [
					'format' => 'azerty',
				]),
			],
		]);
		return $this->_valueWithRuleError();
	}
	
	/**
	 * Test pour une date qui ne respecte pas le format demandé
	 * @return bool
	 */
	protected function _incorrectDateFormatTest() : bool
	{
		$this->_validation->setData([
			'value' => date('d-m-Y'),
		]);
		$this->_validation->setRules([
			'value' => [
				array('date', [
					'format' => 'Y-m-d',
				]),
			],
		]);
		return $this->_valueWithRuleError();
	}
	
	/**
	 * Test pour une date qui respecte le format demandé
	 * @return bool
	 */
	protected function _validDateFormatTest() : bool
	{
		$this->_validation->setData([
			'value' => '1812-02-07 13:25:43',
		]);
		$this->_validation->setRules([
			'value' => [
				array('date', [
					'format' => 'Y-m-d H:i:s',
				]),
			],
		]);
		return $this->_isValid();
	}
	
	/**
	 * Test pour une valeur qui n'est pas une date
	 * @return bool
	 */
	protected function _invalidDateFormatTest() : bool
	{
		$this->_validation->setData([
			'value' => [ 'test', ],
		]);
		$this->_validation->setRules([
			'value' => [
				array('date', [
					'format' => 'd/m/Y',
				]),
			],
		]);
		return $this->_valueWithRuleError();
	}
	
	/**
	 * Test pour une date incorrecte sans précision du format
	 * @return bool
	 */
	protected function _invalidDateTest() : bool
	{
		$this->_validation->setData([
			'value' => 12,
		]);
		return $this->_valueWithRuleError();
	}
	
	/**
	 * Test pour une date correcte sans précision du format
	 * @return bool
	 */
	protected function _validDateTest() : bool
	{
		$this->_validation->setData([
			'value' => '1810-12-29',
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
		$expectedMessage = 'La valeur doit être une date valide.';
		$this->_validation->setData([
			'value' => TRUE,
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
		$expectedMessage = 'La date doit respecter le format :format.';
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