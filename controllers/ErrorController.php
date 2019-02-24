<?php

/**
 * Error
 */
class ErrorController extends Controller {

    public function process($params) {
        header("HTTP/1.0 404 Not Found");
        $this->header = array(
            'title' => 'Error 404',
            'description' => 'Chybová stránka.',
            'keywords' => 'chyba, error, catch');
        $this->view = 'error';
    }
}