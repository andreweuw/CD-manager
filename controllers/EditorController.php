<?php

/**
 * Editor článků
 */
class EditorController extends Controller {    

    public function process($params) {
        $this->header = array(
            'title' => 'Editor článků',
            'keywords' => 'Tato stránka je určena k editaci a vytváření článků.',
            'description' => 'editace, edit, cd, článek, admin');

        $cdManager = new CDManager();

        if (!empty($params[0])) {
            $cd = $cdManager->getCDById($params[0]);
            $cdManager->deleteCD($params[0]);
            $this->data['cd'] = $cd;
            $this->view = ('editor');
        }
        else {
            $cd = array(
                'nazev' => '',
                'delka' => '',
                'autor' => '',
                'datum_vydani' => ''
            );
        }

        if ($_POST) {

            $cdManager->saveCD(
                $_POST['nazev'],
                $_POST['delka'],
                $_POST['autor'],
                $_POST['datum_vydani']
            );
            $cd = array(
                'nazev' => $_POST['nazev'],
                'delka' => $_POST['delka'],
                'autor' => $_POST['autor'],
                'datum_vydani' => $_POST['datum_vydani'],
            );
 
            $this->redirect('cds/printAll');
        }
        // // Je zadané URL článku k editaci
        // else if (!empty($params[1])) {
        //     $bufferedcd = $cdManager->getcd($params[1]);

        //     if ($bufferedcd) {
        //         $cd = $bufferedcd;
        //     }
        // }

        $this->data['cd'] = $cd;
        $this->view = 'editor';
    }
    
}