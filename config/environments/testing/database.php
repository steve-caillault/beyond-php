<?php defined('INITIALIZED') OR die('Vous n\'êtes pas autorisé à accéder à ce fichier.');

/**
 * Configuration de la base de données de test
 */

return [
	Root\Config::DEFAULT	=> [
		'connection'	=> [
			'dns'			=> '@complete',
			'username'		=> '@complete',
			'password'		=> '@complete',
		],
	],
];