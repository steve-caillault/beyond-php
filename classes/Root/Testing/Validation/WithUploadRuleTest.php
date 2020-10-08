<?php

/**
 * Trait pour les règles de validation de téléchargement de fichier
 */

namespace Root\Testing\Validation;

trait WithUploadRuleTest {
	
	/**
	 * Fichiers pour tester
	 * @var array
	 */
	protected array $_array_files = [
		'valid' => [
			'name' => 'text.txt',
			'type' => 'text/plain',
			'tmp_name' => '/tmp/php/file1',
			'error' => UPLOAD_ERR_OK,
			'size' => 1024,
		],
		'too-big' => [
			'name' => 'video.mpeg',
			'type' => 'video/mpeg',
			'tmp_name' => '/tmp/php/file2',
			'error' => UPLOAD_ERR_OK,
			'size' => 1024001000,
		],
		'too-small' => [
			'name' => 'data.json',
			'type' => 'application/json',
			'tmp_name' => '/tmp/php/file3',
			'error' => UPLOAD_ERR_OK,
			'size' => 0,
		],
		'empty' => [
			'name' => '',
			'type' => '',
			'tmp_name' => '',
			'error' => UPLOAD_ERR_NO_FILE,
			'size' => 0,
		],
		'error' => [
			'name' => '',
			'type' => '',
			'tmp_name' => '',
			'error' => UPLOAD_ERR_CANT_WRITE,
			'size' => 0,
		],
	];
	
}