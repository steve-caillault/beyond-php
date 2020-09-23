<?php

/**
 * Gestion d'une ligne de commande
 */
 
namespace Root\Route;

abstract class CommandLine extends AbstractRoute {

	/**
	 * Affecte les paramètres de la route
	 * @return void
	 */
	protected function _retrieveParameters() : array
	{
		global $argv;
			
		$arguments = $argv;
		unset($arguments[0], $arguments[1]);
	
		$this->_parameters = (count($arguments) > 0) ? array_combine(range(0, count($arguments) - 1), array_values($arguments)) : [];
		
		return $this->_parameters;
	}

}