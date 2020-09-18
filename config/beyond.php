<?php defined('INITIALIZED') OR die('Vous n\'êtes pas autorisé à accéder à ce fichier.');

/**
 * Configuration de Beyond PHP
 */

return [
	'locale' => Root\Core::LOCALE_FR_FR,
	'encoding' => 'UTF-8',
	'debug' => FALSE, // Mode de déboggage 
	'exceptions' => [
		'log' => [
			'enabled' => TRUE,
			'name' => Root\Config::DEFAULT,
			'formatter_class' => Root\Exceptions\Log\BaseFormatterLog::class,
		],
	],
];