<?php
// Independent configuration
require 'medoo.php';

/**
 * Database setup for different hosts
 */
$database_setup = Array();

//dev
$database_setup['database_name'] = 'aci';
$database_setup['username'] = 'root';
$database_setup['password'] = 'root';

//staging
if (HOST == STAGING) {
    $database_setup['database_name'] = 'arieck.nl';
    $database_setup['username'] = 'deb67518';
    $database_setup['password'] = 'tick3nrim';
} //live
elseif (HOST == LIVE) {
    $database_setup['database_name'] = '';
    $database_setup['username'] = '';
    $database_setup['password'] = '';
}

$database = new medoo([
    // required
    'database_type' => 'mysql',
    'database_name' => $database_setup['database_name'],
    'server' => 'localhost',
    'username' => $database_setup['username'],
    'password' => $database_setup['password'],
    'charset' => 'utf8',

    // optional
    'port' => 3306,
    // driver_option for connection, read more from http://www.php.net/manual/en/pdo.setattribute.php
    'option' => [
        PDO::ATTR_CASE => PDO::CASE_NATURAL
    ]
]);

unset($database_setup);