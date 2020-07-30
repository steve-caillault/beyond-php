<?php defined('INITIALIZED') OR die('Vous n\'êtes pas autorisé à accéder à ce fichier.');

/**
 * Définition des routes
 */

use Root\Route;

/**
 * Racine
 */
Route::add('home', '', 'HomeController@index');

/**
 * Route de test
 */
Route::add('testing', 'testing', 'TestingController@index');

/*********************************************************************************/
