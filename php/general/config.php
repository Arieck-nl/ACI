<?php
/**
 * Database
 */
define('DEV', 'localhost:8888');
define('STAGING', 'aci.arieck.nl');
define('LIVE', 'UNDEFINED');

/**
 * Host
 */
define('HOST', $_SERVER['HTTP_HOST']);

define('DEBUG', false);

$possible_blacklist_query = 'UPDATE aci_term t
INNER JOIN aci_possible_blacklist b ON b.termID = t.termID
SET blacklist = 1
WHERE b.termID = t.termID;
TRUNCATE TABLE aci_possible_blacklist;';