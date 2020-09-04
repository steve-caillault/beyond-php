<?php defined('INITIALIZED') OR die('Vous n\'êtes pas autorisé à accéder à ce fichier.');

/**
 * Configuration des sessions
 */

use Root\Session;

return [
	Session::CONFIG_DEFAULT => [
		Session::OPTION_LIFETIME => Session::LIFETIME_SESSION,
		Session::OPTION_PATH => Session::PATH_ROOT,
		Session::OPTION_DOMAIN => Session::DOMAIN_CURRENT,
		Session::OPTION_SECURE => Session::SECURE_FROM_REQUEST,
		Session::OPTION_HTTP_ONLY => TRUE,
		Session::OPTION_SAME_SITE => Session::SAME_SITE_STRICT,
	],
];