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
        <title>Message View</title>		
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="decor2.css" rel="stylesheet" type="text/css"/>
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    </head>
    <body>
        <div style="position: absolute; top: 0; right: 0; width: 400px; text-align:right; font-family:tahoma, verdana, arial, sans-serif; font-weight: bold; font-size: 12px; ">
            login as <span style="color:brown;"><?php echo $_SESSION["loginStatus"]; ?></span>:<span style="color:green;"><?php echo ' ' . $_SESSION['loginUser'] ?> (<?php echo $_SESSION['loginUserFullName'] ?>)</span>
        </div>
        <div id="page-wrap">
            <div class="container">
                <!-- header -->
                <header id="header">
                    <h1 class="title"><?php echo strtoupper($_SESSION['loginUserFullName']) ?>'S MESSAGE CONTENT</h1>
                </header>
                <!-- Navigation -->
                <nav id="menu" class="clearfix">
                    <ul>
                        <li><a class="links"  href="mainpage.php">HOME</a></li>
                        <li><a href="sendMessage.php">NEW</a></li>
                        <li><a href="inbox.php">INBOX</a></li>
                        <li><a href="outbox.php">OUTBOX</a></li>
                        <li><a href="process.php?action=signout">SIGN-OUT</a></li>                   
                    </ul>
                </nav> 

                <section>
                    <div>   
                        <?php
                        if (isset($_GET['messageId']) && !empty($_GET['messageId'])) {
                            $msgId = $_GET['messageId'];
                            $from = $_GET['from'];
                            $status = $_GET['status'];

                            $conn = mysql_connect($host, $username, $password);
                            if (!$conn) {
                                die('Could not connect: ' . mysql_error());
                            }

                            mysql_select_db($dbname, $conn);

                            $query = "SELECT MessageID, s.UserFullName as SenderName, r.UserFullName as ReceiverName, MsgTime, Subject, MsgText, s.Username as SenderUserName, r.Username as ReceiverUserName from puser s, puser r, pmailbox WHERE MessageID=$msgId AND Sender = s.Username AND Receiver = r.Username LIMIT 1";

                            $result = mysql_query($query, $conn);
                            $count = mysql_numrows($result);

                            if ($count > 0) {


                                echo '<table width="692" border="0" cellpadding="5">
                            <tr>
                                <td width="64" bordercolor="#FFFFFF"><div align="right"><span class="style5">From:</span></div></td>
                                <td width="602" bordercolor="#FFFFFF"><span class="style1"><strong>' . mysql_result($result, 0, "SenderName") . '</strong> <small>&lt;username:' . mysql_result($result, 0, "SenderUserName") . '&gt;</small></span></td>
                            </tr>
                            <tr>
                                <td bordercolor="#FFFFFF"><div align="right"><span class="style5">To:</span></div></td>
                                <td bordercolor="#FFFFFF"><span class="style1"><strong>' . mysql_result($result, 0, "ReceiverName") . '</strong> <small>&lt;username:' . mysql_result($result, 0, "ReceiverUserName") . '&gt;</small></span></td>
                            </tr>
                            <tr>
                                <td bordercolor="#FFFFFF"><div align="right"><span class="style5">Time:</span></div></td>
                                <td bordercolor="#FFFFFF"><span class="style1"><small>' . mysql_result($result, 0, "MsgTime") . '</small></span></td>
                            </tr>
                            <tr>
                                <td bordercolor="#FFFFFF"><div align="right"><span class="style5">Subject:</span></div></td>
                                <td bordercolor="#FFFFFF"><span class="style3">' . mysql_result($result, 0, "Subject") . '</span></td>
                            </tr>
                        </table>';

                                echo ' <div style="text-align: left; margin-right: 20px ;margin-top: 10px ;margin-bottom: 15px ;float:right; border:2px solid #eff3f9; width: 750px; height: 300px; overflow-y: scroll;" >' . mysql_result($result, 0, "MsgText") . '</div>';

                                echo '<div><a style="color: #905; font-family: Arial, Helvetica, sans-serif; font-weight: bold; text-decoration: #905; font-size: 12;"  href="process.php?action=delete&msgId=' . mysql_result($result, 0, "MessageID") . '&sender=' . mysql_result($result, 0, "SenderUserName") . '&receiver=' . mysql_result($result, 0, "ReceiverUserName") . '&from=' . $from . '" onclick="return confirm(\'Please confirm if you want to delete this message?\')">Delete This Message</a></div>';
                            
                                if($from=='inbox'){
                                    echo '<div style="margin-top: 25px;"><a style="background: transparent url(\'images/reply.png\') center left no-repeat; padding-left: 18px;  color: #blue; font-family: Arial, Helvetica, sans-serif; font-weight: bold; text-decoration: #905; font-size: 12;"  href="sendMessage.php?toUser=' . mysql_result($result, 0, "SenderUserName") . '&from=' . mysql_result($result, 0, "ReceiverUserName").'">Reply</a></div>';
                                }
                            }
                        }
                        ?>

                    </div>       
                </section>


                <?php
                if ($status == 'new' && $from == 'inbox') {
                    $query = "UPDATE pmailbox SET Status ='read' WHERE MessageID=$msgId";
                    $result = mysql_query($query, $conn);
                }

                mysql_close($conn);
                ?>
            </div>

            <p class="signupMessage"><?php if (isset($message)) echo $message; ?></p>

        </div>
        <footer>
            <div class="footer">Liam Le's Assignment #3</div>
        </footer>
    </body>
</html>