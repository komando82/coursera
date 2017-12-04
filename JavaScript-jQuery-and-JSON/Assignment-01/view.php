<?php

session_start();

if ( ! isset($_SESSION['name']) ) {
    die("Not logged in");
}

try 
{
    $pdo = new PDO("mysql:host=localhost;dbname=coursera_javascript_jquery_and_json", "root", "root");
    // set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{
    echo "Connection failed: " . $e->getMessage();
    die();
}

if (isset($_REQUEST['profile_id']))
{
    $profile_id = htmlentities($_REQUEST['profile_id']);

    $stmt = $pdo->prepare("
        SELECT * FROM profile 
        WHERE profile_id = :profile_id
    ");

    $stmt->execute([
        ':profile_id' => $profile_id, 
    ]);

    $profile = $stmt->fetch(PDO::FETCH_OBJ);
}

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Ivan Neradovic Autos</title>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

        <style type="text/css">
            .row {padding: 5px 0;}
        </style>
    </head>
    <body>
        <div class="container">

            <h1>Profile information</h1>

            <div class="row">
                <div class="col-sm-2">First Name:</div>
                <div class="col-sm-4">
                    <?php echo $profile->first_name; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2">Last Name:</div>
                <div class="col-sm-4">
                    <?php echo $profile->last_name; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2">Email:</div>
                <div class="col-sm-4">
                    <?php echo $profile->email; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2">Headline:</div>
                <div class="col-sm-4">
                    <?php echo $profile->headline; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2">Last Name:</div>
                <div class="col-sm-8">
                    <?php echo $profile->summary; ?>
                </div>
            </div>

            <p><a href="index.php">Done</a></p>

        </div>
    </body>
</html>
