<?php defined('INITIALIZED') OR die('Vous n\'êtes pas autorisé à accéder à ce fichier.');

/**
 * Configuration des logs
 */

return [
	Root\Config::DEFAULT => [
		'type' => Root\Log\FileLog::TYPE,
	],
];