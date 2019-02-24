<?php

/**
 * Domovská stránka
 */
class HomeController extends Controller {

    public function process($params) {
        $this->header = array(
            'title' => 'Domovská stránka',
            'keywords' => 'jazyk, homepage, home, konference',
            'description' => 'Domovská stránka webu.'
        );
        
        $this->view = 'home';
    }
}