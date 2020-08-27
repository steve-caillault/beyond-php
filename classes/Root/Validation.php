<?php

/**
 * Gestion de la validation d'un tableau
 */

namespace Root;

class Validation extends Instanciable {
	
	private const ERROR_FILE_DIRECTORY = 'resources/errors/';
	
	/**
	 * Données d'un tableau à valider
	 * @var array
	 */
	private array $_data = [];
	
	/**
	 * Règles pour la validation pour chaque champs
	 * @var array
	 */
	private array $_group_rules = [];
	
	/**
	 * Chemin relatif au fichier des erreurs
	 * @var string
	 */
	private ?string $_errors_filepath = NULL;
	
	/**
	 * Fichiers d'erreurs déjà chargés
	 * @var array
	 */
	private static array $_errors_files_loaded = [];
	
	/**
	 * Erreurs chargés dans le fichier 
	 * @var array
	 */
	private ?array $_errors_from_file = NULL;
	
	/**
	 * Listes des erreurs
	 * @var array
	 */
	private array $_errors = [];
	
	/********************************************************************************/
	
	/* CONTRUCTEUR / INSTANCIATION */
	
	/**
	 * Contructeur
	 * @var array $params :
	 * 	'data' 	=> array, // Données du formulaire
	 *  'rules'	=> array, // Règles de validation
	 */
	protected function __construct(array $params = [])
	{
		$this->_data = getArray($params, 'data', $this->_data);
		$this->_group_rules = getArray($params, 'rules', $this->_group_rules);
		
		$this->_errors_filepath = getArray($params, 'file_errors', $this->_errors_filepath);
	}

	/********************************************************************************/
	
	/**
	 * Validation des données, affectations des erreurs
	 * @return void
	 */
	public function validate() : void
	{
		$namespaces = [ 'App', __NAMESPACE__, ];
		
		foreach($this->_group_rules as $field => $rules)
		{
			$fieldValue = getArray($this->_data, $field);
			$required = FALSE;
			
			foreach($rules as $ruleData)
			{
				$ruleName = getArray($ruleData, 0);
				
				if($ruleName == 'required')
				{
					$required = TRUE;
				}
				
				if($ruleName != 'required' AND ! $required AND $fieldValue == NULL)
				{
					continue;
				}
				
				$ruleParams = getArray($ruleData, 1, []);
				
				$classFound = FALSE;
				$classRule = NULL;
				
				foreach($namespaces as $namespace)
				{
					$classRule = $namespace . '\Validation\Rules\\' . Str::camelCase($ruleName) . 'Rule';
					if($classFound = class_exists($classRule))
					{
						break;
					}
				}
				
				if(! $classFound)
				{
					exception(strtr('La classe :class n\'a pas été trouvé.', [
						':class' => $classRule,
					]));
				}
				
				$rule = $classRule::factory([
					'value' => $fieldValue,
					'parameters' => $ruleParams,
				]);
				
				if(! $rule->check())
				{
					// Attache l'erreur présente dans le fichier
					$errorMessage = $this->_errorFromFile($field, $ruleName);
					if($errorMessage !== NULL)
					{
						$rule->setMessage($errorMessage);
					}
				
					$this->addError($field, $ruleName, $rule->errorMessage());
					break;
				}
			}
		}
	}
	
	/**
	 * Ajoute une erreur
	 * @param string $field Champs pour lequel il y a une erreur
	 * @param string $ruleName Nom de la règle invalide 
	 * @param string $message Message d'erreur
	 * @return void
	 */
	public function addError(string $field, string $ruleName, string $message) : void
	{
		if(! getArray($this->_errors, $field))
		{
			$this->_errors[$field] = [];
		}
		
		$this->_errors[$field] = $message;
	}
	
	/********************************************************************************/
	
	/**
	 * Retourne l'erreur du fichier correspondant au champs et à la règle
	 * @param string $field
	 * @param string $rule
	 * @return string
	 */
	private function _errorFromFile(string $field, string $rule) : ?string
	{
		$errors = $this->_errorsFromFile();
		$fieldErrors = getArray($errors, $field, []);
		$ruleError = getArray($fieldErrors, $rule);
		return $ruleError;
	}
	
	/**
	 * Retourne les erreurs du fichier
	 * @return array
	 */
	private function _errorsFromFile() : array
	{
		if($this->_errors_from_file === NULL)
		{
			$errors = [];
			
			if($this->_errors_filepath !== NULL)
			{
				$errorsLoaded = getArray(self::$_errors_files_loaded, $this->_errors_filepath);
				$filepath = '.' . DIRECTORY_SEPARATOR . self::ERROR_FILE_DIRECTORY . $this->_errors_filepath . '.php';
				if(is_array($errorsLoaded))
				{
					$errors = $errorsLoaded;
					self::$_errors_files_loaded[$this->_errors_filepath] = $errors;
				}
				elseif($errorsLoaded === NULL AND file_exists($filepath))
				{
					$errors = require $filepath;
					self::$_errors_files_loaded[$this->_errors_filepath] = $errors;
				}
				
			}
			
			$this->_errors_from_file = $errors;
		}
		return $this->_errors_from_file;
	}
	
	/********************************************************************************/
	
	/**
	 * Retourne s'il n'y a pas d'erreur de validation
	 * @return bool
	 */
	public function success() : bool
	{
		return (count($this->_errors) == 0);
	}
	
	/**
	 * Retourne les erreurs
	 * @return  array
	 */
	public function errors() : array
	{
		return $this->_errors;
	}
	
	/********************************************************************************/
	
}