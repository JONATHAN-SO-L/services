<?php

$user="veco_dvi";
$pwd="Vec83Dv19iSa@";
$host="localhost";
$db="veco_sims_devecchi";

try {
	$con = new PDO("mysql:host=$host;dbname=$db", $user, $pwd);      
	$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}

catch(PDOException $e)
{
	echo "ERROR 100: Fail Connection - " . $e->getMessage();
}

?>