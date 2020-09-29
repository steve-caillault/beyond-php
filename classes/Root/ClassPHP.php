<?php

/**
 * Gestion d'une classe PHP
 */

namespace Root;

use ReflectionClass;

class ClassPHP {
	
	/**
	 * Objet ReflectionClass
	 * @var ReflectionClass
	 */
	private ReflectionClass $_reflection_class;
	
	/**
	 * Constructeur
	 * @param string Nom de la classe
	 */
	public function __construct(string $name)
	{
		$this->_reflection_class = new ReflectionClass($name);
	}
	
	/**
	 * Retourne la valeur d'une propriété
	 * @param string $propertyName
	 * @param mixed $object Si renseigné, l'objet dont on souhaite récupérer la propriété
	 * @return mixed
	 */
	public function getPropertyValue(string $propertyName, $object = NULL)
	{
		$reflectionClass = $this->_reflection_class;
		
		if($reflectionClass->isAbstract())
		{
			return NULL;
		}
		
		if(! $reflectionClass->hasProperty($propertyName))
		{
			return NULL;
		}
		
		$property = $reflectionClass->getProperty($propertyName);	
		$property->setAccessible(TRUE);
		
		$className = $reflectionClass->getName();
		
		if($object === NULL)
		{
			$object = (! $property->isStatic()) ? new $className : NULL;
		}
		
		return $property->getValue($object);
	}
	
}