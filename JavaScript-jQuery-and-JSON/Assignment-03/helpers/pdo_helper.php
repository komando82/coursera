<?php

function pdoHelper() 
{
	$pdo = new PDO("mysql:host=localhost;dbname=coursera_javascript_jquery_and_json", "root", "root");
    // set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $pdo;
}
