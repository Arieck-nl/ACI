<?php
require 'vendor/autoload.php';
require 'php/general/functions.php';
require 'php/general/config.php';


/**
 * Environment check
 */
$host = $_SERVER['HTTP_HOST'];

/**
 * Error display init
 */

if(HOST == DEV){
    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    error_reporting(-1);
}

/**
 * File setup
 */

require 'php/database/database.php';