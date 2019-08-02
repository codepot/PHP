<?php
ob_start();
session_start();
require_once 'db_config.php';
if (count($_POST) > 0) {
    /* Form Required Field Validation */
    foreach ($_POST as $key => $value) {
        if (empty($_POST[$key])) {
            $message = ucwords($key) . " field is required";
            break;
        }
    }
    /* Password Matching Validation */
    if ($_POST['password'] != $_POST['confirmedPassword']) {
        $message = 'Passwords should be same<br>';
    }

    if (!isset($message)) {
        $conn = mysql_connect($host, $username, $password);
        if (!$conn) {
            die('Could not connect: ' . mysql_error());
        }
        $username = $_POST["userName"];
        mysql_select_db($dbname, $conn);
        $query = "select * from puser WHERE Username= BINARY '$username'";
        $result = mysql_query($query, $conn);
        $count = mysql_numrows($result);
        if ($count > 0) {
            // username is existing
            $message = "Username is existing!";
            mysql_close($conn);
        } else {
            $query = "INSERT INTO puser (UserFullName, Username, Password, Status) VALUES
		('" . mysql_real_escape_string($_POST["fullName"]) . "', '" . mysql_real_escape_string($_POST["userName"]) . "', '" . mysql_real_escape_string($_POST["password"]) . "', 'user')";

            $result = mysql_query($query, $conn);



            if ($result) {
                $message = "You have registered successfully!";
                unset($_POST);
                header("Location: index.php?msg=registered&user=" . $username);
            } else {
                $message = "Problem in registration. Try Again!";
            }
            mysql_close($conn);
        }
    }
}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Register Page - Liam Le's Assignment #3</title>
        <link href="decor.css" rel="stylesheet" type="text/css"/>

    </head>
    <body>
        <div id="page-wrap">
            <div class="login-page">
                <div class="form">
                    <form class="register-form" method="post" action="">
                        <p class="formtitle">SIGN UP</p>
                        <p class="signupMessage"><?php if (isset($message)) echo $message; ?></p>
                        <input type="text" placeholder="full name" name="fullName" value="<?php if (isset($_POST['fullName'])) echo $_POST['fullName']; ?>"/>
                        <input type="text" placeholder="username" name="userName" value="<?php if (isset($_POST['userName'])) echo $_POST['userName']; ?>"/>
                        <input type="password" placeholder="password" name="password" value="<?php if (isset($_POST['password'])) echo $_POST['password']; ?>"/>
                        <input type="password" placeholder="confirmed password" name="confirmedPassword" value="<?php if (isset($_POST['confirmedPassword'])) echo $_POST['confirmedPassword']; ?>"/>
                        <button>create</button>
                        <p class="message">Already registered? <a href="index.php">Sign In</a></p>
                    </form>
                </div> 

            </div> 

        </div>


        <footer>
            <div class="footer">Liam Le's Final Project</div>
        </footer> 
    </body>
</html>


