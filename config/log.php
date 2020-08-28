<?php

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