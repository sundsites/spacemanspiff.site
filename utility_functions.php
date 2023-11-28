<?php

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
