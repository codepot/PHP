<?php
ob_start();
session_start();
require_once 'db_config.php';
?>

<html>
    <head>
        <title>Edit Thread</title>

        <link href="decor.css" rel="stylesheet" type="text/css"/>
        <link href="decor2.css" rel="stylesheet" type="text/css"/>
        <link href="decor3.css" rel="stylesheet" type="text/css"/>
        <link href="boxes.css" rel="stylesheet" type="text/css"/>

        <style type="text/css">
            <!--

            .forumName {
                color: #07a;
                font-size: 14px;
                font-weight: bold;
                float: left;
                padding-top: 10px;
                padding-bottom: 10px;
            }
            -->
        </style>

    </head>

    <?php
    $forumName = $_GET["forumName"];
    $threadNum = $_GET["threadNumber"];

    $conn = mysql_connect($host, $username, $password);
    if (!$conn) {
        die('Could not connect: ' . mysql_error());
    }
    mysql_select_db($dbname, $conn);

    $query = "SELECT * FROM thread WHERE ThreadNumber= $threadNum";

    $result = mysql_query($query, $conn);
    $count = mysql_numrows($result);

    if ($count > 0) {
        $title = mysql_result($result, 0, "Title");
    }
    mysql_close($conn);
    ?>

    <body>
        <div style="position: absolute; top: 0; right: 0; width: 400px; text-align:right; font-family:tahoma, verdana, arial, sans-serif; font-weight: bold; font-size: 12px; ">
            login as <span style="color:brown;"><?php echo $_SESSION["loginStatus"]; ?></span>:<span style="color:green;"><?php echo ' ' . $_SESSION['loginUser'] ?> (<?php echo $_SESSION['loginUserFullName'] ?>)</span>
        </div>
        <?php
        if (count($_POST) > 0) {
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


                $newThreadTitle = mysql_real_escape_string($_POST["threadTitle"]);

                $query = "UPDATE thread SET Title='$newThreadTitle' WHERE ThreadNumber=$threadNum";

                $result = mysql_query($query, $conn);

                if ($result) {
                    $message = "You have update thread successfully!";
                    header("location:threads.php?forumName=" . urlencode($forumName));
                } else {
                    $message = "Error. Try Again!";
                    echo "<span class='signupMessage'>" . mysql_errno($conn) . ": " . mysql_error($conn) . "</span>";
                }

                mysql_close($conn);
            }
        }
        ?>
        <form class="upload_form" method='post' enctype='multipart/form-data' action=''>
            <span class="formtitle">UPDATE THREAD</span>
            <a href="process.php?action=signout">SIGN-OUT</a><a href="mainpage.php">HOME</a> <a href="threads.php?forumName=<?php echo rawurlencode($forumName); ?>">BACK</a>
            <p class="signupMessage"><?php if (isset($message)) echo $message; ?></p>
            <BR><span class="forumName">Forum:&nbsp;</span><span class="forumName"><?php echo $forumName; ?></span>
            <input type="text" placeholder="Thread Title" name="threadTitle" value="<?php if (isset($title)) echo $title; ?>"/>
            <button>submit</button>
        </form>


        <footer><div class="upload_footer">Liam Le's Term Project</div></footer>
    </body>
</html>