<?php defined('INITIALIZED') OR die('Vous n\'êtes pas autorisé à accéder à ce fichier.');

/**
 * Configuration du cache
 */

use Root\Cache;

return [
	Cache::CONFIG_DEFAULT => [
		'type' => \Root\Cache\FileCache::TYPE,
		'prefix_key' => 'beyond-php-',
	],
	/*Cache::CONFIG_DEFAULT => [
		'type' => \Root\Cache\MemcacheCache::TYPE,
		'connection' => [
			'host' => 'localhost',
			'port' => 11211,
		],
		'prefix_key' => 'beyond-php-',
	],*/
	/*Cache::CONFIG_DEFAULT => [
		'type' => \Root\Cache\ApcuCache::TYPE,
		'prefix_key' => 'beyond-php-',
	],*/
];