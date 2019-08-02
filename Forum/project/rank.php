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

$rank = 0;
?>


<html>
    <head>
        <script src="jquery-3.1.1.min.js" type="text/javascript"></script>
        <title>Ranking</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">       
        <link href="boxes.css" rel="stylesheet" type="text/css"/>
        <link href="css/rank.css" rel="stylesheet" type="text/css"/>
        <link href="http://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet" type="text/css"/>


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
                color: #008000;
                font-weight: bold;
                font-size: 16px;
                text-align: left;
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



            .main { 
                width: 95%; 
                margin: 0 auto; 


                padding: 20px;
            }

            .header{
                height: 100px;    
            }
            .content{    
                height: 90%;
                border-top: 1px solid #ccc;
                padding-top: 15px;
            }

            .heading{
                color: #FF5B5B;
                margin: 10px 0;
                padding: 10px 0;
                font-family: trebuchet ms;
            }

            #dv1, #dv0{


                margin: 0 auto;
                padding: 0;
            }


             label { margin: auto; padding: 0; }
            body{ margin: 20px; }
            h1 { font-size: 1.5em; margin: 10px; }

            .rating { 
                width: fit-content;
                border: none;
                margin: 0 auto;
                display: block;
            }



            .rating > input { display: none; } 
            .rating > label:before { 

                font-size: 1.25em;
                font-family: FontAwesome;
                display: inline-block;
                content: "\f005";
            }

            .rating > .half:before { 
                content: "\f089";
                position: absolute;
            }

            .rating > label { 
                color: #ddd; 
               float: right;
               margin: 0 auto;
            }

            .rating > input:checked ~ label, 
            .rating:not(:checked) > label:hover,  
            .rating:not(:checked) > label:hover ~ label { color: #FFD700;  }

            .rating > input:checked + label:hover, 
            .rating > input:checked ~ label:hover,
            .rating > label:hover ~ input:checked ~ label, 
            .rating > input:checked ~ label:hover ~ label { color: #FFED85;  }     


        </style>


    </head>

    <?php
    $forumName = $_GET['forumName'];
    $threadTitle = $_GET['title'];
    $role = $_SESSION['loginStatus'];
    $loginUserName = $_SESSION['loginUser'];
    $threadNum = $_GET['threadNumber'];


    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (!isset($_POST['rating'])) {
            $message = "Please at least rank 1 star, thank you!";
        }

        if (!isset($message)) {

            $conn = mysql_connect($host, $username, $password);
            mysql_select_db($dbname, $conn);
            if (!$conn) {
                die('Could not connect: ' . mysql_error());
            }
            $rate = mysql_real_escape_string($_POST['rating']);

            $sql = "INSERT INTO rank (Username, ThreadNumber, Ranking) VALUES ('$loginUserName', $threadNum, $rate)";

            $result = mysql_query($sql, $conn);

            if ($result) {
                //$message = "You have ranked successfully!";
                unset($_POST);
                echo "<script>alert('You have ranked successfully');</script>";
            } else {
                $message = "Problem in ranking the thread. Please try Again!";
            }
            mysql_close($conn);
        }
    }
    ?>
    <body>
        <div style="position: absolute; top: 0; right: 0; width: 400px; text-align:right; font-family:tahoma, verdana, arial, sans-serif; font-weight: bold; font-size: 12px; ">
            login as <span style="color:brown;"><?php echo $_SESSION["loginStatus"]; ?></span>:<span style="color:green;"><?php echo ' ' . $_SESSION['loginUser'] ?> (<?php echo $_SESSION['loginUserFullName'] ?>)</span>
        </div>
        <div id="page-wrap">
            <div class="container">
                <!-- header -->

                <header id="header">
                    <h1 class="ftitle">Ranking Thread: "<?php echo $threadTitle; ?>"</h1>
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

                        $query = "SELECT * FROM rank WHERE Username='$loginUserName' AND ThreadNumber=" . $threadNum;

                        $result = mysql_query($query, $conn);
                        $count = mysql_numrows($result);
                        if ($count > 0) {
                            $rank = mysql_result($result, 0, "Ranking");
                        }

                        $query = "SELECT Coalesce(ROUND(AVG(Ranking)),0) AS Ranking, COUNT(ThreadNumber) AS Times FROM rank WHERE ThreadNumber=" . $threadNum;

                        //Coalesce( Avg( D.avgVote ), 0 ) 

                        $result = mysql_query($query, $conn);
                        $count = mysql_numrows($result);
                        if ($count > 0) {
                            $numOfUsers = mysql_result($result, 0, "Times");
                            $avgRanking = mysql_result($result, 0, "Ranking");
                        } else {
                            $numOfUsers = 0;
                            $avgRanking = 0;
                        }



                        mysql_close($conn);
                        ?>

                        <li><a href="threads.php?forumName=<?php echo urlencode($forumName); ?>">BACK</a></li>  
                        <li><a href="forum.php">FORUMS</a></li>                          
                        <li><a href="process.php?action=signout">SIGN-OUT</a></li>  
                    </ul>
                </nav> 

                <div class="main">

                    <div class="content">
                        <div>
                            Currently, this thread have been ranked by <?php
                            echo $numOfUsers;
                            if ($numOfUsers > 1) {
                                echo ' users';
                            } else {
                                echo ' user';
                            }
                            ?> <br>
                            The average ranking is <?php echo $avgRanking; ?>
                        </div>

                        <div class="heading">Please give me five stars</div>

                        <div id="dv0"></div>

                        <script>
                            $(document).ready(function () {
                                $("#ranking .stars").click(function () {
                                    $(this).attr("checked");
                                });
                            });
                        </script>
                        <form method="post" action="">
                           
                                <fieldset class="rating">
                                    <input class="stars" type="radio" id="star5" name="rating" value="5" <?php if ($rank == 5) echo ' checked'; ?>/>
                                    <label class = "full" for="star5" title="Awesome - 5 stars"></label>
                                    <input class="stars" type="radio" id="star4" name="rating" value="4" <?php if ($rank == 4) echo ' checked'; ?>/>
                                    <label class = "full" for="star4" title="Pretty good - 4 stars"></label>
                                    <input class="stars" type="radio" id="star3" name="rating" value="3" <?php if ($rank == 3) echo ' checked'; ?>/>
                                    <label class = "full" for="star3" title="Meh - 3 stars"></label>
                                    <input class="stars" type="radio" id="star2" name="rating" value="2" <?php if ($rank == 2) echo ' checked'; ?>/>
                                    <label class = "full" for="star2" title="Kinda bad - 2 stars"></label>
                                    <input class="stars" type="radio" id="star1" name="rating" value="1" <?php if ($rank == 1) echo ' checked'; ?>/>
                                    <label class = "full" for="star1" title="Sucks big time - 1 star"></label>

                                </fieldset>
                           
                            <br>
                            <?php
                            if ($rank > 0) {
                                echo '<p class="signupMessage">You have already ranked this thread</p>';
                            } else {
                                echo '<button>OKAY</button>';
                            }
                            ?>

                            <p class="signupMessage"><?php if (isset($message)) echo $message; ?></p>

                        </form>


                    </div>
                </div>

            </div>

        </div>


        <footer>
            <div class="footer">Liam Le's Final Project</div>
        </footer>
    </body>
</html>