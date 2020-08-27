<?php defined('INITIALIZED') OR die('Vous n\'êtes pas autorisé à accéder à ce fichier.');

/**
 * Configuration de base de la base de données
 */

use Root\Database;

return [
	'default'	=> [
		'connection'	=> [
			'dns'			=> NULL,
			'username'		=> NULL,
			'password'		=> NULL,
		],
		'api'	=> Database::API_PDO,	
	],
];