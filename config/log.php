<?php defined('INITIALIZED') OR die('Vous n\'êtes pas autorisé à accéder à ce fichier.');

/**
 * Configuration des logs
 */

use Root\Log;
use Root\Log\FileLog;

return [
	Log::CONFIG_DEFAULT => [
		'type' => FileLog::TYPE,
	],
];