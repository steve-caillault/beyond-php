<?php

/**
 * Gestion des exceptions
 */

namespace App\Exceptions;

use Root\{ Request, Response };
use Root\Exceptions\Exception;

class HttpException {
	
	/**
	 * Gestionnaire d'exception
	 * @param \Throwable $exception
	 * @return void
	 */
	public static function handler(\Throwable $exception) : void
	{
		$code = $exception->getCode();
		$message = $exception->getMessage();
		
		Exception::log($exception);
		
		$modeDebug = getConfig('beyond.debug');
		if($modeDebug)
		{
			debug($exception, TRUE);
		}
		
		$allowedCodes = [ 401, 403, 404, 500, ];
		if(! in_array($code, $allowedCodes))
		{
			$code = 500;
			$message = '';
		}
		
		if(! $message)
		{
			switch($code)
			{
				case 401:
					$message = 'Vous devez être identifié pour accéder à cette page.';
					break;
				case 403:
					$message = 'Vous n\'êtes pas autorisé à accéder à cette page.';
					break;
				case 404:
					$message = 'Cette page n\'existe pas ou a été déplacé.';
					break;
				case 500:
					$message = 'Une erreur s\'est produite.';
					break;
			}
		}
		
		if($code == 500)
		{
			$message = 'Une erreur s\'est produite.';
		}
		
		$response = NULL;
		
		try {
			$currentRequest = Request::current();
			$isAjax = $currentRequest->isAjax();
		} catch(\Throwable $exceptionRequest) {
			$isAjax = FALSE;
		}
		
		if($isAjax)
		{
			$json = json_encode([
				'error' => [
					'code' => $code,
					'message' => $message,
				],
			]);
			$response = new Response($json);
			$response->addHeader('Content-Type', 'application/json');
		}
		else
		{
			$response = new Response($message);
			$response->addHeader('Content-Type', 'text/plain');
		}
		
		http_response_code($code);
		
		$response->sendHeaders();
		exit($response);
	}
	
}