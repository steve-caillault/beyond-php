<?php

/**
 * Gestion de la validation d'un tableau
 */

namespace Root;

use Root\Validation\Rules\Rule;

class Validation {
	
	private const ERROR_FILE_DIRECTORY = 'resources/errors/';
	public const 
		ERROR_RULE = 'rule',
		ERROR_MESSAGE = 'message'
	;
	
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
	public function __construct(array $params = [])
	{
		$this->_data = Arr::get($params, 'data', $this->_data);
		
		$rules = Arr::get($params, 'rules', $this->_group_rules);
		$this->addRules($rules);
		
		$this->_errors_filepath = Arr::get($params, 'file_errors', $this->_errors_filepath);
	}

	/********************************************************************************/
	
	/**
	 * Modifit les règles de validation
	 * @return self
	 */
	 public function setRules(array $rules) : self
	 {
		$this->_errors = [];
		$this->_group_rules = [];
		$this->addRules($rules);
		return $this;
	 }
	
	/**
	 * Modifit les données à valider
	 * @param array $data Les données à remplacer
	 * @return self
	 */
	public function setData(array $data) : self
	{
		$this->_errors = [];
		$this->_data = $data;
		return $this;
	}
	
	/**
	 * Modifit le fichier des erreurs
	 * @param string $filepath
	 * @return self
	 */
	public function setFileErrors(?string $filepath) : self
	{
		$this->_errors = [];
		$this->_errors_filepath = $filepath;
		$this->_errors_from_file = NULL;
		return $this;
	}
	
	/********************************************************************************/
	
	/**
	 * Validation des données, affectations des erreurs
	 * @return void
	 */
	public function validate() : void
	{
		foreach($this->_group_rules as $field => $rules)
		{
			$fieldValue = Arr::get($this->_data, $field);
			$required = FALSE;
			$knownValue = $this->_getRule($fieldValue, 'required')->check();
			
			foreach($rules as $ruleData)
			{
				$ruleName = Arr::get($ruleData, 0);
				$ruleParams = Arr::get($ruleData, 1, []);
				
				if($ruleName == 'required')
				{
					$required = TRUE;
				}
				
				if($ruleName != 'required' AND ! $required AND ! $knownValue)
				{
					continue;
				}
				
				$rule = $this->_getRule($fieldValue, $ruleName, $ruleParams);
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
	
	/********************************************************************************/
	
	/* AJOUT DE REGLES */
	
	/**
	 * Ajoute un ensemble de règle pour dffèrent champs
	 * @param array $rules
	 * @return self
	 */
	public function addRules(array $rules) : self
	{
		foreach($rules as $field => $fieldRules)
		{
			$this->addFieldRules($field, $fieldRules);
		}
		return $this;
	}
	
	/**
	 * Ajoute plusieurs règles à un champs
	 * @param string $field
	 * @param array $rules
	 * @return self
	 */
	public function addFieldRules(string $field, array $rules) : self
	{
		$currentRules = $this->_group_rules[$field] ?? [];
		$currentRules = [ ...$currentRules, ...$rules];
		
		// Tri des règles pour que la règle required soit en premier
		usort($currentRules, function($rule1, $rule2) use($currentRules) {
			$rule1Type = Arr::get($rule1, 0);
			$rule2Type = Arr::get($rule2, 0);
			
			if($rule1Type == 'required')
			{
				return -1;
			}
			elseif($rule2Type == 'required')
			{
				return 1;
			}
			
			$index1 = array_search($rule1, $currentRules);
			$index2 = array_search($rule2, $currentRules);
			
			return (($index1 > $index2) ? 1 : -1);
		});
		
		$this->_group_rules[$field] = $currentRules;
		
		return $this;
	}
	
	/**
	 * Ajoute une règle à un champs
	 * @param string $field
	 * @param string $rule
	 * @param array $parameters
	 * @return self
	 */
	public function addFieldRule(string $field, string $rule, array $parameters = []) : self
	{
		$rules = [
			array($rule, $parameters),
		];
		$this->addFieldRules($field, $rules);
		return $this;
	}
	
	/**
	 * Retourne la règle correspondant aux paramètres
	 * @param mixed $value La valeur à valider
	 * @param string $ruleName Le nom de la règle
	 * @param array $parameters Les paramètres de la règle
	 * @return Rule
	 */
	private function _getRule($value, string $ruleName, array $parameters = []) : ?Rule 
	{
		$classFound = FALSE;
		$classRule = NULL;
		
		$namespaces = [ 'App', __NAMESPACE__, ];
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
		
		
		return new $classRule([
			'value' => $value,
			'parameters' => $parameters,
		]);
	}
	
	/********************************************************************************/
	
	/**
	 * Ajoute une erreur
	 * @param string $field Champs pour lequel il y a une erreur
	 * @param string $ruleName Nom de la règle invalide 
	 * @param string $message Message d'erreur
	 * @return void
	 */
	public function addError(string $field, string $ruleName, string $message) : void
	{
		if(! Arr::get($this->_errors, $field))
		{
			$this->_errors[$field] = [];
		}
		
		$this->_errors[$field] = [
			self::ERROR_RULE => $ruleName,
			self::ERROR_MESSAGE => $message,
		];
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
		$fieldErrors = Arr::get($errors, $field, []);
		$ruleError = Arr::get($fieldErrors, $rule);
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
				$errorsLoaded = Arr::get(self::$_errors_files_loaded, $this->_errors_filepath);
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
	 * Retourne l'erreur d'un champs
	 * @param string $field Nom du champs
	 * @param string $errorKey Sous quelle forme retourner les erreurs rule|message 
	 * @return string
	 */
	public function error(string $field, string $errorKey = self::ERROR_MESSAGE) : ?string
	{
		$errors = $this->errors($errorKey);
		return Arr::get($errors, $field);
	}
	
	/**
	 * Retourne les erreurs
	 * @param string $errorKey Sous quelle forme retourner les erreurs rule|message 
	 * @return array
	 */
	public function errors(string $errorKey = self::ERROR_MESSAGE) : array
	{
		$allowedKeys = [ self::ERROR_RULE, self::ERROR_MESSAGE, ];
		if(! in_array($errorKey, $allowedKeys))
		{
			exception('Clé incorrecte.');
		}
		
		$errors = $this->_errors;
		array_walk($errors, fn(&$error) => $error = Arr::get($error, $errorKey));

		
		return $errors;
	}
	
	/********************************************************************************/
	
}