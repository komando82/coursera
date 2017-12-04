<?php

session_start();

include 'helpers/login_helper.php';

include 'helpers/pdo_helper.php';

if (isset($_REQUEST['term']))
{

	try 
	{
	    $pdo = pdoHelper();
	}
	catch(PDOException $e)
	{
	    echo "Connection failed: " . $e->getMessage();
	    die();
	}

	$stmt = $pdo->prepare('
		SELECT name FROM Institution
		WHERE name LIKE :prefix'
	);

	$stmt->execute([
		':prefix' => $_REQUEST['term']."%"
	]);

	$retval = [];

	while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) 
	{
		$retval[] = $row['name'];
	}

	echo(json_encode($retval, JSON_PRETTY_PRINT));

}


