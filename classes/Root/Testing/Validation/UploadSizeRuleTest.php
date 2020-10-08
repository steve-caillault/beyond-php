<?php

/**
 * Test sur la règle de validation upload_size
 */

namespace Root\Testing\Validation;

class UploadSizeRuleTest extends WithParametersRuleTest {
	
	use WithUploadRuleTest;
	
	protected const CURRENT_RULE = 'upload_size';
	
	private const SIZE = 1024;
	
	/**
	 * Règles de validation
	 * @var array
	 */
	protected array $_rules = [
		'value' => [
			array(self::CURRENT_RULE, [
				'size' => self::SIZE,
			]),
		],
	];
	
	/*********************************************************/
	
	/**
	 * Le paramètre size est inconnu
	 * @return bool
	 */
	protected function _sizeParameterMissedTest() : bool
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
	 * Le paramètre size n'est pas un nombre
	 * @return bool
	 */
	protected function _sizeParameterNotNumericTest() : bool
	{
		$this->_validation->setRules([
			'value' => [
				array(self::CURRENT_RULE, [
					'size' => 'test',
				]),
			],
		]);
		$this->_validation->setData([
			'value' => $this->_array_files['valid'],
		]);
		return $this->_checkIncorrectRuleParameters();
	}
	
	/**
	 * Le paramètre size n'est pas un nombre positif
	 * @return bool
	 */
	protected function _sizeParameterNotPositiveTest() : bool
	{
		$this->_validation->setRules([
			'value' => [
				array(self::CURRENT_RULE, [
					'size' => -1,
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
	 * Taille correcte
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
	 * La taille du fichier est trop grande
	 * @return bool
	 */
	protected function _tooBigTest() : bool
	{
		$this->_validation->setData([
			'value' => $this->_array_files['too-big'],
		]);
		return $this->_valueWithRuleError();
	}
	
	/**
	 * La taille du fichier est trop faible
	 * @return bool
	 */
	protected function _tooSmallTest() : bool
	{
		$this->_validation->setData([
			'value' => $this->_array_files['too-small'],
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
		$expectedMessage = strtr('Le fichier ne doit pas dépasser :size Mo.', [
			':size' => self::SIZE,
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
		$expectedMessage = strtr('Le fichier ne doit peser plus de :size Mo.', [
			':size' => self::SIZE,
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