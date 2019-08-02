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



if (isset($_GET['box'])) {
    $boxNumber = $_GET['box'];
    unset($_GET);
    echo "<script>alert('You have started chat room # $boxNumber successfully');</script>";
}

//joinedRoomNum

if (isset($_GET['joinedRoomNum'])) {
    $joinedRoomNum = $_GET['joinedRoomNum'];
    unset($_GET);
    echo "<script>alert('You have joined chat room # $joinedRoomNum');</script>";
}

?>
<html>
    <head>
        <title>CHAT ROOMS</title>
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

            .startUserFullName{
                font-weight: bold;
                color: #993300;
            }

            .forumCell{
                border-bottom: 1px dashed #99CCCC;                
                margin-left: 10px;
                vertical-align: middle;
                line-height: 140%;
                min-width: 85%;
            }

            .forumRow{
                height: 72px;

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
                text-align: center;
                width: 15%;

            }

            table{
                border-collapse: collapse;
            }

            table tr:hover {
                background: #d9edf7;

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

            .cool-link {
                display: inline-block;
                color: #000;
                text-decoration: none;
            }

            .cool-link::after {
                content: '';
                display: block;
                width: 0;
                height: 2px;
                background: #000;
                transition: width .3s;
            }

            .cool-link:hover::after {
                width: 100%;
                transition: width .3s;
            }
            
            
            .box{
  color: #993300;
  padding: 10px;
  font-weight: bold;
 
  display: inline-block;
}
 
.box:hover{
  background: #d9edf7;
  color: #26425E;
}
 
.demo-1 {
  position: relative;
}
 
.demo-1:before {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  top: 0;
  right: 0;
 
  -webkit-transition-duration: 0.3s;
  -moz-transition-duration: 0.3s;
  -ms-transition-duration: 0.3s;
  -o-transition-duration: 0.3s;
  transition-duration: 0.2s;
 
  -webkit-transition-property: top, left, right, bottom;
  -moz-transition-property: top, left, right, bottom;
  -ms-transition-property: top, left, right, bottom;
  -o-transition-property: top, left, right, bottom;
  transition-property: top, left, right, bottom;
}
 
.demo-1:hover:before, .demo-1:focus:before{
  -webkit-transition-delay: .1s;
  -moz-transition-delay: .1s;
  -ms-transition-delay: .1s;
  -o-transition-delay: .1s;
  transition-delay: .1s; 
 
  border: #FFED85 solid 3px;
  bottom: -7px;
  left: -7px;
  top: -7px;
  right: -7px;
}
            -->
        </style>
    </head>
    <body>

        <div id="page-wrap">
            <div class="container">
                <div style="position: absolute; top: 0; right: 0; width: 400px; text-align:right; font-family:tahoma, verdana, arial, sans-serif; font-weight: bold; font-size: 12px; ">
                    login as <span style="color:brown;"><?php echo $_SESSION["loginStatus"]; ?></span>:<span style="color:green;"><?php echo ' '.$_SESSION['loginUser'] ?> (<?php echo $_SESSION['loginUserFullName'] ?>)</span>
                </div>
                <!-- header -->
                <header id="header">
                    <h1 class="title">CHAT ROOMS</h1>
                </header>
                <!-- Navigation -->
                <nav id="menu" class="clearfix">
                    <ul> 
                        <li><a class="links"  href="mainpage.php">HOME</a></li>
                        <?php
                        $loginUserName = $_SESSION['loginUser'];
                        $role = $_SESSION['loginStatus'];
                        ?>
                        <li><a href="process.php?action=newChatroom">START-A-NEW-CHAT-ROOM</a></li>
                        <li><a href="process.php?action=signout">SIGN-OUT</a></li>  

                    </ul>
                </nav> 

                <?php
                if (!isset($view_message)) {

                    $conn = mysql_connect($host, $username, $password);
                    if (!$conn) {
                        die('Could not connect: ' . mysql_error());
                    }

                    mysql_select_db($dbname, $conn);
                    //$query = 'SELECT RoomNumber, StartUser, UserFullName FROM chatroom, puser WHERE StartUser=Username';
                    // $query = "SELECT RoomNumber, StartUser, UserFullName, (SELECT COUNT(c.Username) FROM chatuser c WHERE c.RoomNumber=r.RoomNumber) AS Count FROM chatroom r, puser p WHERE r.StartUser=p.Username";

                    $query = "SELECT  (SELECT IF(COUNT(c2.Username)>0 , 'yes', 'no') FROM chatuser c2 WHERE c2.RoomNumber=r.RoomNumber AND c2.Username='$loginUserName') as Joined,RoomNumber, StartUser, UserFullName, (SELECT COUNT(c.Username) FROM chatuser c WHERE c.RoomNumber=r.RoomNumber) AS Count FROM chatroom r, puser p WHERE r.StartUser=p.Username";
                    $result = mysql_query($query, $conn);

                    $count = mysql_numrows($result);
                    echo "<table width='99%'>";
                    for ($i = 0; $i < $count; $i++) {
                        $roomNumber = mysql_result($result, $i, "RoomNumber");
                        $startUser = mysql_result($result, $i, "StartUser");
                        $startUserFullName = mysql_result($result, $i, "UserFullName");
                        $numOfUsers = mysql_result($result, $i, "Count");
                        $joined = mysql_result($result, $i, "Joined");
                        echo '<tr class="forumRow">
                            <td width="64" height="66"><img src="images/chatroom.png" width="64" /></td>
                            <td class="forumCell"><span class="forumTitle">Chat Room # ' . $roomNumber . '</a></span><br />
                               <span class="startUserFullName">Started by: ' . $startUser . ' (' . $startUserFullName . ') </span> <BR/>
                                <span class="forumModerator">Joined by: ' . $numOfUsers . ' user';
                        if ($numOfUsers > 1) {
                            echo 's';
                        }
                        echo '</span></td><td class="statusCell"><span class="forumModerator">';
                        // JOIN OR ENTER


                        if ($joined == 'yes') {
                            echo '<a class="box demo-1" href="chatroom.php?roomNumber=' . $roomNumber . '">ENTER</a>';
                        } else {
                            echo '<a class="box demo-1" href="process.php?action=joinChatRoom&roomNumber=' . $roomNumber . '">JOIN</a>';
                        }


                        echo '</span></td></tr>';
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