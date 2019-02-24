<?php

/**
 * Články jednotlivého autora
 */
class CdsController extends Controller {

    public function process($params) {
        $this->header = array(
            'title' => 'Moje CD knihovna',
            'description' => 'Tato stránka je určena k prohlédnutí knihovny CD.',
            'keywords' => 'prohlednuti, CD, knihovna');

        $cdManager = new CDManager();
        $userManager = new UserManager();
        $curUser = $userManager->getUser();

        if (!empty($params[0]) && $params[0] == 'editor') {
            $cd = $cdManager->getcd($params[0]);
            $cdManager->deletecd($cd['cd_id']);
            $cdManager->deletePdf($cd['file_name']);
            $this->redirect('editor');
        }
        else if (!empty($params[0]) && $params[0] == 'printAll') {
            $cd = $cdManager->getCD($params[0]);
            $cdManager->deleteCD($cd['cd_id']);
            $cdManager->deletePdf($cd['file_name']);
            $this->redirect('editor');
        }

        $cds = $cdManager->getMyCDs($curUser['user_id']);
        $this->data['cds'] = $cds;
        $this->view = 'myCds';
    }
}