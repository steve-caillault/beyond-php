<?php

/**
 * Test sur la règle de validation upload_extensions
 */

namespace Root\Testing\Validation;

class UploadExtensionsRuleTest extends WithParametersRuleTest {
	
	use WithUploadRuleTest;
	
	protected const CURRENT_RULE = 'upload_extensions';
	
	private const TYPES = [ 'txt', ];
	
	/**
	 * Règles de validation
	 * @var array
	 */
	protected array $_rules = [
		'value' => [
			array(self::CURRENT_RULE, [
				'types' => self::TYPES,
			]),
		],
	];
	
	/*********************************************************/
	
	/**
	 * Le paramètre types est inconnu
	 * @return bool
	 */
	protected function _typesParameterMissedTest() : bool
	{
		$this->_validation->setRules([
			'value' => [
				array(self::CURRENT_RULE),
			],
		]);
		$this->_validation->setData([
			'value' => $this->_array_files['valid'],
		]);
		return $this->_checkIncorrectRuleParameters();
	}
	
	/**
	 * Le paramètre types n'est pas un tableau
	 * @return bool
	 */
	protected function _typesParameterNotArrayTest() : bool
	{
		$this->_validation->setRules([
			'value' => [
				array(self::CURRENT_RULE, [
					'types' => 'test',
				]),
			],
		]);
		$this->_validation->setData([
			'value' => $this->_array_files['valid'],
		]);
		return $this->_checkIncorrectRuleParameters();
	}
	
	/**
	 * Le paramètre types est vide
	 * @return bool
	 */
	protected function _typesParameterEmptyTest() : bool
	{
		$this->_validation->setRules([
			'value' => [
				array(self::CURRENT_RULE, [
					'types' => [],
				]),
			],
		]);
		$this->_validation->setData([
			'value' => $this->_array_files['valid'],
		]);
		return $this->_checkIncorrectRuleParameters();
	}
	
	/**
	 * Erreur de téléchargement
	 * @return bool
	 */
	protected function _errorTest() : bool
	{
		$this->_validation->setData([
			'value' => $this->_array_files['error'],
		]);
		return $this->_isValid();
	}
	
	/**
	 * Aucun fichier téléchargé
	 * @return bool
	 */
	protected function _emptyTest() : bool
	{
		$this->_validation->setData([
			'value' => $this->_array_files['empty'],
		]);
		return $this->_isValid();
	}
	
	/**
	 * Extension correcte
	 * @return bool
	 */
	protected function _successTest() : bool
	{
		$this->_validation->setData([
			'value' => $this->_array_files['valid'],
		]);
		return $this->_isValid();
	}
	
	/**
	 * Extension de fichier incorrecte
	 * @return bool
	 */
	protected function _incorrectExtensionTest() : bool
	{
		$this->_validation->setRules([
			'value' => [
				array(self::CURRENT_RULE, [
					'types' => [ 'jpeg', 'jpg', ], 
				]),
			],
		]);
		$this->_validation->setData([
			'value' => $this->_array_files['valid'],
		]);
		return $this->_valueWithRuleError();
	}
	
	/*********************************************************/
	
	/* MESSAGES D'ERREUR */
	
	/**
	 * Test du message d'erreur par défaut
	 * @return bool
	 */
	protected function _defaultMessageTest() : bool
	{
		$expectedMessage = strtr('Le fichier doit-être de type :types.', [
			':types' => implode(', ', self::TYPES),
		]);
		$this->_validation->setData([
			'value' => $this->_array_files['too-big'],
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
		$expectedMessage = strtr('Le fichier doit avoir une extension de type :types.', [
			':types' => implode(', ', self::TYPES),
		]);
		$this->_validation->setFileErrors('testing');
		$this->_validation->setData([
			'value' =>  $this->_array_files['too-big'],
		]);
		$this->_validation->validate();
		$error = $this->_validation->error('value');
		return ($error === $expectedMessage);
	}
	
	/*********************************************************/
	
}