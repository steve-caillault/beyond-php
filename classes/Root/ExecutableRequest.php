<?php

/**
 * Classe pour les objets éxécutant une requête : contrôleur, tâche, test
 */

namespace Root;

use Root\Request\AbstractRequest as Request;

abstract class ExecutableRequest {
	
	/**
	 * La requête du contrôleur
	 * @var Request
	 */
	protected Request $_request;
	
	/**
	 * Réponse de la méthode principale du contrôleur
	 * @var Response
	 */
	protected Response $_response;
	
	/**
	 * Affecte ou retourne la requête
	 * @param ?Request $request La requête à affecter
	 * @return Request
	 */
	public function request(?Request $request = NULL) : Request
	{
		if($request !== NULL)
		{
			$this->_request = $request;
		}
		return $this->_request;
	}
	
	/**
	 * Retourne la réponse du contrôleur
	 * @return mixed
	 */
	public function response()
	{
		return $this->_response;
	}
	
	/**
	 * Méthode d'exécution
	 * @return void
	 */
	abstract public function execute() : void;
	
}