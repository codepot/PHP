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
        <title><?php echo strtoupper($_SESSION['loginUserFullName']) ?>'S INBOX</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="decor2.css" rel="stylesheet" type="text/css"/>
        <link href="boxes.css" rel="stylesheet" type="text/css"/>

    </head>
    <body>
        <div style="position: absolute; top: 0; right: 0; width: 400px; text-align:right; font-family:tahoma, verdana, arial, sans-serif; font-weight: bold; font-size: 12px; ">
            login as <span style="color:brown;"><?php echo $_SESSION["loginStatus"]; ?></span>:<span style="color:green;"><?php echo ' ' . $_SESSION['loginUser'] ?> (<?php echo $_SESSION['loginUserFullName'] ?>)</span>
        </div>
        <div id="page-wrap">
            <div class="container">
                <!-- header -->
                <header id="header">
                    <h1 class="title"><?php echo strtoupper($_SESSION['loginUserFullName']) ?>'S INBOX</h1>
                </header>
                <!-- Navigation -->
                <nav id="menu" class="clearfix">
                    <ul>
                        <li><a class="links"  href="mainpage.php">HOME</a></li>
                        <li><a class="links" href="inbox.php">REFRESH</a></li>
                        <li><a class="links" href="outbox.php">OUTBOX</a></li>
                        <li><a class="links" href="sendMessage.php">NEW</a></li>
                        <li><a class="links" href="process.php?action=signout">SIGN-OUT</a></li>                   
                    </ul>
                </nav> 

                <section>

                    <div>
                        <table><tr>
                                <th class="th_name"><span>From</span></th>
                                <th class="th_time"><span>Time</span></th>
                                <th class="th_subject"><span>Subject</span></th>
                                <th class="th_status"><span></span></th>
                            </tr>
                        </table>
                    </div>

                    <div class="body">
                        <table>

                            <?php
                            if (!isset($view_message)) {
                                $conn = mysql_connect($host, $username, $password);
                                if (!$conn) {
                                    die('Could not connect: ' . mysql_error());
                                }


                                mysql_select_db($dbname, $conn);

                                $query = 'SELECT MessageID, u2.UserFullName, Subject, MsgTime, MsgText, Receiver, p.Status FROM puser u1, puser u2, pmailbox p WHERE u1.Username = Sender AND Receiver=\'' . $_SESSION['loginUser'] . '\' AND p.Status != \'deleted by receiver\' AND p.Status != \'deleted by both\' AND u2.Username=Sender ORDER BY MsgTime DESC';

                                $result = mysql_query($query, $conn);

                                $count = mysql_numrows($result);

                                for ($i = 0; $i < $count; $i++) {
                                    $messageId = mysql_result($result, $i, "MessageID");
                                    $fullname = mysql_result($result, $i, "UserFullName");
                                    $msgTime = mysql_result($result, $i, "MsgTime");
                                    $subject = mysql_result($result, $i, "Subject");
                                    $status = mysql_result($result, $i, "Status");


                                    if ($status == 'new') {
                                        echo ' <tr> <td class="th_name">' . $fullname . '</td> <td class="th_time">' . $msgTime . '</td> <td class="th_subject"><b><a class="subject_link_new" href="viewMessage.php?messageId=' . $messageId . '&from=inbox&status=' . $status . '">' . $subject . '</a></b></td>  <td><img src="new.png" alt="New Message" height=16 width=16></td> </tr>';
                                    } else {
                                        echo ' <tr> <td class="th_name">' . $fullname . '</td> <td class="th_time">' . $msgTime . '</td> <td class="th_subject"><b><a class="subject_link" href="viewMessage.php?messageId=' . $messageId . '&from=inbox&status=' . $status . '">' . $subject . '</a></td>  <td></td> </tr>';
                                    }
                                }

                                mysql_close($conn);
                            }
                            ?>

                        </table>
                    </div>



                </section>

            </div>

            <p class="signupMessage"><?php if (isset($message)) echo $message; ?></p>

        </div>
        <footer>
            <div class="footer">Liam Le's Final Project</div>
        </footer>
    </body>
</html>