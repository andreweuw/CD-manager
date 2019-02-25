<?php

/**
 * Uživatelská administrace
 */
class AdministrationController extends Controller
{
    public function process($params)
    {
        $userManager = new UserManager();
        $user = $userManager->getUser();
        //Pouze controller
        if (!empty($params[0]) && $params[0] == 'logout') {
            $userManager->logout();
            $this->addMessage("Byl jste úspěšně odhlášen.");
            $this->redirect('home');
        }

        $user = $userManager->getUser();
        $this->data['username'] = $user['username'];
        $this->view = 'home';
    }
}