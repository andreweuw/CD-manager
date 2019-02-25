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

        // Jednoduché přepínání stavů (souborů css)
        if (isset($params[0]) && $params[0] == 'changeColor') {
            if ($_SESSION['color'] == 'red')
                $_SESSION['color'] = 'blue';
            else if ($_SESSION['color'] == 'blue')
                $_SESSION['color'] = 'red';
            $this->redirect('home');
        }

        $this->view = 'home';
    }
}