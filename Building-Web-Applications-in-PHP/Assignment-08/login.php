<?php // Do not put any HTML above this line

if ( isset($_POST['cancel'] ) ) 
{
    // Redirect the browser to game.php
    header("Location: index.php");
    return;
}

$salt = 'XyZzy12*_';
$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';  // Pw is php123

$failure = false;  // If we have no POST data

// Check to see if we have some POST data, if we do process it
if ( isset($_POST['who']) && isset($_POST['pass']) ) 
{
    if ( strlen($_POST['who']) < 1 || strlen($_POST['pass']) < 1 ) 
    {
        $failure = "User name and password are required";
    } 
    else 
    {
        $check = hash('md5', $salt.$_POST['pass']);
        if ( $check == $stored_hash ) 
        {
            // Redirect the browser to game.php
            header("Location: game.php?name=".urlencode($_POST['who']));
            return;
        } 
        else 
        {
            $failure = "Incorrect password";
        }
    }
}

// Fall through into the View
?>
<!DOCTYPE html>
<html>
    <head>
        <?php require_once "bootstrap.php"; ?>
        <title>Ivan Neradovic's Login Page</title>
    </head>
    <body>
        <div class="container">

            <h1>Please Log In</h1>
            <?php
                // Note triple not equals and think how badly double
                // not equals would work here...
                if ( $failure !== false ) 
                {
                    // Look closely at the use of single and double quotes
                    echo('<p style="color: red;">'.htmlentities($failure)."</p>\n");
                }
            ?>

            <form method="POST" class="form-horizontal">
                <div class="form-group">
                    <label class="control-label col-sm-2" for="who">User Name:</label>
                    <div class="col-sm-3">
                        <input class="form-control" type="text" name="who" id="nam">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="id_1723">Password:</label>
                    <div class="col-sm-3">
                        <input class="form-control" type="text" name="pass" id="id_1723">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-2 col-sm-offset-2">
                        <input class="btn btn-primary" type="submit" value="Log In">
                        <input class="btn" type="submit" name="cancel" value="Cancel">
                    </div>
                </div>
            </form>

            <p>
                For a password hint, view source and find a password hint
                in the HTML comments.
                <!-- Hint: The password is php123. -->
            </p>

        </div>
    </body>
</html>
