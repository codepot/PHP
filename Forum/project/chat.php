<?php

require_once 'db_config.php';

$roomNum = $_GET['room'];

$conn = mysql_connect($host, $username, $password);
if (!$conn) {
    die('Could not connect: ' . mysql_error());
}

mysql_select_db($dbname, $conn);


$query = "SELECT * FROM chatroom WHERE RoomNumber=$roomNum";

$result = mysql_query($query, $conn);

echo '<table name="chat_content" id="chat_content">' . mysql_result($result, 0, "Content") . '</table>';

mysql_close($conn);
?>