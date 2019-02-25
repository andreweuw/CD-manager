<?php

/**
 * Třída pro zacházení s tabulkou 'CDs'
 */
class CDManager {
    
    /**
     * Přidá CD
     */
    public function addCD($params = array()) {
        return DBWrapper::add('CDs', $params);
    }

    /**
     * Vrátí CD podle dané url adresy
     */
    public function getCD($nazev) {
        return DBWrapper::getRow('
            SELECT * 
            FROM `cds` 
            WHERE `nazev` = ?
            ', array($nazev)
        );
    }

    /**
     * Vrátí CD podle daného id CD
     */
    public function getCDById($id) {
        return DBWrapper::getRow('
            SELECT * 
            FROM `cds` 
            WHERE `cd_id` = ?
            ', array($id)
        );
    }

    /**
     * Vrátí všechny CD z tabulky
     */
    public function getCDs() {
        return DBWrapper::getAllRows('
            SELECT * 
            FROM `cds` 
            ORDER BY `CD_id` DESC
        ');
    }

    /**
     * Vrátí všechny CD uživatele s daným id uživatele
     */
    public function getMyCDs($id) {
        return DBWrapper::getAllRows('
            SELECT * 
            FROM `cds` 
            WHERE FK_user_id = ? 
            ORDER BY `CD_id` DESC
            ', array($id)
        );
    }

    /**
     * Uloží daný CD do databáze, přehlednější způsob předání parametrů by byl polem.
     * Pokud takové CD již existuje, vymaže se a přidá se toto, nové.
     */
    public function saveCD($nazev, $delka, $autor, $datum_vydani) {
        $CD = $this->getCD($nazev);
        if ($CD) {
            $this->deleteCD($CD['cd_id']);
        }

        $CDController = new CdsController();
        $userManager = new UserManager();
        $user = $userManager->getUser();
        $CD = array(
            'nazev' => $nazev,
            'delka' => $delka,
            'autor' => $autor,
            'datum_vydani' => $datum_vydani,
            'FK_user_id' =>  $user['user_id']
        );

        try {
            DBWrapper::add('cds', $CD);
            $CDController->addMessage('CD bylo úspěšně uloženo.');
        }
        catch (PDOException $error) {
            $CDController->addMessage('Nepodařilo se přidat CD.');
        }
    }

    /**
     * Vymaže CD z databáze na základě jeho id
     */
    public function deleteCD($id) {
        DBWrapper::query('
            DELETE FROM cds WHERE cd_id = ? 
        ', array($id));
    }
}