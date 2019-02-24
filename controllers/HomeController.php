<?php

/**
 * Domovská stránka
 */
class HomeController extends Controller {

    public function process($params) {
        $this->header = array(
            'title' => 'Titulní strana',
            'keywords' => 'jazyk, homepage, home, konference',
            'description' => 'Domovská stránka webu.'
        );
        if (isset($params[0]) && $params[0] == 'changeColor') {
            $this->changeColor($params[1]);
            $this->addMessage("Barevné schéma bylo změněno.");
        }

        $this->view = 'home';
    }
}