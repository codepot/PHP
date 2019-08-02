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
            <!--
            .forumTitle {
                font-size: 22px;
                font-weight: bold;
            }
            .forumDescription {
                color: #006600;
                font-size: 16px;
                font-weight: bold;
            }
            .forumModerator {font-size: 14px}

            .forumCell{
                border-bottom: 1px dashed #99CCCC;
                margin-left: 10px;
                vertical-align: middle;
                line-height: 140%;
                min-width: 85%;
            }

            .forumRow{
                height: 75px;

            }

            .status {
                font-family: Arial, Helvetica, sans-serif;
                font-size: 10px;
                font-weight: bold;
                line-height: 1.8em;
                color: #993300;
            }

            .statusCell{
                border-bottom: 1px dashed #99CCCC;
                margin-left: 10px;
                vertical-align: middle;
                line-height: 140%;
                text-align: right;
                width: 15%;
            }

            table tr:hover {
                background: #faebcc;
            }

            .blink_me {
                font-size: 12px !important;
                font-weight: bolder !important;
                color: #905;
                -webkit-animation-name: blinker;
                -webkit-animation-duration: 1s;
                -webkit-animation-timing-function: linear;
                -webkit-animation-iteration-count: infinite;

                -moz-animation-name: blinker;
                -moz-animation-duration: 1s;
                -moz-animation-timing-function: linear;
                -moz-animation-iteration-count: infinite;

                animation-name: blinker;
                animation-duration: 1s;
                animation-timing-function: linear;
                animation-iteration-count: infinite;    
            }

            .status_approved{
                font-size: 10px;
                font-weight: bolder ;
                color: #090;
            }

            .status_disapproved{
                font-size: 10px;
                font-weight: bold ;
                color: #8a6d3b;
            }



            @-moz-keyframes blinker {  
                0% { opacity: 1.0; }
                50% { opacity: 0.0; }
                100% { opacity: 1.0; }
            }

            @-webkit-keyframes blinker {  
                0% { opacity: 1.0; }
                50% { opacity: 0.0; }
                100% { opacity: 1.0; }
            }

            @keyframes blinker {  
                0% { opacity: 1.0; }
                50% { opacity: 0.0; }
                100% { opacity: 1.0; }
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
                <header id="header">
                    <h1 class="title">FORUMS</h1>
                </header>
                <!-- Navigation -->
                <nav id="menu" class="clearfix">
                    <ul> 
                        <li><a class="links"  href="mainpage.php">HOME</a></li>
                        <?php
                        $loginUserName = $_SESSION['loginUser'];
                        $role = $_SESSION['loginStatus'];

                        if ($role == 'moderator' || $role == 'administrator') {
                            echo '<li><a href="addForum.php">ADD FORUM</a></li>';
                        }
                        ?>
                        <li><a href="process.php?action=signout">SIGN OUT</a></li>  

                    </ul>
                </nav> 

                <?php
                if (!isset($view_message)) {

                    $conn = mysql_connect($host, $username, $password);
                    if (!$conn) {
                        die('Could not connect: ' . mysql_error());
                    }

                    mysql_select_db($dbname, $conn);

                    if ($role == 'user') {
                        $query = 'SELECT ForumName, Picture, Description, f.Status, Moderator, UserFullname FROM forum f, puser u where Moderator=Username AND f.Status="approved"';
                    } else if ($role == 'moderator') {
                        $query = "SELECT ForumName, Picture, Description, f.Status, Moderator, UserFullname FROM forum f, puser u where Moderator=Username AND (Moderator='$loginUserName' OR f.status='approved')";
                    } else {
                        $query = 'SELECT ForumName, Picture, Description, f.Status, Moderator, UserFullname FROM forum f, puser u where Moderator=Username';
                    }
                    $result = mysql_query($query, $conn);

                    $count = mysql_numrows($result);
                    echo "<table width='99%'>";
                    for ($i = 0; $i < $count; $i++) {
                        $forumName = mysql_result($result, $i, "ForumName");
                        $picture = mysql_result($result, $i, "Picture");
                        $description = mysql_result($result, $i, "Description");
                        $status = mysql_result($result, $i, "Status");
                        $moderatorID = mysql_result($result, $i, "Moderator");
                        $moderatorName = mysql_result($result, $i, "UserFullname");
                        echo '<tr class="forumRow">
                            <td width="64" height="66"><img src="data:image/png;base64,' . base64_encode($picture) . '" width="64" /></td>
                            <td class="forumCell"><span class="forumTitle"><a href="threads.php?forumName=' . urlencode($forumName) . '">' . $forumName . '</a></span><br />
                                <span class="forumDescription">' . $description . '</span><br />
                                <span class="forumModerator">Moderated by: ' . $moderatorName . ' (' . $moderatorID . ')  </span>';
                        // echo '-'.$moderatorID."<BR>". $loginUserName;

                        if ($role == 'user') {
                            echo '<span class="status_approved"></span>';
                        } else if ($role == 'moderator') {

                            if ($loginUserName == $moderatorID) {
                                if ($status == 'pending') {
                                    echo '<span class="blink_me">' . strtoupper($status) . '</span>';
                                } else if ($status == 'disapproved') {
                                    echo '<span class="status_disapproved">' . strtoupper($status) . '</span>';
                                } else {
                                    echo '<span class="status">' . strtoupper($status) . '</span>';
                                }
                            } else {
                                echo '<span class="status"></span>';
                            }
                        } else if ($role == 'administrator') {
                            if ($status == 'pending') {
                                echo '<span class="blink_me">' . strtoupper($status) . '</span>';
                            } else if ($status == 'disapproved') {
                                echo '<span class="status_disapproved">' . strtoupper($status) . '</span>';
                            } else {
                                echo '<span class="status">' . strtoupper($status) . '</span>';
                            }
                        } else {
                            echo '<span class="status"></span>';
                        }


                        echo '</td>';

                        if ($role == 'administrator') {
                            echo '<td class="statusCell">';
                            echo '<a href="editForum.php?forumName=' . rawurlencode($forumName) . '" class="status">Edit This Forum</a>';
                            if ($status == 'pending') {

                                echo '<BR><a href="process.php?action=approve&forumName=' . rawurlencode($forumName) . '" class="status">Approve This Forum</a>';
                                echo '<BR><a href="process.php?action=disapprove&forumName=' . rawurlencode($forumName) . '" class="status">Disapprove This Forum</a>';
                            } else if ($status == 'approved') {
                                echo '<BR><a href="process.php?action=disapprove&forumName=' . rawurlencode($forumName) . '" class="status">Disapprove This Forum</a>';
                            } else {
                                echo '<BR><a href="process.php?action=approve&forumName=' . rawurlencode($forumName) . '" class="status">Approve This Forum</a>';
                            }
                            echo '<BR><a href="appointModerator.php?forumName=' . rawurlencode($forumName) . '" class="status">Appoint New Moderator</a>';
                            echo '<BR><a href="ban.php?forumName=' . rawurlencode($forumName) . '&moderator='.$moderatorID.'" class="status">Ban an User</a></td>';
                        } else {
                            if ($role == 'moderator' && $loginUserName == $moderatorID) {
                                echo '<td class="statusCell"><a href="editForum.php?forumName=' . rawurlencode($forumName) . '" class="status">Edit This Forum</a>';
                                echo '<BR><a href="ban.php?forumName=' . rawurlencode($forumName) . '&moderator='.$moderatorID.'" class="status">Ban an User</a></td>';
                            }
                        }
                        echo '</tr>';
                    }
                    echo "</table>";
                    mysql_close($conn);
                }
                ?>




                </table>



            </div>



        </div>
        <footer>
            <div class="footer">Liam Le's Final Project</div>
        </footer>
    </body>
</html>