<?php

/**
 * Test sur la règle de validation exact_length
 */

namespace Root\Testing\Validation;

class HttpDomainRuleTest extends ExceptRequiredRuleTest {
	
	protected const CURRENT_RULE = 'http_domain';

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
	 * Domaine incorrect
	 * @return bool
	 */
	protected function _invalidDomainTest() : bool
	{
		$this->_validation->setData([
			'value' => [ 'test', ],
		]);
		return $this->_valueWithRuleError();
	}
	
	/**
	 * Domaine valide
	 * @return bool
	 */
	protected function _validDomainTest() : bool
	{
		$this->_validation->setData([
			'value' => 'www.test.info',
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
		$expectedMessage = 'La valeur doit être un domaine HTTP.';
		$this->_validation->setData([
			'value' => 'Marcel Proust',
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
		$expectedMessage = 'Le domaine n\'est pas valide.';
		$this->_validation->setFileErrors('testing');
		$this->_validation->setData([
			'value' => -1.25,
		]);
		$this->_validation->validate();
		$error = $this->_validation->error('value');
		return ($error === $expectedMessage);
	}
	
	/*********************************************************/
	
}