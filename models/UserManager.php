<?php

/**
 * Třída slouží ke komunikaci s tabulkou 'users'
 */
class UserManager {

    /**
     * Vrátí hashovou funkci pro dané heslo
     */
    public function getHash($pass) {
        // Also adds salt (additional random string)
        // so that the password is not weak.
        return password_hash($pass, PASSWORD_DEFAULT);
    }

    /**
     * Pokud spolu hesla souhlasí, přidá nového uživatele do tabulky
     */
    public function register($name, $pass, $passAgain) {
        if ($pass != $passAgain) {
            throw new UserError('Zadaná hesla spolu nesouhlasí.');
        }
        $user = array(
            'username' => $name,
            'password' => $this->getHash($pass),
            'email' => $email
        );
        try {
            DBWrapper::add('users', $user);
        }
        catch (PDOException $error) {
            throw new UserError('Uživatel s tímto jménem již v systému existuje.');
        }
    }

    /**
     * Blokuje/odblokuje daného uživatele v závislosti na parametru $val (true/false)
     */
    public function block($id, $val) {
        $controller = new UsersController();
        if (DBWrapper::query("UPDATE users SET `blocked` = ? WHERE user_id = ?", array($val, $id)) > 0) {
            $controller->addMessage('Uživatel s id '. $id . ' byl úspěšně blokován');
        }
        else {
            $controller->addMessage('Provádíte zbytečnou operaci, blokování / odblokování nebylo provedeno.');
        }
        
    }

    /**
     * Pokud to jde, přihlásí daného uživatele a vloží ho do $_SESSION['user] proměnné
     */
    public function login($name, $pass) {
        $user = DBWrapper::getRow('
                SELECT user_id, username, password, status, blocked 
                FROM users 
                WHERE username = ?
                ', array($name)
        );
        if (!$user || !password_verify($pass, $user['password'])) {
            throw new UserError('Chybně zadané jméno, nebo heslo.');
        }
        $_SESSION['user'] = $user;
    }

    /**
     * Odhlásí daného uživatele
     */
    public function logout() {
        unset($_SESSION['user']);
    }

    /**
     * Vrátí aktuálního uživatele
     */
    public function getUser() {
        if (isset($_SESSION['user'])) {
            return $_SESSION['user'];
        }
        return null;
    }

    /**
     * Vrátí uživatele podle daného id
     */
    public function getUserById($id) {
        return DBWrapper::getRow('
            SELECT * 
            FROM `users` 
            WHERE `user_id` = ?
            ', array($id)
        );
    }

    /**
     * Vrátí všechny uživatele
     */
    public function getAllUsers() {
        return DBWrapper::getAllRows('
            SELECT * 
            FROM `users`
            ORDER BY `user_id` DESC
        ');
    }

    /**
     * Vrátí všechny uživatele, kteří mají ve statusu napsáno, že jsou recenzent
     */
    public function getAllReviewers() {
        return DBWrapper::getAllRows('
            SELECT * 
            FROM `users` 
            WHERE `status` = ? 
            ORDER BY `user_id` DESC
        ', array('recenzent'));
    }

    /**
     * Vymaže uživatele podle daného id
     */
    public function deleteUser($id) {
        $controller = new UsersController();
        DBWrapper::query('
            DELETE FROM users WHERE user_id = ? 
        ', array($id));
        $controller->addMessage('Uživatel s id '. $id . ' byl úspěšně odstraněn');
    }
}