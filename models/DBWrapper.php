<?php

/**
 * Třída je určena k zajištění připojení k dané databázi a následné zacházení s ní
 */
class DBWrapper {

    private static $connection;

    private static $settings = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_EMULATE_PREPARES => false,
    );

    /**
     * Vytvoří instanci PDO se standardními parametry
     * a uloží ji ve statické proměnné $connection.
     * Taktéž zabraňuje dalšímu připojení.
     */
    public static function connect($host, $user, $password, $database) {
        if (!isset(self::$connection)) {
            self::$connection = @new PDO(
                "mysql:host=$host;dbname=$database",
                $user,
                $password,
                self::$settings
            );
        }
    }

    /**
     * Vrátí řádek databáze v závislosti na dotaze
     */
    public static function getRow($query, $params = array()) {
        $ret = self::$connection->prepare($query);
        $ret->execute($params);
        return $ret->fetch();
    }

    /**
     * Vrátí všechny řádky databáze podle dotazu
     */
    public static function getAllRows($query, $params = array()) {
        $ret = self::$connection->prepare($query);
        $ret->execute($params);
        return $ret->fetchAll();
    }

    /**
     * Vrátí jeden sloupec databáze v závislosti na dotazu
     */
    public static function getColumn($query, $params = array()) {
        $result = self::getRow($query, $params);
        return $result[0];
    }

    /**
     * Provede dotaz a vrátí počet ovlivněných řádků
     */
    public static function query($query, $params = array()) {
        $ret = self::$connection->prepare($query);
        $ret->execute($params);
        return $ret->rowCount();
    }

    /**
     * Přidá do dané tabulky její jednu položku
     */
    public static function add($table, $params = array()) {
        return self::query("INSERT INTO `$table` (`".
                            implode('`, `', array_keys($params)).
                            "`) VALUES (".str_repeat('?,', sizeOf($params)-1)."?)",
                            array_values($params)
        );
    }

    /**
     * Upraví řádek podle dané podmínky dané tabulky na dané pole parametrů
     */
    public static function alter($table, $values = array(), $condition, $params = array()) {   
        return self::query("UPDATE `$table` SET `".
                            implode('` = ?, `', array_keys($values)).
                            "` = ? " . $condition,
                            array_merge(array_values($values), $params)
        );
    }

    /**
     * Provede manuální aktualizaci tabulky
     */
    public static function update($table) {
        return self::query("UPDATE * FROM `?`", $table);
    }
}