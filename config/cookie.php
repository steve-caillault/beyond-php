<?php defined('INITIALIZED') OR die('Vous n\'êtes pas autorisé à accéder à ce fichier.');

/**
 * Configuration pour les cookies
 */

use Root\Cookie\Cookie;

return [
	Root\Config::DEFAULT => [
		Cookie::OPTION_PATH => Cookie::PATH_ROOT,
		Cookie::OPTION_DOMAIN => Cookie::DOMAIN_CURRENT,
		Cookie::OPTION_SECURE => Cookie::SECURE_FROM_REQUEST,
		Cookie::OPTION_HTTP_ONLY => TRUE,
		Cookie::OPTION_SAME_SITE => Cookie::SAME_SITE_STRICT,
	],
];