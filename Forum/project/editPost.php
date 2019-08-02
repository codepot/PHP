<?php
ob_start();
session_start();
require_once 'db_config.php';
if (!isset($_SESSION['loginUser'])) {
    session_unset();
    session_destroy();
    header("location:index.php");
    exit();
}
?>


<html>
    <head>
        <script src="jquery-3.1.1.min.js" type="text/javascript"></script>
        <title>EDIT POST</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">       
        <link href="boxes.css" rel="stylesheet" type="text/css"/>
        <link href="css/forum.css" rel="stylesheet" type="text/css"/>

        <style type="text/css">
            .userFullname {font-family: Arial, Helvetica, sans-serif}
            .style2 {
                color: #006633;
                font-weight: bold;
                vertical-align: top;
            }

            .closeThread{
                font-family: Arial, Helvetica, sans-serif;
                font-size: 12px;
                font-weight: bolder;
                color: #993300;
                padding-left: 10px;
            }

            textArea{
                width: 95%;
                margin-top: 25px;
                font-family: Arial, Helvetica, sans-serif;
                font-size: 14px;
                font-weight: bold;
                color: #206ea1;
            }
            .style3 {
                font-family: Arial, Helvetica, sans-serif;
                font-size: 10px;
                font-weight: bold;
                color: #993300;
                padding-left: 10px;
            }
            .style4 {font-family: Arial, Helvetica, sans-serif; color: #0000FF; }
            .ttitle {
                font-family: Arial, Helvetica, sans-serif;
                font-size: 16px;
                font-weight: bold;
                vertical-align: top;

                text-align: left;
            }
            .dateTime {font-size: 10px}





            .ftitle {
                font-family: Arial, Helvetica, sans-serif;
                color: #0000FF;
                font-weight: bold;
                font-size: 18px;
            }



            .updateButton{
                margin-right: 3%;
            }

            fieldset{
                border: solid 1px lightgray;
                width: 96%;
                border-radius:6px;
                -moz-border-radius:6px;    
                margin: 0 auto;

            }



            legend{
                font-family: "Avant Garde", Avantgarde, "Century Gothic", CenturyGothic, "AppleGothic", sans-serif;
                font-size: 0.8em;
                font-weight: bold;
                text-shadow: -2px -2px 30px rgba(122, 191, 150, 1);

            }



            -->
        </style>


    </head>
    <body>
        <div style="position: absolute; top: 0; right: 0; width: 400px; text-align:right; font-family:tahoma, verdana, arial, sans-serif; font-weight: bold; font-size: 12px; ">
            login as <span style="color:brown;"><?php echo $_SESSION["loginStatus"]; ?></span>:<span style="color:green;"><?php echo ' ' . $_SESSION['loginUser'] ?> (<?php echo $_SESSION['loginUserFullName'] ?>)</span>
        </div>
        <div id="page-wrap">
            <div class="container">

                <?php
                $forumName = $_GET['forumName'];
                $threadTitle = $_GET['title'];
                $role = $_SESSION['loginStatus'];
                $loginUserName = $_SESSION['loginUser'];
                $threadNum = $_GET['threadNumber'];
                $postNum = $_GET['postNumber'];


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
                        $post_text = mysql_real_escape_string($_POST["newContent"]);

                        // $post_text = str_replace('newline',"<br>",$post_text);

                        mysql_select_db($dbname, $conn);
                        $query = "UPDATE post SET PostText='$post_text' WHERE PostNumber=" . $postNum;

                        $result = mysql_query($query, $conn);

                        if ($result) {
                            $message = "You have posted successfully!";
                            header("Location: posts.php?threadNumber=" . $threadNum . '&title=' . urlencode($threadTitle) . '&forumName=' . urlencode($forumName));
                        } else {
                            $message = "Error. Try Again!";
                            echo "<span class='signupMessage'>" . mysql_errno($conn) . ": " . mysql_error($conn) . "</span>";
                        }
                        mysql_close($conn);
                    }
                }
                ?>
                <header id="header">
                    <h1 class="title">UPDATE POST</h1>
                </header>
                <!-- Navigation -->

                <nav id="menu" class="clearfix">
                    <ul>   
                        <li><a class="links"  href="mainpage.php">HOME</a></li>
                        <?php
                        echo '<li><a href="posts.php?threadNumber=' . $threadNum . '&title=' . urlencode($threadTitle) . '&forumName=' . urlencode($forumName) . '">BACK</a></li>';
                        ?>
                        <li><a href="process.php?action=signout">SIGN-OUT</a></li>  
                    </ul>
                </nav>    

                <fieldset id="currentPost">

                    <legend>Current Post Content</legend>

                    <textarea name="currentContent" disabled id="currentContent" rows="4">
                        <?php
                        $conn = mysql_connect($host, $username, $password);
                        if (!$conn) {
                            die('Could not connect: ' . mysql_error());
                        }
                        mysql_select_db($dbname, $conn);

                        $query = "SELECT  PostText from post WHERE PostNumber=" . $postNum;
                        $result = mysql_query($query, $conn);

                        $count = mysql_numrows($result);
                        if ($count > 0) {
                            $postContent = mysql_result($result, 0, "PostText");
                            echo $postContent;
                        }
                        mysql_close($conn);
                        ?>
                            
                    </textarea>


                </fieldset>
                <fieldset id="currentPost">

                    <legend>New Post Content</legend>

                    <div>
                        <form method="post" action="">
                            <textarea name="newContent" id="newContent" rows="4" autofocus>
                                <?php
                                if (isset($postContent)) {
                                    echo $postContent;
                                }
                                ?>
                            </textarea>
                            <button class="updateButton">UPDATE</button>
                        </form>
                    </div>
                </fieldset>

            </div>

        </div>

        <footer>
            <div class="footer">Liam Le's Final Project</div>
        </footer>
    </body>
</html>