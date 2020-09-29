<?php

/**
 * Test sur la règle de validation class_exists
 */

namespace Root\Testing\Validation;

class ClassExistsRuleTest extends ExceptRequiredRuleTest {
	
	protected const CURRENT_RULE = 'class_exists';
	
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
	 * Test d'une classe qui n'existe pas
	 * @return bool
	 */
	protected function _classNotFoundTest() : bool
	{
		$this->_validation->setData([
			'value' => 'Object',
		]);
		return $this->_valueWithRuleError();
	}
	
	/**
	 * Test d'une classe qui existe
	 * @return bool
	 */
	protected function _classFoundTest() : bool
	{
		$this->_validation->setData([
			'value' => \Root\View::class,
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
		$expectedMessage = 'La classe n\'existe pas.';
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
		$expectedMessage = 'La classe Model n\'existe pas.';
		$this->_validation->setFileErrors('testing');
		$this->_validation->setData([
			'value' => 'Model',
		]);
		$this->_validation->validate();
		$error = $this->_validation->error('value');
		return ($error === $expectedMessage);
	}
	
	/*********************************************************/
	
}