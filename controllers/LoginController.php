<?php

/**
 * Stránka přihlášení
 */
class LoginController extends Controller {

    public function process($params) {
        $userManager = new UserManager();
        if ($userManager->getUser()) {
            $this->redirect('administration');
        } 
        $this->header = array(
            'title' => 'Přihlášení',
            'description' => 'Tato stránka je určena k přihlášení uživatele.',
            'keywords' => 'přihlášení, login, uživatel');
        if ($_POST)
        {
            try
            {
                $userManager->login($_POST['username'], $_POST['password']);
                $this->redirect('administration');
            }
            catch (UserError $error)
            {
                $this->addMessage($error->getMessage());
            }
        }
        $this->view = 'login';
    }
}