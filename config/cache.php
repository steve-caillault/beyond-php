<?php

/**
 * Configuration du cache
 */

use Root\Cache;
use Root\Cache\FileCache;

return [
	Cache::CONFIG_DEFAULT => [
		'type' => FileCache::TYPE,
	],
	/*Cache::CONFIG_DEFAULT => [
		'type' => \Root\Cache\MemcacheCache::TYPE,
		'connection' => [
			'host' => 'localhost',
			'port' => 11211,
		],
		'prefix_key' => 'beyond-php-',
	],*/
];