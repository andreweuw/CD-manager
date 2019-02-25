<?php

/**
 * Stránka přihlášení
 */
class LoginController extends Controller {

    public function process($params) {
        $userManager = new UserManager();
        $this->header = array(
            'title' => 'Přihlášení',
            'description' => 'Tato stránka je určena k přihlášení uživatele.',
            'keywords' => 'přihlášení, login, uživatel');
        if ($_POST) {
            try {
                $userManager->login($_POST['username'], $_POST['password']);
                $this->addMessage('Byl jste úspěšně přihlášen');
                $this->redirect('home');
            }
            catch (UserError $error) {
                $this->addMessage('Bohužel se přihlašování nepovedlo.');
            }
        }
        $this->view = 'login';
    }
}