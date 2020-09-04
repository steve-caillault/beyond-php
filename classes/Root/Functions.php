<?php

/**
 * Fichiers des fonctions
 */

/**
 * Redirection
 * @param string $path Chemin où rediriger
 * @return void
 */
function redirect(string $path) : void
{
	\Root\Redirect::process($path);
}

/**
 * Affichage du contenu d'une variable
 * @param mixed $variable
 * @param bool $exit Vrai si on doit arrêté l'exécution du script
 * @return string
 */
function debug($variable, bool $exit = FALSE) : string
{
	$response = \Root\Debug::show($variable);
	
	if($exit)
	{
		exit($response);
	}
	
	return $response;
}

/**
 * Ajoute un message de log
 * @param string $message
 * @param string $level Niveau d'urgence
 * @param string $name Nom dans la configuration
 * @return void
 */
function logMessage(
	string $message, 
	string $level = \Root\Log\BaseLog::LEVEL_DEBUG, 
	string $name = \Root\Log::CONFIG_DEFAULT
) : void
{
	\Root\Log::instance($name)->add($message, $level);
}

/**
 * Déclenchement d'une exception
 * @param string $message
 * @param int $code
 * @return void
 */
function exception(string $message, int $code = 500) : void
{
	throw new \Exception($message, $code);
}

/**
 * Retourne l'environnement du site
 * @return string
 */
function environment() : string
{
	return \Root\Environment\Environment::instance()->getName();
}

/**
 * Retourne la session
 * @return \Root\Session
 */
function session() : \Root\Session
{
	return \Root\Session::instance();
}

/**
 * Retourne le gestionnaire de cookies
 * @return \Root\Cookie\Cookie
 */
function cookie() : \Root\Cookie\Cookie
{
	return \Root\Cookie\Cookie::instance();
}

/**
 * Retourne le gestionnaire de cache dont le nom est en paramètre
 * @param string $name Nom dans la configuration
 * @return \Root\Cache\BaseCache
 */
function cache(string $name = \Root\Cache::CONFIG_DEFAULT) : \Root\Cache\BaseCache
{
	return \Root\Cache::instance($name);
}

/**
 * Retourne la valeur en session
 * @param string $key
 * @param mixed $defaultValue
 * @return mixed
 */
function getConfig(string $key, $defaultValue = NULL)
{
	return \Root\Config::load($key, $defaultValue);
}

/**
 * Modifit la langue du site
 * @param string $locale fr_FR, en_GB
 * @return void
 */
function setLanguage(string $locale) : void
{
	\Root\Core::setLanguage($locale);
}

/**
 * Retourne la valeur d'une clé dans un tableau.
 * On retourne la valeur par défaut en paramètre si la clé n'est pas présente
 * @param array $array Le tableau visé
 * @param mixed $key La clé visée
 * @param mixed $default La valeur par défault retournée si le clé n'est pas présente dans le tableau
 * @return mixed
 */
function getArray(?array $array, $key, $default = NULL)
{
	return \Root\Arr::get($array, $key, $default);
}

/**
 * Retourne l'URL du chemin que l'on donne en paramètre
 * @param string $uri
 * @param bool $absolute Vrai si on retourne l'URL absolut
 * @return string
 */
function getURL(string $uri, bool $absolute = FALSE) : string
{
	return \Root\URL::get($uri, $absolute);
}

/**
 * Traduction d'une chaine de caractère dans la langue courante
 * @param string $string La chaine de caractères à traduire
 * @param string $locale
 * @return string La chaine traduite
 */
function translate(string $string, array $options = [], ?string $locale = NULL) : string 
{
	$translations = \Root\Core::translations($locale);
	$dataTranslate = getArray($translations, $string, $string);
	if(is_string($dataTranslate))
	{
		return $dataTranslate;
	}
	// Gestion des options
	else
	{
		$count = getArray($options, 'count');
		$gender = getArray($options, 'gender');
		
		if($gender !== NULL)
		{
			$dataTranslate = getArray($dataTranslate, $gender, $string);
			if(is_string($dataTranslate))
			{
				return $dataTranslate;
			}
		}
		if($count !== NULL)
		{
			if($count > 1)
			{
				return getArray($dataTranslate, 'several', $string);
			}
			else
			{
				return getArray($dataTranslate, 'zero', $string);
			}
		}
		return $string;
	}
}