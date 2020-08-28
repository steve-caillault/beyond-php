<?php 

/**
 * Classe de base d'un gestionnaire (cache, log)
 * La classe ne sert que pour valider l'instance d'un gestionnaire
 */

namespace Root\Manager;

use Root\Instanciable;

abstract class BaseManager extends Instanciable {
	
	public const TYPE = ''; // file, database...
	
}