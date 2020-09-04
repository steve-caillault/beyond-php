<?php defined('INITIALIZED') OR die('Vous n\'êtes pas autorisé à accéder à ce fichier.');

/**
 * Configuration de la base de données de développement
 */

return [
	Root\Config::DEFAULT => [
		'connection' => [
			'dns'			=> 'mysql:host=localhost;dbname=beyond-php;charset=UTF8',
			'username'		=> 'root',
			'password'		=> NULL,
		],
	],
];