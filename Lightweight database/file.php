<?php
$remote_address = $_SERVER["REMOTE_ADDR"];
$user_agent = $_SERVER["HTTP_USER_AGENT"];
$time = date("Y-m-d H:i:s");


$value = "Tid: " . $time . "<br>" . " REMOTE_ADDR: " . $remote_address . "<br>" . "HTTP_USER_AGENT: " . $user_agent . "<br>";
echo $value;
$dbh = dba_open('visitors.db','c','qdbm') or die($php_errormsg);
dba_insert($time, $value, $dbh);

$key = dba_firstkey($dbh);
while($key != NULL)
{
	 $key = dba_nextkey($dbh);
	 echo dba_fetch($key,$dbh);
	 echo "<br>";
} 

?>