<?php

/**
 * Třída pro práci s CD
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
            $this->redirect('editor'); //Přidat nové CD
        }
        else if (!empty($params[1]) && $params[1] == 'editor') {
            $this->redirect('editor/' . $params[0]); //Editovat stávající CD
        }
        else if (!empty($params[0]) && $params[0] == 'printAll') {
            $cds = $cdManager->getMyCDs($curUser['user_id']); // Všechna CD uživatele, který je přihlášen

            // Stránkování
            if (!empty($params[1]) && $params[1] == 'show') {
                $this->data['cds'] = $this->getNthFive($params[2], $cds);
            }
            else {
                $this->data['cds'] = $this->getNthFive(1, $cds);
            }
            // Všechna CD pro hledání
            $this->data['allCds'] = $cds;
            $this->data['numberOfPages'] = $this->getNofPages($cds);
            $this->view = 'myCds';
        }
        else if (!empty($params[1]) && $params[1] == 'remove') { // Odstranit danné CD
            $cdManager->deleteCD($params[0]);
            $this->redirect('cds/printAll');
        }
    }

    /**
     * Vrátí počet potřebných stránek na zobrazení všech vlastních CD.
     */
    public function getNofPages($cds) {
        $nOfCds = 0;
        foreach($cds as $cd) {
            $nOfCds++;
        }
        $result = $nOfCds / 5;
        return $result;
    }

    /**
     * Vrátí nejvýše pětici danné hodnosti (kolikátou stranu potřebujeme).
     */
    public function getNthFive($rank, $array) {
        $arrayOfFive;

        for ($i = 0; $i < 5; $i++) {
            if (isset($array[$i * ($rank)])) {
                if ($rank == 1) { // Násobili bychom nulou...
                    $arrayOfFive[$i] = $array[$i];
                }
                else if (isset($array[$i + 5 * ($rank - 1)])) { // Pohyb po 2D matici
                    $arrayOfFive[$i] = $array[$i + 5 * ($rank - 1)];
                }
            }
        }
        return $arrayOfFive;
    }
}