<?php
ob_start();
session_start();
require_once 'db_config.php';

if(isset($_GET['banned'])){
     echo "<script>alert('User Banned');</script>";
}

if (count($_POST) > 0) {
    /* Form Required Field Validation */
    foreach ($_POST as $key => $value) {
        if (empty($_POST[$key])) {
            $login_message = ucwords($key) . " field is required";
            break;
        }
    }

    if (!isset($login_message)) {
        $conn = mysql_connect($host, $username, $password);
        if (!$conn) {
            die('Could not connect: ' . mysql_error());
        }

        mysql_select_db($dbname, $conn);

        $query = "SELECT * FROM puser WHERE Username = BINARY '" . $_POST["userName"] . "' AND Password= BINARY  '" . $_POST["password"] . "'";

        $result = mysql_query($query, $conn);
        $count = mysql_numrows($result);
        if ($count > 0) {
            $banned = mysql_result($result, 0, "Banned");

            if ($banned == 0) {
                $_SESSION["loginUser"] = $_POST["userName"];
                $_SESSION["loginUserFullName"] = mysql_result($result, 0, "UserFullName");
                $_SESSION["loginStatus"] = mysql_result($result, 0, "Status");

                header("location:mainpage.php");
            } else {               
                header("location:index.php?banned=yes");
            }
            mysql_close($conn);
        } else {
            $login_message = 'Log in failed, please try again';

            session_unset();
            session_destroy();
        }
    }
}
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Login Page - Liam Le's Assignment #3</title>
        <link href="decor.css" rel="stylesheet" type="text/css"/>

    </head>

    <script>
        function setFocusToTextBox(id) {
            document.getElementById(id).focus();
        }
    </script>
    <body>
        <div id="page-wrap">
            <div class="login-page">
                <div class="form">
                    <form class="login-form" method="post" action="">
                        <p class="formtitle">SIGN IN</p>
                        <?php
                        if (isset($_GET['msg']) && !empty($_GET['msg'])) {
                            echo '<p class="signinMessage">You successfully registered, please log in.</p>';
                        }
                        ?>
                        <p class="signinFailMessage"><?php if (isset($login_message)) echo $login_message; ?></p>

                        <input type="text" placeholder="username" autofocus name="userName" value="<?php if (isset($_GET['user']) && !empty($_GET['user'])) echo $_GET['user']; ?>"/>
                        <input type="password" placeholder="password" name="password" autocomplete="off"/>
                        <button>login</button>
                        <p class="message">Not registered? <a href="register.php">Create an account</a></p>
                    </form>
                </div> 
            </div> 

        </div>


        <footer>
            <div class="footer">Liam Le's Final Project</div>
        </footer>
    </body>
</html>
