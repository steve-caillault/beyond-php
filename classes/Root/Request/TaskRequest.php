<?php

/**
 * Gestion de l'appel d'une tâche en CLI
 */

namespace Root\Request;

use Root\Response;
use Root\Route\TaskRoute as Route;

class TaskRequest extends AbstractRequest {

	protected const ROUTE_CLASS = Route::class;
	
	/********************************************************************************/
	
	/**
	 * Réponse de la requête
	 * @return Response
	 */
	public function response() : ?Response
	{	
		// Vérifie si la tâche existe
		$taskClass = $this->_route->task();
		if(! class_exists($taskClass))
		{
			exception(strtr('Le tâche :name n\'existe pas', [
				':name' => $taskClass,
			]));
		}
		
		$task = new $taskClass;
		$task->request($this);
		return $task->response();
	}
	
	/********************************************************************************/
		
}