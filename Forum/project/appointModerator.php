<?php
ob_start();
session_start();
require_once 'db_config.php';
?>

<html>
    <head>
        <title>APPOINT MODERATOR</title>

        <link href="decor.css" rel="stylesheet" type="text/css"/>
        <link href="decor2.css" rel="stylesheet" type="text/css"/>
        <link href="css/appoint.css" rel="stylesheet" type="text/css"/>
        <link href="boxes.css" rel="stylesheet" type="text/css"/>
        <style type="text/css">
            <!--
            select {
                font-size: 16px;
                float: left;
                width: 100%;
                margin-bottom: 20px;
            }

            option{
                border-bottom: 1px dashed #99CCCC;
                margin-left: 10px;
                vertical-align: middle;
                line-height: 2.5em;

                width: 100%;
            }

            .moderator {
                text-decoration: none;
                padding-left: 0px;
                padding-top: 0px;
                padding-bottom: 0px;
                float: left;
                font-size: 12px;
                font-weight: bold;
                color: #000000;


            }

            .forumName{
                font-family: "Roboto", sans-serif;
                outline: 0;
                background: #f2f2f2;
                width: 100%;
                border: 0;
                margin: 0 0 15px;
                padding: 15px;
                box-sizing: border-box;
                font-size: 18px;                
                color: #206ea1;
                font-weight: bolder;
            }

            .userManager{
                text-decoration: none;
                padding-left: 10px;

                font-size: 10px;
                font-weight: bold;
                color: #090;
                opacity: 0.6;

                -webkit-transition: opacity 0.2s ease-in-out 0s;
                -moz-transition: opacity 0.2s ease-in-out 0s;
                transition: opacity 0.2s ease-in-out 0s;
            }


            -->

        </style>

    </head>

    <body>
        <div style="position: absolute; top: 0; right: 0; width: 400px; text-align:right; font-family:tahoma, verdana, arial, sans-serif; font-weight: bold; font-size: 12px; ">
                    login as <span style="color:brown;"><?php echo $_SESSION["loginStatus"]; ?></span>:<span style="color:green;"><?php echo ' '.$_SESSION['loginUser'] ?> (<?php echo $_SESSION['loginUserFullName'] ?>)</span>
                </div>
        <?php
        if (count($_POST) > 0) {
            $forumName = rawurldecode( $_GET['forumName']);
            /* Form Required Field Validation */
            foreach ($_POST as $key => $value) {
                if (empty($_POST[$key])) {
                    $message = ucwords($key) . " is required";
                    break;
                }
            }

            if (!isset($message)) {

                $conn = mysql_connect($host, $username, $password);

                if (!$conn) {
                    die('Could not connect: ' . mysql_error());
                }

                mysql_select_db($dbname, $conn);

                $moderator = mysql_real_escape_string($_POST["moderator"]);

                $query = "INSERT INTO forum (ForumName, Picture, Description, Status, Moderator) VALUES "
                        . "('$forumName','{$data}','$description' ,'pending', '{$loginUserName}');";

                $query = "UPDATE forum SET Moderator='" . $moderator . "' WHERE ForumName='" . $forumName . "'";

                $result = mysql_query($query, $conn);

                if ($result) {
                    $message = "You have assigned moderator successfully!";
                    header("location:forum.php");
                    exit();
                } else {
                    $message = "Error. Try Again!";
                    echo "<span class='signupMessage'>" . mysql_errno($conn) . ": " . mysql_error($conn) . "</span>";
                }
                mysql_close($conn);
            }
        }
        ?>
        <form class="upload_form" method='post' enctype='multipart/form-data' action=''>
            <span class="formtitle">APPOINT A FORUM MODERATOR</span>
            <a href="process.php?action=signout">SIGN-OUT</a><a href="addUser.php?role=moderator">NEW-MODERATOR</a><a href="forum.php">BACK</a><a href="mainpage.php">HOME</a>
            <p class="signupMessage"><?php if (isset($message)) echo $message; ?></p>

            <input class="forumName" type="text" placeholder="Forum Name" name="forumName" value="<?php if (isset($_GET['forumName'])) echo rawurldecode( $_GET['forumName']); ?>"/>

            <span class="moderator">Moderator:</span>
            <?php
            $conn = mysql_connect($host, $username, $password);

            if (!$conn) {
                die('Could not connect: ' . mysql_error());
            }

            mysql_select_db($dbname, $conn);




            $query = "SELECT * FROM puser WHERE Status='moderator'";

            $result = mysql_query($query, $conn);
            $count = mysql_numrows($result);
            if ($count == 0) {
                echo '<a id="userManager" href="manageUsers.php">ADD A MODERATOR</a>';
            }
            echo '<BR><select name="moderator" size="6">';
            for ($i = 0; $i < $count; $i++) {
                $username = mysql_result($result, $i, "Username");
                $userFullName = mysql_result($result, $i, "UserFullName");
                echo '<option value="' . $username . '">' . $userFullName . ' (' . $username . ')</option>';
            }

            echo '</select>';
            mysql_close($conn);
            ?>
            <BR>
            <button>submit</button>
        </form>


        <footer><div class="upload_footer">Liam Le's Term Project</div></footer>
    </body>
</html>