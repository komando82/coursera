<?php
	
session_start();

$logged_in = false;
$autos = [];

if (isset($_SESSION['name']) ) {

	$logged_in = true;
	$status = false;

	if ( isset($_SESSION['status']) ) {
		$status = htmlentities($_SESSION['status']);
		$status_color = htmlentities($_SESSION['color']);

		unset($_SESSION['status']);
		unset($_SESSION['color']);
	}

	try 
	{
	    $pdo = new PDO("mysql:host=localhost;dbname=coursera_building_database_applications_in_php", "root", "root");
	    // set the PDO error mode to exception
	    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	    $all_autos = $pdo->query("SELECT * FROM autos");

		while ( $row = $all_autos->fetch(PDO::FETCH_OBJ) ) 
		{
		    $autos[] = $row;
		}
	}
	catch(PDOException $e)
	{
	    echo "Connection failed: " . $e->getMessage();
	    die();
	}
}

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Ivan Neradovic</title>
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	</head>
	<body>
		<div class="container">
			<h1>Welcome to the Automobiles Database</h1>

			<?php if (!$logged_in) : ?>
				<p>
					<a href="login.php">Please log in</a>
				</p>
				<p>
					Attempt
					<a href="add.php">add data</a> 
					without logging in.
				</p>
			<?php else : ?>

				<?php
	                if ( $status !== false ) 
	                {
	                    // Look closely at the use of single and double quotes
	                    echo(
	                        '<p style="color: ' .$status_color. ';" class="col-sm-10">'.
	                            $status.
	                        "</p>\n"
	                    );
	                }
	            ?>

				<?php if (empty($autos)) : ?>
					<p>No rows found</p>
				<?php else : ?>
					<p>
						<table class="table">
							<thead>
								<tr>
									<th>Make</th>
									<th>Model</th>
									<th>Year</th>
									<th>Mileage</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($autos as $auto) : ?>
			                        <tr>
			                        	<td><?php echo $auto->make; ?></td>
										<td><?php echo $auto->model; ?></td>
										<td><?php echo $auto->year; ?></td>
										<td><?php echo $auto->mileage; ?></td>
										<td><a href="edit.php?autos_id=<?php echo $auto->auto_id; ?>">Edit</a> / <a href="delete.php?autos_id=<?php echo $auto->auto_id; ?>">Delete</a></td>
			                        </tr>
			                    <?php endforeach; ?>
							</tbody>
						</table>
					</p>
				<?php endif; ?>
				<p>
					<a href="add.php">Add New Entry</a>
				</p>
				<p>
					<a href="logout.php">Logout</a>
				</p>

			<?php endif; ?>	
		</div>
	</body>
</html>

