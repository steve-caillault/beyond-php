<?php

/**
 * Contrôleur HTML de base
 */

namespace Root\Controllers;

use Root\{ Response, View };

class HTMLController extends Controller {
    
    /**
     * Chemin de la vue de base à utiliser
     * @var string
     */
    protected string $_template_path;
    
    /**
     * Vue à utiliser
     * @var View
     */
    protected View $_template;
    
    /********************************************************************************/
    
    /* CONTRUCTEUR / INSTANCIATION */
    
    /**
     * Constructeur
     */
    public function __construct()
    {
    	if($this->_template_path === NULL)
        {
            exception('Le template est inconnu.');
        }
        
        $this->_template = new View($this->_template_path);
    }
    
    /********************************************************************************/
    
    /**
     * Retourne la réponse du contrôleur
     * @return mixed
     */
    final public function response()
    {
        $this->_response = new Response($this->_template->render());
        return $this->_response;
    }
   
    /********************************************************************************/
    
}