<?php

/**
 * Editor CD
 */
class EditorController extends Controller {    

    public function process($params) {
        $this->header = array(
            'title' => 'Editor CD',
            'keywords' => 'Tato stránka je určena k editaci a vytváření CD.',
            'description' => 'editace, edit, cd, uzivatel');

        $cdManager = new CDManager();

        if (!empty($params[0])) { // Přišlo již stávající CD
            $cd = $cdManager->getCDById($params[0]);
            $cdManager->deleteCD($params[0]);
            $this->data['cd'] = $cd;
            $this->view = ('editor');
        }
        else { // Vytvářříme nové CD
            $cd = array(
                'nazev' => '',
                'delka' => '',
                'autor' => '',
                'datum_vydani' => ''
            );
        }

        if ($_POST) { // Na submit uložíme CD
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

        // data['cd'] je bud prázdné nebo je v této proměnné CD k editaci.
        $this->data['cd'] = $cd;
        $this->view = 'editor';
    }
}