<?php

session_start();

include 'helpers/pdo_helper.php';

if ( ! isset($_SESSION['name']) ) {
    die("Not logged in");
}

try 
{
    $pdo = pdoHelper();
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

    $stmt = $pdo->prepare("
        SELECT * FROM position 
        WHERE profile_id = :profile_id
    ");

    $stmt->execute([
        ':profile_id' => $profile_id, 
    ]);

    $position = [];

    while ( $row = $stmt->fetch(PDO::FETCH_OBJ) ) 
    {
        $position[] = $row;
    }

    $positionLen = count($position);
}

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Ivan Neradovic Autos</title>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

        <script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>

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

            <?php if($positionLen > 0) : ?>
                <div class="row">
                    <div class="col-sm-2">Positions:</div>
                    <div class="col-sm-8">
                        <ul>
                            <?php for($i=1; $i<=$positionLen; $i++) : ?>
                                <li><?php echo $position[$i-1]->year; ?>: <?php echo $position[$i-1]->description; ?></li>
                            <?php endfor; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>

            <p><a href="index.php">Done</a></p>

        </div>
    </body>
</html>
