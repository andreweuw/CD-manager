<?php

/**
 * Stránka registrace
 */
class RegistrationController extends Controller {

    public function process($params) {
        $this->header = array(
            'title' => 'Registrace',
            'description' => 'Tato stránka je určena k registraci nových uživatelů.',
            'keywords' => 'registrace, register, new, user, nový, uživatel');
        if ($_POST) {
            try {
                $userManager = new UserManager();
                $userManager->register($_POST['username'], $_POST['password'], $_POST['password_again']);
                $userManager->login($_POST['username'], $_POST['password']);
                $this->addMessage('Registrace proběhla úspěšně!');
                $this->redirect('administration');
            }
            catch (UserError $error) {
                $this->addMessage($error->getMessage());
            }
        }

        $this->view = 'registration';
    }
}