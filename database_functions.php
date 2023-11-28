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
  $mysqli = new mysqli($GLOBALS['DBCONFIG']["dbhost"], $GLOBALS['DBCONFIG']["dbuser"], $GLOBALS['DBCONFIG']["dbpassword"], $GLOBALS['DBCONFIG']["dbname"]);
  if ($mysqli->connect_errno)
  {
    die('Could not connect: ' . $mysqli->connect_error);
  }
  return $mysqli;
}

?>
