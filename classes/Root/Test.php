<?php

/**
 * Gestion d'un test appelé en ligne de commande
 */

namespace Root;

abstract class Test extends ExecutableRequest {
	
	/**
	 * Vrai si le test a réussi
	 * @var bool
	 */
	private bool $_success = FALSE;
	
	/**
	 * Messages à afficher
	 * @var array
	 */
	private array $_messages = [];
	
	/**
	 * Vrai si on peut ajouter le résultat du test dans le message
	 * @var bool
	 */
	protected bool $_can_add_message = TRUE;
	
	/********************************************/
	
	/**
	 * Exécute tous les tests du fichier
	 * @return bool
	 */
	protected function _executeAll() : bool
	{
		$reflectionClass = new \ReflectionClass($this);
		$reflectionClassMethods = $reflectionClass->getMethods();
		
		$pattern = '/Test$/D';
		
		$success = TRUE;
		
		foreach($reflectionClassMethods as $method)
		{
			if(preg_match($pattern, $method->name) === 1)
			{
				$this->before();
				$responseMethod = $this->{ $method->name }();
				$this->after();
				if(! $responseMethod)
				{
					$success = FALSE;
				}
				$this->_addLogResult($method->name, $responseMethod);
			}
		}
		
		return $success;
	}
	
	/**
	 * Méthode d'exécution
	 * @return void
	 */
	public function execute() : void
	{
		$method = $this->request()->route()->method();
		$this->_success = $this->{ $method }();
	}
	
	/**
	 * Réponse de la tâche
	 * @return mixed
	 */
	public function response()
	{
		$method = $this->request()->route()->method();
		$this->_addLogResult($method, $this->_success);
		$response = implode(PHP_EOL, $this->_messages);
		return new Response($response);
	}
	
	/**
	 * Ajoute un message à afficher
	 * @param string $message
	 * @return void
	 */
	public function addMessage(string $message) : void
	{
		$this->_messages[] = $message;
	}
	
	/**
	 * Retourne si le test s'est exécuté avec succès
	 * @return bool
	 */
	public function success() : bool
	{
		return $this->_success;
	}
	
	/********************************************/
	
	/**
	 * Ajoute la chaine du message comme résultat du test
	 * @param string $method La méthode qui a été testé
	 * @param bool $response La réponse du test
	 * @return void
	 */
	private function _addLogResult(string $method, bool $success) : void
	{
		if(! $this->_can_add_message)
		{
			return;
		}
		
		$color = ($success) ? 'green' : 'red';
		$colors = [
			'red' => '0;31',
			'green' => '0;32',
		];
		
		$statusText = ($success) ? 'Succès' : 'Echec';
		$statusMessage = strtr("\033[:colorm:status\033[0m", [
			':color' => $colors[$color],
			':status' => $statusText,
		]);
		
		if($success)
		{
			$message = 'Le test :test a été exécuté avec succès.';
		}
		else
		{
			$message = 'Echec du test :test.';
		}
		
		$testName = $this->request()->route()->name() . '::' . $method;
		
		$this->_messages[] = $statusMessage . ' ' . strtr($message, [
			':test' => $testName,
		]);
	}
	
	/********************************************/
	
}