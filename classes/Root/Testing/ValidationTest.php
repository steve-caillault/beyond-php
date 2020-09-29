<?php

/**
 * Test sur les règles de validation
 */

namespace Root\Testing;

use Root\{ Test, Validation, ClassPHP };

class ValidationTest extends Test {
	
	/**
	 * Test sans données 
	 * @return bool
	 */
	protected function _emptyTest() : bool
	{
		$validation = new Validation();
		$validation->validate();
		return $validation->success();
	}
	
	/**
	 * Test l'affection de données
	 * @return bool
	 */
	protected function _setDataTest() : bool
	{
		$expectedData = [
			'name' => 'Charlemagne',
			'birthdate' => NULL,
		];
		$validation = new Validation([
			'data' => [
				'email' => NULL,
			],
		]);
		$validation->setData($expectedData);
		
		$class = new ClassPHP(Validation::class);
		$validationData = $class->getPropertyValue('_data', $validation);
		
		return ($validationData === $expectedData);
	}
	
	/**
	 * Test l'affectation des règles
	 * @return bool
	 */
	protected function _setRulesTest() : bool
	{
		$expectedRules = [
			'name' => [
				array('required'),
				array('min_length', [
					'min' => 5,
				]),
				array('max_length', [
					'max' => 100,
				]),
			],
			'email' => [
				array('required'),
				array('email'),
			],
		];
		
		$validation = new Validation([
			'rules' => [
				'email' => [
					array('required'),
				],
			],
		]);
		$validation->setRules($expectedRules);
		
		$class = new ClassPHP(Validation::class);
		$validationRules = $class->getPropertyValue('_group_rules', $validation);
		
		return ($validationRules === $expectedRules);
	}
	
	/**
	 * Test d'ajout d'une règle à un champs
	 * @return bool
	 */
	protected function _addFieldRuleTest() : bool
	{
		$expectedRules = [
			'email' => [
				array('email'),
			],
			'name' => [
				array('min_length', [
					'min' => 5,
				]),
			],
		];
		
		$validation = new Validation([
			'rules' => [
				'email' => [
					array('email'),
				],
			],
		]);
		
		$validation->addFieldRule('name', 'min_length', [
			'min' => 5,
		]);
	
		$class = new ClassPHP(Validation::class);
		$validationRules = $class->getPropertyValue('_group_rules', $validation);
		
		return ($validationRules === $expectedRules);
	}
	
	/**
	 * Test d'ajout de plusieurs règles à un champs
	 * @return bool
	 */
	protected function _addFieldRulesTest() : bool
	{ 
		$expectedRules = [
			'email' => [
				array('email'),
			],
			'name' => [
				array('required'),
				array('min_length', [
					'min' => 5,
				]),
				array('max_length', [
					'max' => 100,
				]),
			],
		];
		
		$validation = new Validation([
			'rules' => [
				'email' => [
					array('email'),
				],
			],
		]);
		
		$validation->addFieldRules('name', [
			array('min_length', [
				'min' => 5,
			]),
			array('max_length', [
				'max' => 100,
			]),
			array('required'),
		]);
		
		$class = new ClassPHP(Validation::class);
		$validationRules = $class->getPropertyValue('_group_rules', $validation);
		
		return ($validationRules === $expectedRules);
	}
	
	/**
	 * Test d'ajout de plusieurs règles à diffèrent champs
	 * @return bool
	 */
	protected function _addRulesTest() : bool
	{
		$expectedRules = [
			'email' => [
				array('required'),
				array('email'),
				array('min_length', [
					'min' => 5,
				]),
			],
			'name' => [
				array('required'),
				array('min_length', [
					'min' => 5,
				]),
				array('max_length', [
					'max' => 100,
				]),
			],
		];
		
		$validation = new Validation([
			'rules' => [
				'email' => [
					array('email'),
				],
			],
		]);
		
		$validation->addRules([
			'email' => [
				array('required'),
				array('min_length', [
					'min' => 5,
				]),
			],
			'name' => [
				array('min_length', [
					'min' => 5,
				]),
				array('required'),
				array('max_length', [
					'max' => 100,
				]),
			],
		]);
		
		$class = new ClassPHP(Validation::class);
		$validationRules = $class->getPropertyValue('_group_rules', $validation);
		
		return ($validationRules === $expectedRules);
	}
	
	/**
	 * Test l'affectation du fichier d'erreur
	 * @return bool
	 */
	protected function _setFileErrorsTest() : bool
	{
		$expectedFileErrors = 'site/login';
		
		$validation = new Validation([
			'file_errors' => 'default',
		]);
		$validation->setFileErrors($expectedFileErrors);

		$class = new ClassPHP(Validation::class);
		$validationFileErrors = $class->getPropertyValue('_errors_filepath', $validation);
		
		return ($validationFileErrors === $expectedFileErrors);
	}
	
}