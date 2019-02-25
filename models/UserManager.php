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
    public function register($name, $email, $pass, $passAgain) {
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
     * Pokud to jde, přihlásí daného uživatele a vloží ho do $_SESSION['user] proměnné
     */
    public function login($name, $pass) {
        $user = DBWrapper::getRow('
                SELECT * 
                FROM users 
                WHERE username = ?
                ', array($name)
        );
        if (!password_verify($pass, $user['password'])) {
            throw new UserError('Chybně zadané heslo.');
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

    public function getAllNames() {
        return DBWrapper::query('
            SELECT username 
            FROM users');
    }
}