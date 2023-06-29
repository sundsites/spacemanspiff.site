<?
$configpath = dirname(__FILE__)."/config/dbconfig.cfg";

$debug = FALSE;

if (is_readable($configpath)) {
	if ($debug) {
		var_dump($configpath);
		error_reporting(E_ALL);
		ini_set('display_errors', '1');
    echo 'The '.$configpath.' is readable';
	}
		include $configpath;
} else {
    echo 'The ' . $configpath . ' is not readable';
		echo $configpath;
		exit;
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

function sql2date($sqldate)		// convert sql date to PHP date
{
	return strtotime($sqldate);
}

function date2sql($intDate)		// convert PHP date to sql date
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

function strSQL($strIn)			// escape SQL string
{
	$strIn = str_replace("\\","\\\\",$strIn);
	return str_replace("'","\\'",$strIn);
	// return ereg_replace("'","''",ereg_replace("\'","'",$strIn));
}

?>
