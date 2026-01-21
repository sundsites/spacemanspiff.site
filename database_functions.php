<?php

$dockerconfig = __DIR__ . "/config/docker.dbconfig.cfg";
$defaultconfig = __DIR__ . "/config/dbconfig.cfg";

if (is_readable($defaultconfig)) {
    include $defaultconfig;
} elseif (is_readable($dockerconfig)) {
    include $dockerconfig;
} else {
    // No config found; DB features will not work
}

// Enable debug mode only if explicitly set in config
if (!empty($debug)) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
}

global $mysqli;

function databaseOpen()
{
    $mysqli = new mysqli($GLOBALS['DBCONFIG']["dbhost"], $GLOBALS['DBCONFIG']["dbuser"], $GLOBALS['DBCONFIG']["dbpassword"], $GLOBALS['DBCONFIG']["dbname"]);
    if ($mysqli->connect_errno) {
        die('Could not connect: ' . $mysqli->connect_error);
    }
    return $mysqli;
}

?>
