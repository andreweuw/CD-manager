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
    public function getCD($url) {
        return DBWrapper::getRow('
            SELECT * 
            FROM `CDs` 
            WHERE `url` = ?
            ', array($url)
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
            FROM `CDs` 
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
            FROM `CDs` 
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
     * Nastaví artibut 'published' daného článku na hodnotu 'true'
     */
    public function setPublished($id) {
        $nextRank = true;
        DBWrapper::query("UPDATE CDs SET `published` = ? WHERE CD_id = ?", array($nextRank, $id));
    }

    /**
     * Stáhne uživateli soubor pdf z dané cesty pomocí protokolu FTP na lokální úložiště uživatele
     */
    public function downloadPdf($path) {
        if (file_exists($path)) {
            $file = $path;
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($file).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            readfile($file);
            exit;
        }
    }

    /**
     * Zvýší stav článku.
     */
    public function raiseState($id) {
        $CD = DBWrapper::getRow('
            SELECT * 
            FROM CDs 
            WHERE CD_id = ?
            ', array($id)
        );
        
        $status = $CD['status'];
        if ($status == 'k recenzi') {
            $nextRank = 'čeká na rozhodnutí administrátora';
        } else if ($status == 'čeká na rozhodnutí administrátora') {
            $nextRank = 'schváleno';
        }
        if ($nextRank) {
            DBWrapper::query("UPDATE CDs SET `status` = ? WHERE CD_id = ?", array($nextRank, $id));
        }
    }

    /**
     * Sníží stav článku
     */
    public function lowerState($id) {
        $CD = DBWrapper::getRow('
            SELECT * 
            FROM CDs 
            WHERE CD_id = ?
            ', array($id)
        );
        $nextRank = null;
        $status = $CD['status'];
        if ($status == 'čeká na rozhodnutí administrátora') {
            $nextRank = 'k recenzi';
        }
        if ($nextRank) {
            DBWrapper::query("UPDATE CDs SET `status` = ? WHERE CD_id = ?", array($nextRank, $id));
        }
    }
    
    /**
     * Aktualizuje danému článku atributy 'reviewers_ids' a 'reviewer_count' na základě hodnot předaného pole $reviewers
     */
    public function updateReviewers($reviewers = array(), $count, $id) {
        $reviewers_ids = $reviewers[0];
        // Pouze pro výpis
        $CDController = new CDController();
        foreach(array_slice($reviewers, 1) as $reviewer) {
            $reviewers_ids .= ("_" . $reviewer);
        }

        // výpis recenzentů končí prázdným recenzentem
        if (substr($reviewers_ids, -1) === "_") {
            $CDController->addMessage("Musíte nejdříve vybrat nového nebo odebrat prázdnou kolonku pro recenzenta.");
        }
        else {
            DBWrapper::query("UPDATE CDs SET `reviewers_ids` = ? WHERE CD_id = ?", array($reviewers_ids, $id));
            DBWrapper::query("UPDATE CDs SET `reviewer_count` = ? WHERE CD_id = ?", array($count, $id));
            $CDController->addMessage("Recenzenti byli úspěšně přiřazeni ke článku.");
        }
    }

    /**
     * Zvýší hodnotu 'reviewer_count' v tabulce článků danému článku o 1, nebo -1 v závislosti na parametru $plus
     */
    public function updateRevCount($id, $plus) {
        $CD = $this->getCDById($id);
        $new = $CD['reviewer_count'];
        if ($plus == true) {
            $new++;
        }
        else {
            $new--;
        }
        DBWrapper::query("UPDATE CDs SET `reviewer_count` = ? WHERE CD_id = ?", array($new, $id));
    }

    /**
     * Vrátí abstract daného článku v závislosti na jeho id
     */
    public function getAbstract($id) {
        return DBWrapper::getAllRows("
            SELECT abstract 
            FROM `CDs` 
            WHERE CD_id = ?", array($id)
        );
    }

    /**
     * Uloží daný článek do databáze, přehlednější způsob předání parametrů by byl polem!
     * Pokud takový článek již existuje, vymaže se a přidá se tento, nový
     */
    public function saveCD($title, $url, $abstract, $description, $keywords, $file_name) {
        $CD = $this->getCD($url);
        if ($CD) {
            $this->deleteCD($CD['CD_id']);
        }

        $CDController = new CDController();
        $userManager = new UserManager();
        $user = $userManager->getUser();
        $CD = array(
            'title' => $title,
            'abstract' => $abstract,
            'url' => $url,
            'description' => $description,
            'keywords' => $keywords,
            'status' => 'k recenzi',
            'FK_user_id' =>  $user['user_id'],
            'file_name' => $file_name
        );

        try {
            DBWrapper::add('CDs', $CD);
            $CDController->addMessage('Článek byl úspěšně uložen.');
        }
        catch (PDOException $error) {
            $CDController->addMessage('Článek s tímto názvem již existuje.');
        }
    }

    /**
     * Vymaže článek z databáze na základě jeho id
     */
    public function deleteCD($id) {
        DBWrapper::query('
            DELETE FROM CDs WHERE CD_id = ? 
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
            FROM `CDs` 
            WHERE reviewers_ids REGEXP ?", array($id)
        );
    }
}