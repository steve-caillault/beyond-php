<?php defined('INITIALIZED') OR die('Vous n\'êtes pas autorisé à accéder à ce fichier.');

/**
 * Configuration de base de la base de données
 */

return [
	Root\Config::DEFAULT	=> [
		'connection'	=> [
			'dns'			=> NULL,
			'username'		=> NULL,
			'password'		=> NULL,
		],
		'api'	=> Root\Database::API_PDO,	
	],
];