<?php
ob_start();
session_start();
require_once 'db_config.php';

function phpAlert($msg) {
    echo '<script type="text/javascript">alert("' . $msg . '")</script>';
}

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
		('" . mysql_real_escape_string($_POST["fullName"]) . "', '" . mysql_real_escape_string($_POST["userName"]) . "', '" . mysql_real_escape_string($_POST["password"]) . "', '" . mysql_real_escape_string($_POST["userRole"]) . "')";

            $result = mysql_query($query, $conn);



            if ($result) {
                $message = "You have added an user successfully!";
                unset($_POST);
                phpAlert("You have added an user successfully!");
               // header("Location: addUser.php");
            } else {
                $message = "Problem in adding a new user. Try Again!";
            }
            mysql_close($conn);
        }
    }
}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Register Page - Liam Le's Final Project</title>
        <link href="decor.css" rel="stylesheet" type="text/css"/>


        <style type="text/css">

            .demonstration{

                margin-bottom: 25px;
            }


            .imageBased input[type=radio]:not(old){
                float: left;                           
                margin-left: 20px;
                opacity : 0;
            }


            .imageBased input[type=radio   ]:not(old) + label{
                display      : inline-block;
                margin-left  : 0px;
                padding-left : 28px;
                background   : url('images/checks.png') no-repeat 0 0;
                line-height  : 20px;


            }

            .imageBased input[type=checkbox]:not(old):checked + label{
                background-position : 0 -24px;
            }

            .imageBased input[type=radio]:not(old):checked + label{
                background-position : 0 -48px;
            }


            .pureCSS input[type=radio   ]:not(old){
                width     : 160px;

                font-size : 1em;
                opacity   : 0;
            }


            .pureCSS input[type=radio   ]:not(old) + label{
                display      : inline-block;
                margin-left  : -2em;
                line-height  : 0.8em;
            }


            .pureCSS input[type=radio   ]:not(old) + label > span{
                display          : inline-block;
                width            : 0.875em;

                margin           : 0.25em 0.5em 0.25em 0.25em;
                border           : 0.0625em solid rgb(192,192,192);
                border-radius    : 0.25em;
                background       : rgb(224,224,224);
                background-image :    -moz-linear-gradient(rgb(240,240,240),rgb(224,224,224));
                background-image :     -ms-linear-gradient(rgb(240,240,240),rgb(224,224,224));
                background-image :      -o-linear-gradient(rgb(240,240,240),rgb(224,224,224));
                background-image : -webkit-linear-gradient(rgb(240,240,240),rgb(224,224,224));
                background-image :         linear-gradient(rgb(240,240,240),rgb(224,224,224));
                vertical-align   : bottom;
            }


            .pureCSS input[type=radio   ]:not(old):checked + label > span{
                background-image :    -moz-linear-gradient(rgb(224,224,224),rgb(240,240,240));
                background-image :     -ms-linear-gradient(rgb(224,224,224),rgb(240,240,240));
                background-image :      -o-linear-gradient(rgb(224,224,224),rgb(240,240,240));
                background-image : -webkit-linear-gradient(rgb(224,224,224),rgb(240,240,240));
                background-image :         linear-gradient(rgb(224,224,224),rgb(240,240,240));
            }



            .pureCSS input[type=radio]:not(old):checked +  label > span > span{
                display          : block;
                width            : 0.5em;


                border           : 0.0625em solid rgb(115,153,77);
                border-radius    : 0.125em;
                background       : rgb(153,204,102);
                background-image :    -moz-linear-gradient(rgb(179,217,140),rgb(153,204,102));
                background-image :     -ms-linear-gradient(rgb(179,217,140),rgb(153,204,102));
                background-image :      -o-linear-gradient(rgb(179,217,140),rgb(153,204,102));
                background-image : -webkit-linear-gradient(rgb(179,217,140),rgb(153,204,102));
                background-image :         linear-gradient(rgb(179,217,140),rgb(153,204,102));
            }

            a {
                text-decoration: none;
                padding-left: 15px;
                padding-top: 15px;
                padding-bottom: 15px;
                float: right;
                font-size: 12px;
                font-weight: bold;
                color: #090;
                opacity: 0.6;
                position: relative;
                -webkit-transition: opacity 0.2s ease-in-out 0s;
                -moz-transition: opacity 0.2s ease-in-out 0s;
                transition: opacity 0.2s ease-in-out 0s;
            }

            a:hover {
                opacity: 1;
            }

            a:before {
                content: "";
                position: absolute;
                bottom: -2px;
                width: 100%;
                height: 2px;

                background-color: #aca;
                -webkit-transform: scaleX(0);
                -moz-transform: scaleX(0);
                transform: scaleX(0);
                -webkit-transition: all 0.2s ease-in-out 0s;
                -moz-transition: all 0.2s ease-in-out 0s;
                transition: all 0.2s ease-in-out 0s;
            }

            a:hover:before {
                -webkit-transform: scaleX(0.4);
                -moz-transform: scaleX(0.4);
                transform: scaleX(0.4);
            }



        </style>

    </head>
    <body>
        <div style="position: absolute; top: 0; right: 0; width: 400px; text-align:right; font-family:tahoma, verdana, arial, sans-serif; font-weight: bold; font-size: 12px; ">
                    login as <span style="color:brown;"><?php echo $_SESSION["loginStatus"]; ?></span>:<span style="color:green;"><?php echo ' '.$_SESSION['loginUser'] ?> (<?php echo $_SESSION['loginUserFullName'] ?>)</span>
                </div>
        <div id="page-wrap">
            <div class="login-page">
                <div class="form">
                    <form class="register-form" method="post" action="">
                        <p class="formtitle">ADD AN USER</p>
                        <div>

                            <a href="mainpage.php">HOME</a>
                           
                            
                        </div>
                        
                        <input type="text" placeholder="full name" name="fullName" value="<?php if (isset($_POST['fullName'])) echo $_POST['fullName']; ?>" autofocus/>
                        <input type="text" placeholder="username" name="userName" value="<?php if (isset($_POST['userName'])) echo $_POST['userName']; ?>"/>
                        <div class="demonstration imageBased">

                            <div style="text-align: left; margin-top:-15px;">
                                <input id="radioImageBased1" type="radio" name="userRole" value="user" checked="checked"><label width="120px" for="radioImageBased1">Regular User</label>
                            </div>
                            <div style="text-align: left">
                                <input id="radioImageBased2" type="radio" name="userRole" value="moderator" <?php if (isset($_GET['role'])){echo 'checked';} ?>><label width="120px" for="radioImageBased2">Moderator</label>
                            </div>
                            <div style="text-align: left">
                                <input id="radioImageBased3" type="radio" name="userRole" value="administrator"><label width="120px" for="radioImageBased3">Administrator</label>
                            </div>
                        </div>

                        <input type="password" placeholder="password" name="password" value="<?php if (isset($_POST['password'])) echo $_POST['password']; ?>"/>
                        <input type="password" placeholder="confirmed password" name="confirmedPassword" value="<?php if (isset($_POST['confirmedPassword'])) echo $_POST['confirmedPassword']; ?>"/>
                        <button>SUBMIT</button>
<p class="signupMessage"><?php if (isset($message)) echo $message; ?></p>
                    </form>
                </div> 

            </div> 

        </div>


        <footer>
            <div class="footer">Liam Le's Final Project</div>
        </footer> 
    </body>
</html>


