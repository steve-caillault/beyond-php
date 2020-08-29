<?php

/**
 * Gestion de l'application 
 */

namespace Root;

use Root\Environment\Environment;

class Application extends Instanciable {
	
	/**
	 * Génére une clé aléatoire pour l'application
	 * @return string
	 */
	public function generateKey() : string
	{
		$value = Str::random(random_int(20, 30));
		$key = (base64_encode(random_bytes(random_int(20, 30))));
		return hash_hmac('sha256', $value, $key);
	}
	
	
	/**
	 * Retourne la clé de l'application
	 * @return string
	 */
	public function getKey() : string
	{
		return Environment::instance()->getApplicationKey();
	}
}