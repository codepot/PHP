<?php
ob_start();
session_start();
require_once 'db_config.php';
?>

<html>
    <head>
        <title>Add Thread</title>

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
    
    

    <body>
        <div style="position: absolute; top: 0; right: 0; width: 400px; text-align:right; font-family:tahoma, verdana, arial, sans-serif; font-weight: bold; font-size: 12px; ">
                    login as <span style="color:brown;"><?php echo $_SESSION["loginStatus"]; ?></span>:<span style="color:green;"><?php echo ' '.$_SESSION['loginUser'] ?> (<?php echo $_SESSION['loginUserFullName'] ?>)</span>
                </div>
        <?php
        $forumName = $_GET["forumName"];
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



                $title = mysql_real_escape_string($_POST["threadTitle"]);
                
                

                $starter = $_SESSION['loginUser'];


                $query = "INSERT INTO thread (Title, ForumName, StartUser, Status) VALUES "
                        . "('$title','$forumName','$starter' ,'new');";

                $result = mysql_query($query, $conn);

                if ($result) {
                    $message = "You have added a thread successfully!";
                    header("Location: threads.php?forumName=" . urlencode($forumName));
                } else {
                    $message = "Error. Try Again!";
                    echo "<span class='signupMessage'>" . mysql_errno($conn) . ": " . mysql_error($conn) . "</span>";
                }
                mysql_close($conn);
            }
        }
        ?>
        <form class="upload_form" method='post' enctype='multipart/form-data' action=''>
            <span class="formtitle">ADD A THREAD</span>
            <a href="process.php?action=signout">SIGN OUT</a><a href="mainpage.php">HOME</a> <a href="threads.php?forumName=<?php echo rawurlencode($forumName);?>">BACK</a>
            <p class="signupMessage"><?php if (isset($message)) echo $message; ?></p>
            <BR><span class="forumName">Forum:&nbsp;</span><span class="forumName"><?php echo $forumName;?></span>
            <input type="text" placeholder="Thread Title" name="threadTitle" value="<?php if (isset($_POST['threadTitle'])) echo $_POST['threadTitle']; ?>" autofocus/>

            <button>submit</button>
        </form>


        <footer><div class="upload_footer">Liam Le's Term Project</div></footer>
    </body>
</html>