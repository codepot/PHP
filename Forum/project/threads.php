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
        <title><?php echo strtoupper($_SESSION['loginUserFullName']) ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">       
        <link href="boxes.css" rel="stylesheet" type="text/css"/>
        <link href="css/forum.css" rel="stylesheet" type="text/css"/>

        <style type="text/css">
            .userFullname {font-family: Arial, Helvetica, sans-serif}
            .style2 {
                color: #006633;
                font-weight: bold;
            }

            .closeThread{
                font-family: Arial, Helvetica, sans-serif;
                font-size: 12px;
                font-weight: bolder;
                color: #993300;
                padding-left: 10px;
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
            }


            -->
        </style>
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
                ?>
                <header id="header">
                    <h1 class="title">THREADS (Forum: <?php echo $forumName; ?>)</h1>
                </header>
                <!-- Navigation -->
                <nav id="menu" class="clearfix">
                    <ul>     
                        <li><a href="forum.php">FORUMS</a></li>  
                        <?php
                        $role = $_SESSION['loginStatus'];

                        echo '<li><a href="addThread.php?forumName=' . urlencode($forumName) . '">NEW THREAD</a></li>';
                        ?>
                        <li><a href="process.php?action=signout">SIGN OUT</a></li>  
                    </ul>
                </nav> 

                <?php
                $loginUserName = $_SESSION['loginUser'];
                if (!isset($view_message)) {

                    $conn = mysql_connect($host, $username, $password);
                    if (!$conn) {
                        die('Could not connect: ' . mysql_error());
                    }

                    mysql_select_db($dbname, $conn);
                    if ($role == 'user') {
                        $query = "SELECT t.ThreadNumber, (SELECT Coalesce( ROUND(AVG(Ranking),1),0) FROM rank r WHERE r.ThreadNumber = t.ThreadNumber ) AS Ranking, Title, ForumName, DateTime, StartUser, UserFullname, t.Status FROM thread t, puser u"
                                . " WHERE StartUser=Username AND ForumName= BINARY '$forumName' AND (t.Status='new' OR t.Status='closed')";
                    } else
                    if ($role == 'moderator') {
                        $query = "SELECT t.ThreadNumber, (SELECT Coalesce( ROUND(AVG(Ranking),1),0) FROM rank r WHERE r.ThreadNumber = t.ThreadNumber ) AS Ranking, Title, ForumName, DateTime, StartUser, UserFullname, t.Status FROM thread t, puser u"
                                . " WHERE StartUser=Username AND ForumName= BINARY '$forumName' AND (t.Status='new' OR t.Status='closed' OR StartUser='" . $loginUserName . "')";
                    } else if ($role == 'administrator') {
                        $query = "SELECT t.ThreadNumber, (SELECT Coalesce( ROUND(AVG(Ranking),1),0) FROM rank r WHERE r.ThreadNumber = t.ThreadNumber ) AS Ranking, Title, ForumName, DateTime, StartUser, UserFullname, t.Status FROM thread t, puser u WHERE StartUser=Username AND ForumName= BINARY '$forumName'";
                    } else {
                        
                    }

                    $result = mysql_query($query, $conn);

                    $count = mysql_numrows($result);


                    echo '<table width="95%">';

                    for ($i = 0; $i < $count; $i++) {
                        // ThreadNumber	Title	ForumName	DateTime	StartUser	Status
                        $threadNumber = mysql_result($result, $i, "ThreadNumber");
                        $title = mysql_result($result, $i, "Title");
                        $dateTime = mysql_result($result, $i, "DateTime");
                        $startUser = mysql_result($result, $i, "StartUser");
                        $startUserFullName = mysql_result($result, $i, "UserFullname");
                        $status = mysql_result($result, $i, "Status");
                        $rank = mysql_result($result, $i, "Ranking");

                        echo '<tr>
    <td height="34"><span class="userFullname"><span class="style2">' . $startUserFullName . '</span> (' . $startUser . '): </span>
    <a href="posts.php?threadNumber=' . $threadNumber . '&title=' . urlencode($title) . '&forumName=' . urlencode($forumName) . '"><span class="ttitle">' . $title . ' '
                        . '</a><BR><span class="dateTime">[' . $dateTime . ']</span>';
                        if ($status == 'new') {
                            echo '<a style="font-size: 10px;color: orange;font-family: tahoma, verdana, arial, sans-serif;" href="rank.php?threadNumber=' . $threadNumber .
                            '&title=' . urlencode($title) . '&forumName=' . urlencode($forumName) . '"> RANK THIS THREAD</a>';
                                   
                        }
                        if ($status == 'closed' || $status == 'removed') {
                            echo '<span class="closeThread"> [' . strtoupper($status) . ']</span>';
                            
                        }
                         if($rank>0){
                                    echo '<span style="color:green; font-family:tahoma, verdana, arial, sans-serif; font-weight: bold; font-size: 10px;"> - AVERAGE RATING: '.$rank.' &#11088;</span>';
                         }
                         else{
                              echo '<span style="color:green; font-family:tahoma, verdana, arial, sans-serif; font-weight: bold; font-size: 10px;"> - has not been ranked</span>';
                         }
                        echo '</td><td class="rightCol"><div align="right">';

                        if ($status == 'new') {
                            if ($startUser == $loginUserName || $role == 'administrator') {
                                echo '<a href="editThread.php?threadNumber=' . $threadNumber . '&forumName=' . urlencode($forumName) . '"class="style3">EDIT</a>';
                                echo '<a href="process.php?action=closeThread&threadNumber=' . $threadNumber . '&forumName=' . urlencode($forumName) . '"class="style3">CLOSE</a>';
                                echo '<a href="process.php?action=removeThread&threadNumber=' . $threadNumber . '&forumName=' . urlencode($forumName) . '"class="style3">REMOVE</a>';
                            }
                            echo '<a href="posts.php?threadNumber=' . $threadNumber . '&title=' . urlencode($title) . '&forumName=' . urlencode($forumName) . '" class="style3">POST</a>';
                        } else {
                            if ($status == 'closed') {
                                if ($startUser == $loginUserName || $role == 'administrator') {
                                    echo '<a href="process.php?action=removeThread&threadNumber=' . $threadNumber . '&forumName=' . urlencode($forumName) . '"class="style3">REMOVE</a>';
                                }
                            }
                        }

                        echo '</div></td></tr>';
                    }
                    echo '</table>';
                }

                mysql_close($conn);
                ?>
            </div>


        </div>
        <footer>
            <div class="footer">Liam Le's Final Project</div>
        </footer>
    </body>
</html>