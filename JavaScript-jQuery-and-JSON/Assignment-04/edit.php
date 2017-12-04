<?php

session_start();

include 'helpers/login_helper.php';

include 'helpers/pdo_helper.php';
include 'helpers/validation_helper.php';

// If the user requested logout go back to index.php
if ( isset($_POST['cancel']) ) {
    header('Location: index.php');
    return;
}

$status = false;

if ( isset($_SESSION['status']) ) {
    $status = htmlentities($_SESSION['status']);
    $status_color = htmlentities($_SESSION['color']);

    unset($_SESSION['status']);
    unset($_SESSION['color']);
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

$name = htmlentities($_SESSION['name']);

$_SESSION['color'] = 'red';

if (isset($_REQUEST['profile_id']))
{

    $profile_id = htmlentities($_REQUEST['profile_id']);

    // Check to see if we have some POST data, if we do process it
    if (isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary'])) 
    {
        if(!validationHelper())
        {
            header("Location: edit.php?profile_id=" . htmlentities($_REQUEST['profile_id']));
            return;
        }

        $first_name = htmlentities($_POST['first_name']);
        $last_name = htmlentities($_POST['last_name']);
        $email = htmlentities($_POST['email']);
        $headline = htmlentities($_POST['headline']);
        $summary = htmlentities($_POST['summary']);

        $stmt = $pdo->prepare("
            UPDATE profile
            SET first_name = :first_name, last_name = :last_name, email = :email, headline = :headline, summary = :summary
            WHERE profile_id = :profile_id
        ");

        $stmt->execute([
            ':first_name' => $first_name, 
            ':last_name' => $last_name, 
            ':email' => $email,
            ':headline' => $headline,
            ':summary' => $summary,
            ':profile_id' => $profile_id,
        ]);

        $stmt = $pdo->prepare("
            DELETE FROM position
            WHERE profile_id=:profile_id
        ");

        $stmt->execute([
            ':profile_id' => $profile_id,
        ]);

        $stmt = $pdo->prepare("
            DELETE FROM education
            WHERE profile_id=:profile_id
        ");

        $stmt->execute([
            ':profile_id' => $profile_id,
        ]);

        $rank = 1;

        for($i=1; $i<=9; $i++) {

            if ( ! isset($_POST['year'.$i]) ) continue;
            if ( ! isset($_POST['desc'.$i]) ) continue;

            $year = htmlentities($_POST['year'.$i]);
            $desc = htmlentities($_POST['desc'.$i]);

            $stmt = $pdo->prepare('
                INSERT INTO position (profile_id, rank, year, description)
                VALUES ( :profile_id, :rank, :year, :description)'
            );

            $stmt->execute([
                ':profile_id' => $profile_id,
                ':rank' => $rank, 
                ':year' => $year, 
                ':description' => $desc,
            ]);

            $rank++;
        }

        $rank = 1;

        for ($i=1; $i<=9; $i++) 
        {
            if ( ! isset($_POST['edu_year'.$i]) ) continue;
            if ( ! isset($_POST['edu_school'.$i]) ) continue;

            $edu_year = htmlentities($_POST['edu_year'.$i]);
            $edu_school = htmlentities($_POST['edu_school'.$i]);

            $stmt = $pdo->prepare("
                SELECT * FROM institution
                WHERE name = :edu_school LIMIT 1
            ");

            $stmt->execute([
                ':edu_school' => $edu_school, 
            ]);

            $result = $stmt->fetch(PDO::FETCH_OBJ);

            if ($result)
            {
                $institution_id = $result->institution_id;
            }
            else
            {
                $stmt = $pdo->prepare("
                    INSERT INTO institution (name)
                    VALUES (:name)
                ");

                $stmt->execute([
                    ':name' => $edu_school,
                ]);

                $institution_id = $pdo->lastInsertId();
            }

            $stmt = $pdo->prepare("
                INSERT INTO education (profile_id, institution_id, rank, year)
                VALUES (:profile_id, :institution_id, :rank, :year)
            ");

            $stmt->execute([
                ':profile_id' => $profile_id,
                ':institution_id' => $institution_id,
                ':rank' => $rank, 
                ':year' => $edu_year, 
            ]);

            $rank++;
        }

        $_SESSION['status'] = 'Record edited';
        $_SESSION['color'] = 'green';

        header('Location: index.php');
        return;
    }

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
    $education = [];

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
    </head>
    <body>
        <div class="container">
            <h1>Editing Profile for <?php echo $name; ?></h1>
            <?php
                if ( $status !== false ) 
                {
                    // Look closely at the use of single and double quotes
                    echo(
                        '<p style="color: ' .$status_color. ';" class="col-sm-10 col-sm-offset-2">'.
                            htmlentities($status).
                        "</p>\n"
                    );
                }
            ?>
            <form method="post" class="form-horizontal">
                <div class="form-group">
                    <label class="control-label col-sm-2" for="first_name">First Name:</label>
                    <div class="col-sm-5">
                        <input class="form-control" type="text" name="first_name" id="first_name" value="<?php echo $profile->first_name; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="last_name">Last Name:</label>
                    <div class="col-sm-5">
                        <input class="form-control" type="text" name="last_name" id="last_name" value="<?php echo $profile->last_name; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="email">Email:</label>
                    <div class="col-sm-5">
                        <input class="form-control" type="text" name="email" id="email" value="<?php echo $profile->email; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="headline">Headline:</label>
                    <div class="col-sm-5">
                        <input class="form-control" type="text" name="headline" id="headline" value="<?php echo $profile->headline; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="summary">Summary:</label>
                    <div class="col-sm-5">
                        <textarea class="form-control" name="summary" id="summary" rows="8"><?php echo $profile->summary; ?></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-2">Education:</label>
                    <div class="col-sm-5">
                        <button id="addEdu" class="btn btn-default">+</button>
                    </div>
                </div>

                <div id="edu_fields">
                    <?php if($educationLen > 0) : ?>
                        <?php for($i=1; $i<=$educationLen; $i++) : ?>
                            <div id="edu<?php echo $i; ?>">
                                <div class="form-group">
                                    <label class="control-label col-sm-2">Year:</label>
                                    <div class="col-sm-4">
                                        <input class="form-control" type="text" name="edu_year<?php echo $i; ?>" value="<?php echo $education[$i-1]->year; ?>">
                                    </div>
                                    <div class="col-sm-1">
                                        <button class="btn btn-basic" 
                                            onclick="$('#edu<?php echo $i; ?>').remove();return false;"
                                        >-</button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2">School:</label>
                                    <div class="col-sm-5">
                                        <input class="school form-control" type="text" name="edu_school<?php echo $i; ?>" value="<?php echo $education[$i-1]->name; ?>"/>
                                    </div>
                                </div>
                            </div>
                        <?php endfor; ?>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-2">Position:</label>
                    <div class="col-sm-5">
                        <button id="addPos" class="btn btn-default">+</button>
                    </div>
                </div>
                
                <div id="position_fields">
                    <?php if($positionLen > 0) : ?>
                        <?php for($i=1; $i<=$positionLen; $i++) : ?>
                            <div id="position<?php echo $i; ?>">
                                <div class="form-group">
                                    <label class="control-label col-sm-2">Year:</label>
                                    <div class="col-sm-4">
                                        <input class="form-control" type="text" name="year<?php echo $i; ?>" value="<?php echo $position[$i-1]->year; ?>">
                                    </div>
                                    <div class="col-sm-1">
                                        <button class="btn btn-basic" 
                                            onclick="$('#position<?php echo $i; ?>').remove();return false;"
                                        >-</button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2"></label>
                                    <div class="col-sm-5">
                                        <textarea class="form-control" name="desc<?php echo $i; ?>" rows="8"><?php echo $position[$i-1]->description; ?></textarea>
                                    </div>
                                </div>
                            </div>
                        <?php endfor; ?>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <div class="col-sm-2 col-sm-offset-2">
                        <input class="btn btn-primary" type="submit" value="Save">
                        <input class="btn" type="submit" name="cancel" value="Cancel">
                    </div>
                </div>
            </form>

        </div>

        <script>
            var countPos = <?php echo $positionLen; ?>;
            var countEdu = <?php echo $educationLen; ?>;

            // http://stackoverflow.com/questions/17650776/add-remove-html-inside-div-using-javascript
            $(document).ready(function(){
            //$( window ).load(function() {

                window.console && console.log('Document ready called');

                $('#addPos').click(function(event){
                    // http://api.jquery.com/event.preventdefault/
                    event.preventDefault();
                    if ( countPos >= 9 ) {
                        alert("Maximum of nine position entries exceeded");
                        return;
                    }
                    countPos++;
                    window.console && console.log("Adding position "+countPos);

                    $('#position_fields').append(
                        '<div id="position'+countPos+'"> \
                            <div class="form-group"> \
                                <label class="control-label col-sm-2">Year:</label> \
                                <div class="col-sm-4"> \
                                    <input class="form-control" type="text" name="year'+countPos+'"> \
                                </div> \
                                <div class="col-sm-1"> \
                                    <button class="btn btn-basic" \
                                        onclick="$(\'#position'+countPos+'\').remove();return false;" \
                                    >-</button> \
                                </div> \
                            </div> \
                            <div class="form-group"> \
                                <label class="control-label col-sm-2"></label> \
                                <div class="col-sm-5"> \
                                    <textarea class="form-control" name="desc'+countPos+'" rows="8"></textarea> \
                                </div> \
                            </div> \
                        </div>'
                    );
                });

                $('#addEdu').click(function(event){
                    event.preventDefault();
                    if ( countEdu >= 9 ) {
                        alert("Maximum of nine education entries exceeded");
                        return;
                    }
                    countEdu++;
                    window.console && console.log("Adding education "+countEdu);

                    $('#edu_fields').append(
                        '<div id="edu'+countEdu+'"> \
                            <div class="form-group"> \
                                <label class="control-label col-sm-2">Year:</label> \
                                <div class="col-sm-4"> \
                                    <input class="form-control" type="text" name="edu_year'+countEdu+'"> \
                                </div> \
                                <div class="col-sm-1"> \
                                    <button class="btn btn-basic" \
                                        onclick="$(\'#edu'+countEdu+'\').remove();return false;" \
                                    >-</button> \
                                </div> \
                            </div> \
                            <div class="form-group"> \
                                <label class="control-label col-sm-2">School:</label> \
                                <div class="col-sm-5"> \
                                    <input class="school form-control" type="text" name="edu_school'+countEdu+'" /> \
                                </div> \
                            </div> \
                        </div>'
                    );

                    // Add the even handler to the new ones
                    $('.school').autocomplete({
                        source: "school.php"
                    });

                });

                $('.school').autocomplete({
                    source: "school.php"
                });

            });
        </script>
    </body>
</html>