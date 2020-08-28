<?php

/**
 * Gestionnaires
 */

namespace Root;

use Root\Manager\BaseManager;

class Manager {
	
	public const
		MANAGER_TYPE = '',
		/***/
		CONFIG_DEFAULT = 'default'
	;
		
	/**
	 * Instances de manager
	 * @var array
	 */
	private static array $_instance = [];
	
	/****************************************************/
	
	/**
	 * Retourne le gestionnaire dont le nom est en paramètre
	 * @param string $name
	 * @return BaseManager
	 */
	public static function instance(string $name = self::CONFIG_DEFAULT) : BaseManager
	{
		$managerType = static::MANAGER_TYPE;
		
		if($managerType == NULL)
		{
			exception('Type de gestionnaire inconnu.');
		}
		
		if(! array_key_exists($managerType, self::$_instance))
		{
			self::$_instance[$managerType] = [];
		}
		
		if(! array_key_exists($name, self::$_instance[$managerType]))
		{
			
			$config = getConfig($managerType . '.' . $name, []);
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
				$managerTypeFormat = ucfirst(Str::camelCase($managerType));
				$class = $namespace . '\\' . $managerTypeFormat . '\\' . ucfirst(Str::camelCase($type)) . $managerTypeFormat;
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
			
			self::$_instance[$managerType][$name] = $class::factory($config);
		}
		
		return self::$_instance[$managerType][$name];
	}
	
	/****************************************************/
		
}