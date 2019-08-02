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
$threadNum = $_GET['threadNumber'];
$loginUserName = $_SESSION['loginUser'];

if (isset($_POST['rating']) && !empty($_POST['rating'])) {
    $conn = mysql_connect($host, $username, $password);
    if (!$conn) {
        die('Could not connect: ' . mysql_error());
    }
    mysql_select_db($dbname, $conn);

    $rate = real_escape_string($_POST['rating']);

    $sql = "SELECT * FROM rank WHERE Username='$loginUserName' AND ThreadNumber=" . $threadNum;

    $result = mysql_query($sql, $conn);
    $count = mysql_numrows($result);
    if ($counts > 0) {
        echo "1";
    } else {
       
        $sql = "INSERT INTO rank (Username, ThreadNumber, Ranking) VALUES ('$loginUserName', $threadNum, $rate)";

        mysql_query($sql, $conn);
        echo "0";
    }
}
mysql_close($conn);
?>