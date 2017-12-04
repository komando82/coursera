<?php

session_start();

include 'helpers/pdo_helper.php';

include 'helpers/login_helper.php';

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

    $position = [];
    $education = [];

    $stmt = $pdo->prepare("
        SELECT * FROM position 
        WHERE profile_id = :profile_id
    ");

    $stmt->execute([
        ':profile_id' => $profile_id, 
    ]);

    while ( $row = $stmt->fetch(PDO::FETCH_OBJ) ) 
    {
        $position[] = $row;
    }

    $stmt = $pdo->prepare("
        SELECT * FROM education 
        LEFT JOIN institution ON education.institution_id=institution.institution_id
        WHERE profile_id = :profile_id
    ");

    $stmt->execute([
        ':profile_id' => $profile_id, 
    ]);

    while ( $row = $stmt->fetch(PDO::FETCH_OBJ) ) 
    {
        $education[] = $row;
    }

    $positionLen = count($position);
    $educationLen = count($education);
}

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Ivan Neradovic Autos</title>

        <?php include 'helpers/head_helper.php'; ?>

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

            <?php if($educationLen > 0) : ?>
                <div class="row">
                    <div class="col-sm-2">Educations:</div>
                    <div class="col-sm-8">
                        <ul>
                            <?php for($i=1; $i<=$educationLen; $i++) : ?>
                                <li><?php echo $education[$i-1]->year; ?>: <?php echo $education[$i-1]->name; ?></li>
                            <?php endfor; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>

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
