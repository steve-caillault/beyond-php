<?php

/**
 * Gestionnaire de cache
 */

namespace Root;

use Root\Cache\BaseCache;

class Cache {
	
	public const
		CONFIG_DEFAULT = 'default',
		/***/
		TYPE_FILE = 'file',
		TYPE_MEMCACHE = 'memcache'
	;
	
	/**
	 * Instances de cache
	 * @var array
	 */
	private static array $_instance = [];
	
	/****************************************************/
	
	/**
	 * Retourne le gestionnaire dont le nom est en paramètre
	 * @param string $name
	 * @return BaseCache
	 */
	public static function instance(string $name = self::CONFIG_DEFAULT) : BaseCache
	{
		if(! array_key_exists($name, self::$_instance))
		{
			$config = getConfig('cache.' . $name, []);
			$type = getArray($config, 'type');
			
			if($type === NULL)
			{
				exception('Type de configuration inconnu.');
			}
			
			$namespaces = [ 'App', __NAMESPACE__, ];
			$classFound = FALSE;
			$class = NULL;
			
			foreach($namespaces as $namespace)
			{
				$class = $namespace . '\Cache\\' . Str::camelCase($type) . 'Cache';
				if($classFound = class_exists($class))
				{
					break;
				}
			}
			
			if(! $classFound)
			{
				exception(strtr('La classe :class n\'a pas été trouvé.', [
					':class' => $class,
				]));
			}
			
			self::$_instance[$name] = $class::factory($config);
		}
		
		return self::$_instance[$name];
	}
	
	/****************************************************/
	
}