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

if(isset($_GET['banned'])){
    echo "<script>alert('You have banned a user successfully');</script>";
    unset($_GET['banned']);
}
?>


<html>
    <head>
        <title><?php echo strtoupper($_SESSION['loginUserFullName']) ?>'S MAIN PAGE</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="decor2.css" rel="stylesheet" type="text/css"/>
        <link href="boxes.css" rel="stylesheet" type="text/css"/>
        <link href="css/mainpage.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>

        <div id="page-wrap">
            <div class="container">
                <div style="position: absolute; top: 0; right: 0; width: 400px; text-align:right; font-family:tahoma, verdana, arial, sans-serif; font-weight: bold; font-size: 12px; ">
                    login as <span style="color:brown;"><?php echo $_SESSION["loginStatus"]; ?></span>:<span style="color:green;"><?php echo ' '.$_SESSION['loginUser'] ?> (<?php echo $_SESSION['loginUserFullName'] ?>)</span>
                </div>   
                <!-- header -->
                <header id="header">
                    <h1 class="title"><?php echo strtoupper($_SESSION['loginUserFullName']) ?>'S MAIN PAGE</h1>
                </header>
                <!-- Navigation -->
                <nav id="menu" class="clearfix">
                    <ul>                    
                        <li><a href="process.php?action=signout">SIGN-OUT</a></li>                   
                    </ul>
                </nav> 


                <div class="linkmenu">
                    <div class="imageIcon"><a href="inbox.php"><img src="images/mailbox.png" style="width:64px; height:64px"><label class="menulabel">MAILBOX</label></a></div>
                    <div class="imageIcon"><a href="forum.php"><img src="images/forum.png" style="width:64px; height:64px"><label class="menulabel">FORUM</label></a></div>
                    <div class="imageIcon"><a href="chatbox.php"><img src="images/chat.png" style="width:64px; height:64px"><label class="menulabel">CHATBOX</label></a></div>
                    <?php
                    $role = $_SESSION['loginStatus'];
                    if ($role == 'administrator') {
                        echo '<div class="imageIcon"><a href="addUser.php"><img src="images/user.png" style="width:64px; height:64px"><label class="menulabel">USER MANAGER</label></a></div>';
                        echo '<div class="imageIcon"><a href="banUser.php"><img src="images/banUser.png" style="width:64px; height:64px"><label class="menulabel">BAN USER </label></a></div>';
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