<?php

$dockerconfig = __DIR__ . "/config/docker.dbconfig.cfg";
$defaultconfig = __DIR__ . "/config/dbconfig.cfg";

if (is_readable($defaultconfig)) {
    include $defaultconfig;
    if (!empty($debug)) {
        var_dump($defaultconfig);
        error_reporting(E_ALL);
        ini_set('display_errors', '1');
        echo 'The ' . $defaultconfig . ' is present and readable';
    }
} elseif (is_readable($dockerconfig)) {
    include $dockerconfig;
    if (!empty($debug)) {
        var_dump($dockerconfig);
        error_reporting(E_ALL);
        ini_set('display_errors', '1');
        echo 'The ' . $dockerconfig . ' is present and readable';
    }
} else {
    if (!empty($debug)) {
        error_reporting(E_ALL);
        ini_set('display_errors', '1');
        echo "Neither $dockerconfig nor $defaultconfig were found.";
    }
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
