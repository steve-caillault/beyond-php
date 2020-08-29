<?php

/**
 * Méthode utilitaire sur les chaines de caractères
 */

namespace Root;

final class Str {
    
    /**
     * Transforme la chaine de caractères en paramètre en camelCase
     * @param string $value 
     * @return string
     */
    public static function camelCase(string $value) : string
    {
        return strtr(ucwords(strtr(strtolower($value), [ '_' => ' '])), [ ' ' => '' ]);
    }
    
    /**
     * Génére une chaine au hasard
     * @param int $length Longueur de la chaine à générer
     * @return string
     */
    public static function random(int $length) : string
    {
    	
    	$chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    	$maxIndex = strlen($chars);
    	
    	$str = '';
    	for($i = 0 ; $i < $length ; $i++)
    	{
    		$index = random_int(0, $maxIndex - 1);
    		$str .= $chars[$index];
    	}
    	
    	return $str;
    }
    
}