<?php
	/***********************************************************************
		database.lib.php
		-------------------
		begin		:	unknown
		copyright	:	(C) 2006 Timothy B Martin
		email		:	tim@design1st.org
		authors		:	Timothy B Martin
	  	  
	***********************************************************************
	*
	*   This program is free software; you can redistribute it and/or 
	*   modify it under the terms of the GNU General Public License as 
	*   published by the Free Software Foundation; either version 2 of the 
	*   License, or (at your option) any later version.
	*
	***********************************************************************/
	require_once(EZSQL_CORE_INCLUDE);
	require_once(EZSQL_DB_INCLUDE);
	
	$db = new ezSQL_mysql();
	$db->show_errors();
	$db->quick_connect(EZSQL_DB_USER, EZSQL_DB_PASSWORD, EZSQL_DB_NAME, EZSQL_DB_HOST);
	//$db->debug_all = TRUE;
	
	if (empty($db->captured_errors)) {
		// fix database connection!
		mysql_query("SET NAMES 'utf8'", $db->dbh);
		mysql_query("SET CHARACTER SET utf8", $db->dbh);
	}

	//
	// Database Functions
	//
	
	// get the list of users for the filter popups
	function dbUsernames(&$db) {
		$sql  = "SELECT username ";
		$sql .= "FROM users ";
		$sql .= "ORDER BY username ASC";
		
		return $db->get_results($sql, ARRAY_A);
	}
	
	// get the total link count for the footer
	function dbLinkCount(&$db, $whereclause = NULL) {
		$sql  = "SELECT count(*) ";
		$sql .= "FROM links ";
		$sql .= $whereclause;
		
		return $db->get_var($sql);
	}
	
	// create an account
	function dbCreateAccount(&$db, $accountArray) {		
		$sql  = "INSERT into users ";
		$sql .= "(realname, username, password, usergroup) VALUES (";
		$sql .= "'".$db->escape($accountArray['realname'])."', ";
		$sql .= "'".$db->escape($accountArray['username'])."', ";
		$sql .= "'".$db->escape($accountArray['password'])."', ";
		$sql .= "'".$db->escape($accountArray['usergroup'])."')";
		
		return $db->query($sql);
	}

	//
	// Mysql Dump Processer
	//
	function parse_mysql_dump($url, $ignoreerrors = false) {
		$file_content = file($url);
		//print('<pre>');print_r($file_content);print('</pre>');
		$query = "";
		foreach($file_content as $sql_line) {
			$tsl = trim($sql_line);
			if (($sql_line != "") && (substr($tsl, 0, 2) != "--") && (substr($tsl, 0, 1) != "#")) {
				$query .= $sql_line;
				if(preg_match("/;\s*$/", $sql_line)) {
					$result = mysql_query($query);
					if (!$result && !$ignoreerrors) die(mysql_error());
					$query = "";
				}
			}
		}
	}

?>