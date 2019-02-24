<?php

/**
 * Router rozhoduje o přesměrování na konkrétní stránku, nebo chybu.
 */
class RouterController extends Controller {

    protected $controller;

    public function process($params) {
        $parsedURL = $this->parseURL($params[0]);

        if (empty($parsedURL[0])) {
            $this->redirect('home');
        }
        
        $controllerClass = $this->dashToCamel(array_shift($parsedURL)) . 'Controller';
        
        if (file_exists('controllers/' . $controllerClass . '.php')) {
            $this->controller = new $controllerClass;
        }
        else if (file_exists('articles/' . $params[2])) {
            $this->redirect('home');
        }
        else {
            $this->addMessage($params[0] . ', ' . $params[1] . ', ' . $params[2]);
            $this->redirect('error');
        }

        $this->controller->process($parsedURL);

        $this->data['title'] = $this->controller->header['title'];
        $this->data['description'] = $this->controller->header['description'];
        $this->data['keywords'] = $this->controller->header['keywords'];
        $this->data['messages'] = $this->getMessages();

        $userManager = new UserManager();
        $user = $userManager->getUser();
        $this->data['curUsername'] = $user['username'];
        $this->data['curStatus'] = $user['status'];
        $this->view = 'layout';
    }

    /**
     * Vrátí jednotlivé parametry URL po escapovacích znacích '/'
     */
    private function parseURL($url) {
        $parsedURL = parse_url($url);
        $parsedURL["path"] = ltrim($parsedURL["path"], "/");
        $parsedURL["path"] = trim($parsedURL["path"]);
        $disconnectedPath = explode("/", $parsedURL["path"]);
        return $disconnectedPath;
    }

    /**
     * Převede tuto-adresu na tutoAdresu
     */
    private function dashToCamel($text) {
        $str = str_replace('-', ' ', $text);
        $str = ucwords($str);
        $str = str_replace(' ', '', $str);
        return $str;
    }
}