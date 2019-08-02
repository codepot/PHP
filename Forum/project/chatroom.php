<?php
ob_start();
session_start();
require_once 'db_config.php';
//include 'db.php';
if (!isset($_SESSION['loginUser'])) {
    session_unset();
    session_destroy();
    header("location:index.php");
    exit();
}

$loginUserName = $_SESSION['loginUser'];
$role = $_SESSION['loginStatus'];
$fullname = $_SESSION["loginUserFullName"];
$roomNumber = 0;
if (isset($_GET['roomNumber'])) {
    $roomNumber = $_GET['roomNumber'];
}
?>
<html>
    <head>
        <title>CHAT ROOM Number <?php echo $roomNumber; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">       
        <link href="decor2.css" rel="stylesheet" type="text/css"/>
        <script src="jquery-3.1.1.min.js" type="text/javascript"></script>
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


            .startUserFullName{
                font-weight: bold;
                color: #993300;
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

           
            textArea{
                font-family: Arial, Helvetica, sans-serif;
                font-size: 14px;
                font-weight: bold;
                color: #206ea1;
                margin-top: 5px;
                margin-left: 5px;
                float: left;
                border:1px solid #999999;               
                width: 70%;
                min-width: 250px;
            }   

            .fullname{
                font-family: Arial, Helvetica, sans-serif;
                font-size: 12px;
                font-weight: bolder;
                color: #090;
                width: fit-content;
                float: left;
                display: block;
                margin-top: 10px;
                display: inline-table;
            }

            .send{
                font-family: Arial, Helvetica, sans-serif;
                font-size: 12px;
                font-weight: bolder;
                float: left;
                margin-left: 5px;
                margin-top: 5px;
                height: 36px;
                display: inline-table;
                border-radius: 5px;
                border: 2px solid #4CAF50;
            }

            .send:hover{
                background-color: #FFED85;
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

        <script>
            function ajax() {

                var req = new XMLHttpRequest();

                req.onreadystatechange = function () {

                    if (req.readyState == 4 && req.status == 200) {
                        var text = req.responseText;
                        document.getElementById('chat_box').innerHTML = text;
                        scrollToBottom();
                    }
                }

                req.open('GET', 'chat.php?room=<?php echo $roomNumber; ?>', true);
                req.send();

            }
            setInterval(function () {
                ajax()
            }, 3000);


            function scrollToBottom() {
                var div = document.getElementById("chat_box");
                div.scrollTop = div.scrollHeight - div.clientHeight;
            }

            

            $(function () {
                $("#msg").keypress(function (e) {
                    if (e.which == 13) {
                        //submit form via ajax, this is not JS but server side scripting so not showing here
                        $('input[name = submit]').click();
                        $(this).val("");
                        e.preventDefault();
                    }
                });
            });

        </script>
    </head>



    <body onload="ajax();">

        <div id="page-wrap">
            <div class="container">
                  <div style="position: absolute; top: 0; right: 0; width: 400px; text-align:right; font-family:tahoma, verdana, arial, sans-serif; font-weight: bold; font-size: 12px; ">
                    login as <span style="color:brown;"><?php echo $_SESSION["loginStatus"]; ?></span>:<span style="color:green;"><?php echo ' '.$_SESSION['loginUser'] ?> (<?php echo $_SESSION['loginUserFullName'] ?>)</span>
                </div>
                <!-- header -->
                <header id="header">
                    <h1 class="title">CHAT ROOM # <?php echo $roomNumber; ?></h1>
                </header>
                <!-- Navigation -->
                <nav id="menu" class="clearfix">
                    <ul> 
                        <li><a class="links"  href="chatbox.php">BACK</a></li>
                        <li><a class="links"  href="mainpage.php">HOME</a></li>

                        <li><a href="process.php?action=signout">SIGN-OUT</a></li>  
                    </ul>
                </nav> 
                <section>
                    <div id="chat_box" class="body">


                    </div>
                    <form id="chat_form" name="chat_form" method="post" action="" style="float:left; width:100%; margin-top: 15px; border-top: solid 1px #07a;">
                        <label for="msg" class="fullname"><?php echo $fullname; ?>: </label>
                        <textarea name="msg" placeholder="enter message" id="msg" autofocus></textarea>
                        <input class="send" type="submit" name="submit" id="submit" value="Send"/>
                    </form>
                </section>
            </div>
        </div>
        <footer>
            <div class="footer">Liam Le's Final Project</div>
        </footer>
        <?php
        if (isset($_POST['submit'])) {

            /*
              $msg = '<span style="color:green;">' . $fullname . "</span> :";
              $msg = $msg . '<span style="color:brown;">' . $_POST['msg'] . "</span><BR>";

             */

            $msg = '<tr> <td><span style="color:green;">&#8227; ' . $fullname . "</span>: ";
            $msg = $msg . '<span style="color:brown;">' . $_POST['msg'] . "</span></td></tr>";


            $conn = mysql_connect($host, $username, $password);
            if (!$conn) {
                die('Could not connect: ' . mysql_error());
            }

            mysql_select_db($dbname, $conn);

            $query = "UPDATE chatroom SET Content = CONCAT(Content, '$msg') WHERE RoomNumber=$roomNumber";

            $result = mysql_query($query, $conn);

            if ($result) {
                echo "<embed loop='false' src='chat.wav' hidden='true' autoplay='true'/>";
            }

            mysql_close($conn);
        }
        ?>

    </body>
</html>