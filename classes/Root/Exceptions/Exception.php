<?php

/**
 * Gestion d'une exception
 */

namespace Root\Exceptions;

use Root\{ Validation, Arr };
use Root\Environment\Environment;

final class Exception {
	
	private const CONFIG_KEY = 'beyond.exceptions.log';
	
	/**
	 * Validation de la configuration en paramètre
	 * @param array $configuration
	 * @return void
	 */
	private static function _validConfiguration(array $configuration) : void
	{
		$validation = Validation::factory([
			'data' => $configuration,
			'rules' => [
				'enabled' => [
					array('required'),
					array('boolean'),
				],
				'name' => [
					array('required'),
					array('string'),
				],
				'formatter_class' => [
					array('required'),
					array('class_exists'),
				],
			],
		]);
		
		$validation->validate();
		
		// Message d'erreurs de la configuration
		if(! $validation->success())
		{
			$message = 'Configuration des exceptions incorrecte.';
			if(environment() == Environment::DEVELOPMENT)
			{
				$message .= ' Liste des erreurs : ' . PHP_EOL;
				$errors = $validation->errors();
				array_walk($errors, function($error, $field) use(&$message) {
					$message .= ' - Entrée ' . self::CONFIG_KEY . '.' . $field . ' : ' . $error . PHP_EOL;
				});
			}
			
			exception($message);
		}
	}
	
	/**
	 * Enregistre le message de l'exception dans le journal
	 * @param \Throwable $exception
	 * @return void
	 */
	public static function log(\Throwable $exception) : void
	{
		$configuration = getConfig(self::CONFIG_KEY, []);
		self::_validConfiguration($configuration);
		
		$logName = Arr::get($configuration, 'name');
		
		// L'enregistrement n'est pas autorisé
		$enabled = (bool) Arr::get($configuration, 'enabled', FALSE);
		if(! $enabled)
		{
			return;
		}
		
		$formatterClass = Arr::get($configuration, 'formatter_class');
		$message = (new $formatterClass($exception))->formattedMessage();
		
		logMessage($message, \Root\Log\BaseLog::LEVEL_ERROR, $logName);
	}
	
}