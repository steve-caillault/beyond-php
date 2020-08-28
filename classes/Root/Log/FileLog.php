<?php

/**
 * Enregistrement de messages dans un fichier
 */

namespace Root\Log;

class FileLog extends BaseLog {
	
	public const TYPE = 'file';
	
	/**
	 * Chemin d'enregistrement des fichiers
	 */
	private const DIRECTORY = 'resources/logs/';
	
	/************************************************************/
	
	/**
	 * Retourne le chemin du fichier où enregistrer le message
	 * @return string
	 */
	private function _filepath() : ?string
	{
		$filepath = realpath(self::DIRECTORY);
		if($filepath === FALSE)
		{
			return NULL;
		}
		$date = $this->_datetime->format('Y-m-d');
		$filename = 'logs-' . $date . '.txt';
		$filepath .= DIRECTORY_SEPARATOR . $filename;
		return $filepath;
	}
	
	/************************************************************/
	/**
	 * Ajoute un message
	 * @param string $message
	 * @param string $level Niveau d'urgence
	 * @return bool
	 */
	public function add(string $message, string $level = self::LEVEL_DEBUG) : bool
	{
		$filepath = $this->_filepath();
		if(! $filepath)
		{
			return FALSE;
		}
		
		$file = fopen($filepath, 'a');
		if(! $file)
		{
			return FALSE;
		}
		
		$date = $this->_datetime->format('Y-m-d H:i:s');
		
		$texts = [
			$date . ' - ' . strtoupper($level) . ' - ' . $this->_uri,
			$message . PHP_EOL,
			'-------------------------------' . PHP_EOL,
		];
		
		foreach($texts as $text)
		{
			fwrite($file, $text . PHP_EOL);
		}
		fclose($file);
		
		return TRUE;
	}
	
	/************************************************************/
	
}