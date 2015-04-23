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
    $database_setup['database_name'] = 'deb67518_arieck';
    $database_setup['username'] = 'deb67518_rick';
    $database_setup['password'] = 'tick3nrim';
} //live
elseif (HOST == LIVE) {
    $database_setup['database_name'] = '';
    $database_setup['username'] = '';
    $database_setup['password'] = '';
}

$database = new medoo(array(
    // required
    'database_type' => 'mysql',
    'database_name' => $database_setup['database_name'],
    'server' => 'localhost',
    'username' => $database_setup['username'],
    'password' => $database_setup['password'],
    'charset' => 'utf8_unicode_ci',

    // optional
    'port' => 3306,
    // driver_option for connection, read more from http://www.php.net/manual/en/pdo.setattribute.php
    'option' => array(
        PDO::ATTR_CASE => PDO::CASE_NATURAL
    )
));

unset($database_setup);