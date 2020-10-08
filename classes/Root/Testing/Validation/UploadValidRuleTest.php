<?php

/**
 * Test sur la règle de validation upload_valid
 */

namespace Root\Testing\Validation;

class UploadValidRuleTest extends ExceptRequiredRuleTest {
	
	use WithUploadRuleTest;
	
	protected const CURRENT_RULE = 'upload_valid';
	
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
	 * Erreur de téléchargement
	 * @return bool
	 */
	protected function _errorTest() : bool
	{
		$this->_validation->setData([
			'value' => $this->_array_files['error'],
		]);
		return $this->_valueWithRuleError();
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
	 * Fichier téléchargé
	 * @return bool
	 */
	protected function _successTest() : bool
	{
		$this->_validation->setData([
			'value' => $this->_array_files['valid'],
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
		$expectedMessage = 'Le fichier n\'a pas pu être téléchargé.';
		$this->_validation->setData([
			'value' => $this->_array_files['error'],
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
		$expectedMessage = 'Erreur lors du téléchargement du fichier.';
		$this->_validation->setFileErrors('testing');
		$this->_validation->setData([
			'value' =>  $this->_array_files['error'],
		]);
		$this->_validation->validate();
		$error = $this->_validation->error('value');
		return ($error === $expectedMessage);
	}
	
	/*********************************************************/
	
}