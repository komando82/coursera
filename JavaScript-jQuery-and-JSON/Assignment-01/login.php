<?php // Do not put any HTML above this line

session_start();

if ( isset($_POST['cancel'] ) ) 
{
    // Redirect the browser to game.php
    header("Location: index.php");
    return;
}

$salt = 'XyZzy12*_';
$failure = false;  // If we have no POST data

if ( isset($_SESSION['failure']) ) {
    $failure = htmlentities($_SESSION['failure']);

    unset($_SESSION['failure']);
}

// Check to see if we have some POST data, if we do process it
if ( isset($_POST['email']) && isset($_POST['pass']) ) 
{
    if ( strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1 ) 
    {
        $_SESSION['failure'] = "User name and password are required";
        header("Location: login.php");
        return;
    } 

    $pass = htmlentities($_POST['pass']);
    $email = htmlentities($_POST['email']);

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

    $stmt = $pdo->prepare("
        SELECT * FROM users 
        WHERE email = :email AND password = :password
    ");

    $stmt->execute([
        ':email' => $email, 
        ':password' => hash('md5', $salt.$pass), 
    ]);

    $row = $stmt->fetch(PDO::FETCH_OBJ);

    if ($row !== false) 
    {
        error_log("Login success ".$email);
        $_SESSION['name'] = $row->name;
        $_SESSION['user_id'] = $row->user_id;

        header("Location: index.php");
        return;
    }

    error_log("Login fail ".$pass." $check");
    $_SESSION['failure'] = "Incorrect password";

    header("Location: login.php");
    return;

}

// Fall through into the View
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Ivan Neradovic's Login Page</title>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
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
                        echo(
                            '<p style="color: red;" class="col-sm-10 col-sm-offset-2">'.
                                htmlentities($failure).
                            "</p>\n"
                        );
                    }
                ?>
            <form method="post" class="form-horizontal">
                <div class="form-group">
                    <label class="control-label col-sm-2" for="email">Email:</label>
                    <div class="col-sm-3">
                        <input class="form-control" type="text" name="email" id="email">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="pass">Password:</label>
                    <div class="col-sm-3">
                        <input class="form-control" type="text" name="pass" id="id_1723">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-2 col-sm-offset-2">
                        <input class="btn btn-primary" onclick="return doValidate();" type="submit" value="Log In">
                        <input class="btn" type="submit" name="cancel" value="Cancel">
                    </div>
                </div>
            </form>
            <p>
                For a password hint, view source and find a password hint in the HTML comments.
                <!-- Hint: The password is the four character sound a cat
                makes (all lower case) followed by 123. -->
            </p>
        </div>

        <script>
            function doValidate() {
                console.log('Validating...');
                try {
                    addr = document.getElementById('email').value;
                    pw = document.getElementById('id_1723').value;
                    console.log("Validating addr="+addr+" pw="+pw);
                    if (addr == null || addr == "" || pw == null || pw == "") {
                        alert("Both fields must be filled out");
                        return false;
                    }
                    if ( addr.indexOf('@') == -1 ) {
                        alert("Invalid email address");
                        return false;
                    }
                    return true;
                } catch(e) {
                    return false;
                }
                return false;
            }
        </script>

    </body>
</html>
