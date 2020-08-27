<?php

/**
 * Configuration du cache
 */

use Root\Cache;

return [
	Cache::CONFIG_DEFAULT => [
		'type' => Cache::TYPE_FILE,
	],
	/*Cache::CONFIG_DEFAULT => [
		'type' => Cache::TYPE_MEMCACHE,
		'connection' => [
			'host' => 'localhost',
			'port' => 11211,
		],
		'prefix_key' => 'beyond-php-',
	],*/
];