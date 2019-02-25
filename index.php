<?php
mb_internal_encoding("UTF-8");
session_start();// PHP starts sending cookies with the id of the user relation

function autoLoad($class)
{
    if (preg_match('/Controller$/', $class))
        require("controllers/" . $class . ".php");
    else
        require("models/" . $class . ".php");
}

spl_autoload_register("autoLoad");
DBWrapper::connect("127.0.0.1", "root", "", "cds");
$router = new RouterController();
$router->process(array($_SERVER['REQUEST_URI']));

if (!isset($_SESSION['color'])) // Implicitní barva
    $_SESSION['color'] = 'blue';

$router->printView();