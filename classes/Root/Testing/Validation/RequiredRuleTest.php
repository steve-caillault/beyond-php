<?php

/**
 * Test sur la règle de validation required
 */

namespace Root\Testing\Validation;

class RequiredRuleTest extends RuleTest {

	protected const CURRENT_RULE = 'required';
	
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
	
	/* VALEUR MANQUANTE, NULL OU VIDE  */
	
	/**
	 * Test de validation d'un tableau de données vide
	 * @return bool
	 */
	protected function _emptyDataTest() : bool
	{
		return $this->_valueWithRuleError();
	}
	
	/**
	 * Test de validation d'un tableau sans la valeur 
	 * @return bool
	 */
	protected function _missedValueTest() : bool
	{
		$this->_validation->setData([
			'key' => 'value',
		]);
		return $this->_valueWithRuleError();
	}
	
	/**
	 * Test la valeur NULL
	 * @return bool
	 */
	protected function _nullValueTest() : bool
	{
		$this->_validation->setData([
			'value' => NULL,
		]);
		return $this->_valueWithRuleError();
	}
	
	/**
	 * Test la valeur à la chaine vide
	 * @return bool
	 */
	protected function _emptyValueTest() : bool
	{
		$this->_validation->setData([
			'value' => '',
		]);
		return $this->_valueWithRuleError();
	}
	
	/*********************************************************/ 
	
	/* BOOLEAN ET ENTIER */
	
	/**
	 * Test la valeur FALSE, qui est autorisée
	 * @return bool
	 */
	protected function _falseValueTest() : bool
	{
		$this->_validation->setData([
			'value' => FALSE,
		]);
		return $this->_isValid();
	}
	
	/**
	 * Test la valeur TRUE
	 * @return bool
	 */
	protected function _trueValueTest() : bool
	{
		$this->_validation->setData([
			'value' => TRUE,
		]);
		return $this->_isValid();
	}
	
	/**
	 * Test la valeur 0, qui est autorisée
	 * @return bool
	 */
	protected function _zeroValueTest() : bool
	{
		$this->_validation->setData([
			'value' => 0,
		]);
		return $this->_isValid();
	}
	
	/**
	 * Test la valeur 1
	 * @return bool
	 */
	protected function _oneValueTest() : bool
	{
		$this->_validation->setData([
			'value' => 1,
		]);
		return $this->_isValid();
	}
	
	/*********************************************************/
	
	/**
	 * Test d'une chaine de caractère
	 * @return bool
	 */
	protected function _stringValueTest() : bool
	{
		$this->_validation->setData([
			'value' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
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
		$expectedMessage = 'La valeur ne doit pas être vide.';
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
		$expectedMessage = 'La valeur est manquante.';
		$this->_validation->setFileErrors('testing');
		$this->_validation->validate();
		$error = $this->_validation->error('value');
		return ($error === $expectedMessage);
	}
	
	/*********************************************************/
	
}