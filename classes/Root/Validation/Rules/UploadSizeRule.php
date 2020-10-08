<?php

/**
 * Règle vérifiant la taille d'un fichier téléchargé
 */

namespace Root\Validation\Rules;

use Root\Arr;
use Root\Exceptions\Validation\Rules\ParameterException;

class UploadSizeRule extends Rule {
	
	private const 
		SIZE_MEGA = 1000000,
		SIZE_KILO = 1000
	;
	
	/**
	 * Message en cas d'erreur
	 * @var string
	 */
	protected string $_error_message = 'Le fichier ne doit pas dépasser :size Mo.';
	
	/********************************************************************************/
	
	/* VERIFICATION */
	
	/**
	 * Retourne si la valeur respecte la règle
	 * @return bool
	 */
	public function check() : bool
	{
		// Pas de vérification si le fichier n'a pas été téléchargé
		$error = Arr::get($this->_getValue(), 'error');
		if($error != UPLOAD_ERR_OK)
		{
			return TRUE;
		}
		
		$maximumSize = $this->_getParameter('size'); 
		if(! is_numeric($maximumSize) OR $maximumSize <= 0)
		{
			throw new ParameterException('La taille maximum du fichier doit être un entier strictement positif.');
		}
		
		$fileSize = Arr::get($this->_getValue(), 'size', 0);
		if(! is_numeric($fileSize) OR $fileSize <= 0)
		{
			$this->_error_message = 'Le fichier est vide.';
			return FALSE;	
		}
		
		$allowedSize = $maximumSize * self::SIZE_MEGA;
		return ($fileSize < $allowedSize);
	}
	
	/********************************************************************************/
	
}