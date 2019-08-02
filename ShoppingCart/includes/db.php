<?php
	@mysql_connect("localhost","root","") or die("Database now is not available, please try again later");
	@mysql_select_db("shopping") or die("Shopping now is not available, please try again later");
	session_start();
?>