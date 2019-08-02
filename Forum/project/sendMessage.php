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
        <title>Send Message</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="decor2.css" rel="stylesheet" type="text/css"/>
        <script src="js/tinymce/tinymce.min.js" type="text/javascript"></script>
        <script>
            tinymce.init({
                selector: 'textarea',
                height: 250,
                menubar: false,
                resize: false,
                plugins: [
                    'advlist autolink lists link image charmap print preview anchor',
                    'searchreplace visualblocks code fullscreen',
                    'insertdatetime media table contextmenu paste code'
                ],
                toolbar: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
                content_css: '//www.tinymce.com/css/codepen.min.css'
            });


        </script>

    </head>
    <body>
        <div style="position: absolute; top: 0; right: 0; width: 400px; text-align:right; font-family:tahoma, verdana, arial, sans-serif; font-weight: bold; font-size: 12px; ">
            login as <span style="color:brown;"><?php echo $_SESSION["loginStatus"]; ?></span>:<span style="color:green;"><?php echo ' ' . $_SESSION['loginUser'] ?> (<?php echo $_SESSION['loginUserFullName'] ?>)</span>
        </div>
        <?php
        if (count($_POST) > 0) {
            /* Form Required Field Validation */
            foreach ($_POST as $key => $value) {
                if (empty($_POST[$key])) {
                    $message = ucwords($key) . " field is required";
                    break;
                }
            }

            if (!isset($message)) {
                $conn = mysql_connect($host, $username, $password);
                if (!$conn) {
                    die('Could not connect: ' . mysql_error());
                }

                mysql_select_db($dbname, $conn);

                $query = "select * from puser WHERE Username= BINARY '" . $_POST["receiver"] . "' AND Username != BINARY '" . $_SESSION['loginUser'] . "'";
                $result = mysql_query($query) or trigger_error(mysql_error(), E_USER_ERROR);

                //$result = mysql_query($query, $conn);
                $count = mysql_num_rows($result);

                if ($count < 1) {
                    $message = "invalid receiver's username!";
                    mysql_close($conn);
                } else {
                    $query = "INSERT INTO pmailbox (Subject, MsgTime, MsgText, Sender, Receiver, Status) "
                            . "VALUES ('" . mysql_real_escape_string($_POST["subject"]) . "', now(), '" . mysql_real_escape_string($_POST["content"]) . "', '" . $_SESSION['loginUser'] . "', '" . $_POST["receiver"] . "', 'new')";

                    $result = mysql_query($query, $conn);
                    if ($result) {
                        $message = "sent successfully!";
                        unset($_POST);

                        header("Location: outbox.php?sent=ok");
                    } else {
                        $message = "Problem in sending you message. Try Again!";
                    }
                    mysql_close($conn);
                }
            }
        }
        ?>
        <div id="page-wrap">
            <div class="container">
                <!-- header -->
                <header id="header">
                    <h1 class="title">NEW MESSAGE FROM <?php echo strtoupper($_SESSION['loginUserFullName']) ?></h1>
                </header>
                <!-- Navigation -->
                <nav id="menu" class="clearfix">
                    <ul>
                        <li><a class="links"  href="mainpage.php">HOME</a></li>
                        <li><a class="links"  href="inbox.php">INBOX</a></li>
                        <li><a class="links"  href="outbox.php">OUTBOX</a></li>
                        <li><a class="links"  href="process.php?action=signout">SIGN-OUT</a></li>                   
                    </ul>
                </nav> 

                <section>
                    <p class="message"><?php if (isset($message)) echo $message; ?></p>
                    <form action="" method="post" >
                        <div class="messageHeader">
                            <input type="text" autofocus placeholder="Receiver Username" style="font-family:tahoma, verdana, arial, sans-serif; font-size: 16px;width:98%;cursor:text; margin-bottom: 20px;height: 32px;" name="receiver" value="<?php if (isset($_POST['receiver'])) {
            echo $_POST['receiver'];
        } else {
            if (isset($_GET['toUser'])) {
                echo $_GET['toUser'];
            }
        } ?>"/><br/>
                            <input type="text" placeholder="Subject" name="subject" style="font-family:tahoma, verdana, arial, sans-serif; font-size: 16px;font-weight: bold; width:98%;cursor:text;margin-bottom: 20px;height: 32px;" value="<?php if (isset($_POST['subject'])) {echo $_POST['subject'];} ?>" /><br/>
                        </div>                  
                        <textarea name="content" id="content" style="width:98%;cursor:text; height: 230px; padding-bottom: 50px;"><?php if (isset($_POST['content'])) echo $_POST['content']; ?></textarea>
                        <!--<p><input type="submit" value="Submit" class="submit"></p> -->
                        <button>SEND</button>
                    </form>
                </section>
            </div> 
        </div>
        <footer>
            <div class="footer">Liam Le's Final Project</div>
        </footer>
    </body>
</html>