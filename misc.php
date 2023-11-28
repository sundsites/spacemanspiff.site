<?php

$dockerconfig = dirname(__FILE__)."/config/docker.dbconfig.cfg";
$defaultconfig = dirname(__FILE__)."/config/dbconfig.cfg";

if (file_exists($defaultconfig)) {
    if (is_readable($defaultconfig)) {
        if ($debug) {
            var_dump($defaultconfig);
            error_reporting(E_ALL);
            ini_set('display_errors', '1');
        echo 'The '.$defaultconfig.' is present and readable';
        }
            include $defaultconfig;
}
} elseif (file_exists($dockerconfig)) {
    if (is_readable($dockerconfig)) {
        if ($debug) {
            var_dump($dockerconfig);
            error_reporting(E_ALL);
            ini_set('display_errors', '1');
        echo 'The '.$dockerconfig.' is present and readable';
        }
            include $dockerconfig;
    }
} else {
    // Neither file exists
    echo "Neither $dockerconfig nor $defaultconfig were found.";
}

global $mysqli;

function databaseOpen()
{
    // mysqli
    //$mysqli = new mysqli($_CONFIG["dbhost"], $_CONFIG["dbuser"], $_CONFIG["dbpassword"], $_CONFIG["dbname"]);
    $mysqli = new mysqli($GLOBALS['DBCONFIG']["dbhost"], $GLOBALS['DBCONFIG']["dbuser"], $GLOBALS['DBCONFIG']["dbpassword"], $GLOBALS['DBCONFIG']["dbname"]);
    if (mysqli_connect_errno())
        die('Could not connect: ' . mysqli_connect_error());
}

function sql2date($sqldate)     // convert sql date to PHP date
{
    return strtotime($sqldate);
}

function date2sql($intDate)     // convert PHP date to sql date
{
    if ($intDate && $intDate != -1)
    {
        // echo '$intDate = '.$intDate.', $intDate != -1 = '.($intDate != -1).'<BR>';
        return date('Y-m-d H:i:s',$intDate);
    } else {
        // echo '$intDate = NULL!<BR>';
        return '0000-00-00 00:00:00';
    }
}

function strSQL($strIn)         // escape SQL string
{
    $strIn = str_replace("\\","\\\\",$strIn);
    return str_replace("'","\\'",$strIn);
    // return ereg_replace("'","''",ereg_replace("\'","'",$strIn));
}

?>
