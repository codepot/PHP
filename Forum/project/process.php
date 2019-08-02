<?php

ob_start();
session_start();
require_once 'db_config.php';
if (!isset($_SESSION['loginUser'])) {
    session_unset();
    session_destroy();
    header("location:index.php");
    exit();
} else {

    if (isset($_GET['action']) && !empty($_GET['action'])) {
        $action = $_GET['action'];

        if ($action == "signout") {
            session_start();
            session_unset();
            session_destroy();

            header("location:index.php");
            exit();
        }

        if ($action == "approve") {
            $forumName = rawurldecode($_GET['forumName']);


            $conn = mysql_connect($host, $username, $password);
            if (!$conn) {
                die('Could not connect: ' . mysql_error());
            }
            mysql_select_db($dbname, $conn);
            $query = "UPDATE forum SET Status ='approved' WHERE ForumName='" . $forumName . "'";
            mysql_query($query, $conn);

            mysql_close($conn);

            header("location:forum.php");
            exit();
        }

        if ($action == "closeThread") {
            $threadNum = $_GET['threadNumber'];
            $forumName = $_GET['forumName'];

            $conn = mysql_connect($host, $username, $password);
            if (!$conn) {
                die('Could not connect: ' . mysql_error());
            }
            mysql_select_db($dbname, $conn);
            $query = "UPDATE thread SET Status ='closed' WHERE ThreadNumber=" . $threadNum . "";
            mysql_query($query, $conn);

            mysql_close($conn);

            header("location:threads.php?forumName=" . rawurlencode($forumName));
            exit();
        }

        if ($action == "newChatroom") {
            $login = $_SESSION['loginUser'];
            $conn = mysql_connect($host, $username, $password);
            if (!$conn) {
                die('Could not connect: ' . mysql_error());
            }
            mysql_select_db($dbname, $conn);
            $query = "INSERT INTO chatroom(Content, StartUser) VALUES ('','$login')";
            mysql_query($query, $conn);
            $chatboxNumber = mysql_insert_id();
            $query = "INSERT INTO chatuser(RoomNumber, Username) VALUES ($chatboxNumber,'$login')";
            mysql_query($query, $conn);
            mysql_close($conn);

            header("location:chatbox.php?box=$chatboxNumber");
            exit();
        }
        
        // "process.php?action=joinChatRoom&roomNumber=3
        if ($action == "joinChatRoom") {
            $login = $_SESSION['loginUser'];
            $roomNum = $_GET['roomNumber'];
            
            //  RoomNumber Username
            $conn = mysql_connect($host, $username, $password);
            if (!$conn) {
                die('Could not connect: ' . mysql_error());
            }
            mysql_select_db($dbname, $conn);
            $query = "INSERT INTO chatuser(RoomNumber, Username) VALUES (".$roomNum.",'$login')";
            mysql_query($query, $conn);
           
            mysql_close($conn);

            header("location:chatbox.php?joinedRoomNum=$roomNum");
            exit();
        }

        if ($action == "removeThread") {
            $threadNum = $_GET['threadNumber'];
            $forumName = $_GET['forumName'];

            $conn = mysql_connect($host, $username, $password);
            if (!$conn) {
                die('Could not connect: ' . mysql_error());
            }
            mysql_select_db($dbname, $conn);
            $query = "UPDATE thread SET Status ='removed' WHERE ThreadNumber=" . $threadNum . "";
            mysql_query($query, $conn);

            mysql_close($conn);

            header("location:threads.php?forumName=" . urlencode($forumName));
            exit();
        }

        if ($action == 'removePost') {
            $postNum = $_GET['postNumber'];
            $threadNumber = $_GET['threadNumber'];
            $threadTitle = $_GET['title'];
            $forumName = $_GET['forumName'];

            $conn = mysql_connect($host, $username, $password);

            if (!$conn) {
                die('Could not connect: ' . mysql_error());
            }

            mysql_select_db($dbname, $conn);

            $query = "DELETE FROM post WHERE PostNumber=" . $postNum;

            mysql_query($query, $conn);

            mysql_close($conn);

            header("Location: posts.php?threadNumber=" . $threadNumber . '&title=' . urlencode($threadTitle) . '&forumName=' . urlencode($forumName));

            exit();
        }



        if ($action == "disapprove") {
            $forumName = rawurldecode($_GET['forumName']);

            $conn = mysql_connect($host, $username, $password);
            if (!$conn) {
                die('Could not connect: ' . mysql_error());
            }
            mysql_select_db($dbname, $conn);
            $query = "UPDATE forum SET Status ='disapproved' WHERE ForumName='" . $forumName . "'";
            mysql_query($query, $conn);

            mysql_close($conn);

            header("location:forum.php");
            exit();
        }

        if ($action == "delete") {
            $login = $_SESSION['loginUser'];
            $action = $_GET['msgId'];
            $msgId = $_GET['msgId'];
            $sender = $_GET['sender'];
            $receiver = $_GET['receiver'];
            $from = $_GET['from'];
            $conn = mysql_connect($host, $username, $password);
            if (!$conn) {
                die('Could not connect: ' . mysql_error());
            }

            mysql_select_db($dbname, $conn);

            $query = "SELECT MessageID, Status FROM mailbox WHERE MessageID=$msgId";

            $result = mysql_query($query, $conn);
            $count = mysql_numrows($result);

            $status = mysql_result($result, 0, "Status");
            echo '-->' . $status;
            $newStatus = 'new';

            if ($from == 'inbox') {

                if ($status == 'deleted by sender') {
                    $newStatus = 'deleted by both';
                } else {
                    $newStatus = 'deleted by receiver';
                }
            } else { //outbox
                if ($status == 'deleted by receiver') {
                    $newStatus = 'deleted by both';
                } else {
                    $newStatus = 'deleted by sender';
                }
            }

            $query = "UPDATE mailbox SET Status ='" . $newStatus . "' WHERE MessageID=$msgId";

            mysql_query($query, $conn);

            mysql_close($conn);

            header("location:" . $from . ".php");
            exit();
        }
    }
}
?>
