<?php

/**
 * Třída pro zacházení s tabulkou 'CDs'
 */
class CDManager {
    
    /**
     * Přidá článek
     */
    public function addCD($params = array()) {
        return DBWrapper::add('CDs', $params);
    }

    /**
     * Vrátí článek podle dané url adresy
     */
    public function getCD($nazev) {
        return DBWrapper::getRow('
            SELECT * 
            FROM `CDs` 
            WHERE `nazev` = ?
            ', array($nazev)
        );
    }

    /**
     * Vrátí článek podle daného id článku
     */
    public function getCDById($id) {
        return DBWrapper::getRow('
            SELECT * 
            FROM `CDs` 
            WHERE `CD_id` = ?
            ', array($id)
        );
    }

    /**
     * Vrátí všechny články z tabulky
     */
    public function getCDs() {
        return DBWrapper::getAllRows('
            SELECT * 
            FROM `cds` 
            ORDER BY `CD_id` DESC
        ');
    }

    /**
     * Vrátí všechny ty články, které byly zveřejněny.
     */
    public function getAllPublished() {
        return DBWrapper::getAllRows('
            SELECT * 
            FROM `CDs` 
            WHERE published = 1 
            ORDER BY `CD_id` DESC
            ');
    }

    /**
     * Vrátí všechny články uživatele s daným id uživatele
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
     * Vrátí největší počet recenzentů ze všech článků
     */
    public function getMaxRev() {
        return DBWrapper::getRow('
            SELECT MAX(reviewer_count) 
            as max 
            FROM CDs;');
    }

    /**
     * Uloží daný článek do databáze, přehlednější způsob předání parametrů by byl polem!
     * Pokud takový článek již existuje, vymaže se a přidá se tento, nový
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
     * Vymaže článek z databáze na základě jeho id
     */
    public function deleteCD($id) {
        DBWrapper::query('
            DELETE FROM cds WHERE cd_id = ? 
        ', array($id));
    }

    /**
     * Vymaže článek z lokálního serveru, podadresáře /CDs/
     */
    public function deletePdf($fileName) {
        $directory = $_SERVER['DOCUMENT_ROOT']."/CDs/";
        unlink($directory . $fileName);
    }

    /**
     * Vrátí všechny články, které obsahují dané id jako podřetězec atributu 'reviewers_ids'
     */
    public function getCDsForReview($id) {
        return DBWrapper::getAllRows("
            SELECT * 
            FROM `cds` 
            WHERE reviewers_ids REGEXP ?", array($id)
        );
    }
}