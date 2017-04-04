<?php

//phpinfo();

$hostname = "db";
$port = 1433;
$dbname = "tempdb";
$username = "sa";
$pw = "Password123";
try{
	$dbh = new PDO ("dblib:host=$hostname:$port;dbname=$dbname","$username","$pw");
	$stmt = $dbh->query('SELECT @@VERSION');
    $result = $stmt->fetchAll();
}catch(\Exception $e){
	die ($e->getMessage());
}

var_dump($result);