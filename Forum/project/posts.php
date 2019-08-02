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
        <title>POSTS</title>
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

            td{
                border-bottom: 1px dashed #99CCCC;
                line-height: 150%;

            }

            .rightCol{
                border-bottom: 1px dashed #99CCCC;
                margin-left: 10px;
                vertical-align: middle;
                line-height: 140%;
                text-align: right;
                width: 15%;
            }

            .ftitle {
                font-family: Arial, Helvetica, sans-serif;
                color: #0000FF;
                font-weight: bold;
                font-size: 18px;
            }

            table{
                float: center;
                padding-left: 5px;
                width: 98%;
            }

            table tr:hover {
                background: #e8ff8e;
                border: 1px solid #e8ff8e;
            }

            .postButton{
                margin-right: 3%;
            }



            -->
        </style>

        <script>
            function focusElement() {
                $('#editing').focus();
            }
        </script>
    </head>
    <body>
       
        <div id="page-wrap">
            <div class="container">
                 <div style="position: absolute; top: 0; right: 0; width: 400px; text-align:right; font-family:tahoma, verdana, arial, sans-serif; font-weight: bold; font-size: 12px; ">
            login as <span style="color:brown;"><?php echo $_SESSION["loginStatus"]; ?></span>:<span style="color:green;"><?php echo ' ' . $_SESSION['loginUser'] ?> (<?php echo $_SESSION['loginUserFullName'] ?>)</span>
        </div>
                <!-- header -->
                <?php
                $forumName = $_GET['forumName'];
                $threadTitle = $_GET['title'];
                $role = $_SESSION['loginStatus'];
                $loginUserName = $_SESSION['loginUser'];
                $threadNum = $_GET['threadNumber'];


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
                        $post_text = mysql_real_escape_string($_POST["editing"]);

                        // $post_text = str_replace('newline',"<br>",$post_text);

                        mysql_select_db($dbname, $conn);

                        $query = "INSERT INTO post (ThreadNumber, PostText, User, Status) VALUES "
                                . "($threadNum,'$post_text','$loginUserName' ,'new');";

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
                    <h1 class="title">POSTS (Thread: <?php echo $threadTitle; ?>)</h1>
                </header>
                <!-- Navigation -->

                <nav id="menu" class="clearfix">
                    <ul> 
                        <li><a class="links"  href="mainpage.php">HOME</a></li>




                        <?php
                        $conn = mysql_connect($host, $username, $password);
                        if (!$conn) {
                            die('Could not connect: ' . mysql_error());
                        }
                        mysql_select_db($dbname, $conn);

                        $query = "SELECT Status FROM thread WHERE ThreadNumber=" . $threadNum;
                        $result = mysql_query($query, $conn);
                        $count = mysql_numrows($result);
                        if ($count > 0) {
                            $threadStatus = mysql_result($result, 0, "Status");
                        }
                        if ($threadStatus == 'new') {
                            echo '<li><a onclick="focusElement();">NEW POST</a></li>  ';
                        }
                        ?>

                        <li><a href="threads.php?forumName=<?php echo urlencode($forumName); ?>">BACK</a></li>  
                        <li><a href="forum.php">FORUMS</a></li>                          
                        <li><a href="process.php?action=signout">SIGN OUT</a></li>  
                    </ul>
                </nav> 

                <?php
                $query = "SELECT p.PostNumber, p.ThreadNumber, p.DateTime, p.PostText, p.User, u.UserFullName, p.Status, t.Status as pStatus, Moderator FROM forum f, thread t, post p, puser u WHERE f.ForumName=t.ForumName AND t.ThreadNumber=p.ThreadNumber AND Username=User AND p.ThreadNumber=" . $threadNum;

              
                $result = mysql_query($query, $conn);

                $count = mysql_numrows($result);

                echo '<table width="95%">';



                for ($i = 0; $i < $count; $i++) {
                    $postNumber = mysql_result($result, $i, "PostNumber");
                    $postTime = mysql_result($result, $i, "DateTime");
                    $postText = mysql_result($result, $i, "PostText");
                    $postUser = mysql_result($result, $i, "User");
                    $postUserFullName = mysql_result($result, $i, "UserFullName");
                    $postStatus = mysql_result($result, $i, "Status");

                    $moderator = mysql_result($result, $i, "Moderator");

                    $postText = str_replace("\r\n\r\n", "\r\n", $postText);

                    $lines = array_filter(explode("\n", trim($postText)));
                    $lines = implode("\n", $lines);

                    echo '<tr>
    <td height="34" width="15%"><span class="userFullname"><span class="style2">' . $postUserFullName . '</span> (' . $postUser . '): </span><BR><span class="dateTime">[' . $postTime . ']</span></td><td width="60%" style="text-align:left;vertical-align:top;padding:0">
    <span class="ttitle">' . nl2br($lines) . '</td>';
                    if ($postStatus == 'removed') {
                        echo '<span class="closeThread"> [' . strtoupper($postStatus) . ']</span>';
                    }
                    echo '</span></td><td class="rightCol"><div align="right">';

                    if ($postStatus == 'new' && $threadStatus == 'new') {
                        if ($postUser == $loginUserName || $role == 'administrator') {
                            echo '<a href="editPost.php?postNumber=' . $postNumber . '&threadNumber=' . $threadNum . '&title=' . urlencode($threadTitle) . '&forumName=' . urlencode($forumName) . '" class="style3">EDIT</a>';
                            echo '<a href="process.php?action=removePost&postNumber=' . $postNumber . '&threadNumber=' . $threadNum . '&title=' . urlencode($threadTitle) . '&forumName=' . urlencode($forumName) . '" class="style3">REMOVE</a>';
                        }
                    }
                    echo '</div></td></tr>';
                }
                echo '</table> <div>';
                mysql_close($conn);

                if ($threadStatus == 'new') {
                    echo '<form method="post" action="">
                        <textarea name="editing" id="editing" rows="4">
                            
                        </textarea>
                        <button class="postButton">POST</button>
                    </form>';
                } else {
                    echo '<form>
                        <textarea disabled name="editing" id="editing" rows="4">
                            UNABLE TO POST BECAUSE THREAD WAS CLOSED OR REMOVED
                        </textarea>                       
                    </form>';
                }
                ?>


            </div>

        </div>

    </div>
    <footer>
        <div class="footer">Liam Le's Final Project</div>
    </footer>
</body>
</html>