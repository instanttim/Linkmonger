<?php
	/**
	* Linkmonger Set Up Script - use me once, then delete me!
	*
	* @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
	* @author     Timothy B Martin <tim@design1st.org>
	*/

	require_once("../configs/config.php");
	require_once("../include/database.lib.php");
	
	//
	// Connect
	//
	print("<p>Connecting to mysql...");

	$link = mysql_connect(EZSQL_DB_HOST, EZSQL_DB_USER, EZSQL_DB_PASSWORD);
	if ($link) {
		print("done.</p>");
	} else {
		die('Error: '.mysql_error());
	}
	
	//
	// Create Tables
	//
	print("<p>Creating tables...");

	$file = dirname(__FILE__)."/mysql.txt";
	if (file_exists($file)) {
		parse_mysql_dump($file);
		print("done.</p>");
	} else {
		print("Error: Couldn't find database script!</p>");
	}
	
	mysql_close($link);

	//
	// Create Admin Account
	//
	print("<p>Creating admin user...");
	$accountArray = array(
		"realname" => "Administrator",
		"username" => "admin",
		"password" => md5("changeme"),
		"usergroup" => "admin"
	);
	dbCreateAccount($db, $accountArray);
	print("done.</p>");
	
?>
