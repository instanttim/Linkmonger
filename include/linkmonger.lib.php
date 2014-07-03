<?php
	/***********************************************************************
		linkmonger.lib.php
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
	require_once("database.lib.php");

	//
	// Page Functions
	//
	
	function createSmarty()
	{
		require_once(SMARTY_INCLUDE);
		$smarty = new Smarty;
		$smarty->compile_check = TRUE;
		$smarty->debugging = FALSE;
		return $smarty;
	}
	
	function setWho(&$_COOKIE)
	{
		if (isset($_COOKIE['who']))
		{
			$who = $_COOKIE["who"];
		} else {
			$who = NULL;
		}
		
		return $who;
	}
	
	function setPage(&$_GET)
	{
		if (isset($_GET['page']))
		{
			$page = $_GET['page'];
		} else {
			$page = 0;
		}
		
		return $page;
	}
	
	function pageQueryString($queryArray, $count, $page, $change)
	{	
		// set up the paging urls
		if ($change > 0 && $count > LM_PAGELIMIT && ($page + $change) * LM_PAGELIMIT < $count)
		{
				$queryArray['page'] = $page + $change;
				$queryStr = http_build_query($queryArray);
				return "?".$queryStr;
		} else if ($change < 0 && $page > 0)
		{
			$queryArray['page'] = $page - 1;
			$queryStr = http_build_query($queryArray);
			return "?".$queryStr;
		}
	
		return;
	}
	
	function validateLinkSubmission(&$db, $formData) {
		
		if ($formData['action'] == "add") {
			$result = $db->get_results("SELECT title, link, submitter FROM links WHERE title='".$db->escape($formData['titleText'])."' OR link='".$db->escape($formData['linkText'])."' ", ARRAY_A);

			if ($result) {
				//if we get a result, then this link/title is in the database.
				$validData['error'] = "Your link was not added because it appears as though it's already been feed to Linkmonger.";

				/*
				$cur_row = pg_fetch_array($result, $row_num);
				$errormsg .= "<p>Existing link:<br>";
				$errormsg .= "&nbsp;&nbsp;".$cur_row['title']."<br>";
				$errormsg .= "&nbsp;&nbsp;<a href=\"".$cur_row['link']."\" target=\"linkmonger_link\">".$cur_row['link']."</a><br>";
				$errormsg .= "&nbsp;&nbsp;Submitted by: ".$cur_row['submitter']."<br>";
				*/
			}
		}

		// TITLE
		if ($formData['titleText'] == "") {
			$blankFields[] = "Title";
		} else {
			//$validData['title'] = $formData['titleText'];
			$validData['title'] = $db->escape($formData['titleText']);
		}

		// LINK
		if ($formData['linkText'] == "") {
			$blankFields[] = "URL";
		} else {
			$validData['link'] = $db->escape($formData['linkText']);
		}

		// ARCHIVED
		if (isset($formData['archivedCheck'])) {
			$validData['archived'] = "TRUE";
		} else {
			$validData['archived'] = "FALSE";
		}

		// DEAD LINK
		if (isset($formData['deadCheck'])) {
			$validData['dead'] = "TRUE";
		} else {
			$validData['dead'] = "FALSE";
		}

		// DESCRIPTION
		if ($formData['descriptionText'] == "") {
			$blankFields[] = "Description";
		} else {
			$validData['description'] = $db->escape($formData['descriptionText']);
		}

		// SUBMITTER
		if ($formData['submitterHidden'] == "") {
			$blankFields[] = "Submitter";
		} else {
			$validData['submitter'] = $formData['submitterHidden'];
		}

		if (isset($blankFields)) {
			$validData['error'] =  "Your link was not added because required fields were left blank.";
			$validData['blankfields'] = $blankFields;
		}

		return $validData;
	}


	//
	// Error handling functions
	//
 
	function errorMsg($errorcode)
	{
		$messages = array(
			// database errors
			"db_fail"		=>	
				"Couldn't connect to the database. Check the following:
				<ol>
					<li>Is the database server running?</li>
					<li>Have you set up the database properly?</li>
				</ol>",
			// link errors
			"link_none"		=>	"No links.",
			"badlinkid"		=>	"A link with that ID doesn't exist.",
			"missinglinkid"	=>	"No link ID was specified."
		);
		return $messages[$errorcode];
	}

	
	//
	// HTTP Status Checking
	//
	function httpStatus($url)
	{
		$status = "unknown";

		// get the headers
		//require "URLHelper.class.php";
		//$code = URLHelper::getHTTPStatusCode($url);

		// create a new CURL resource
		$ch = curl_init();

		// set URL and other appropriate options			
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
		curl_setopt($ch, CURLOPT_HEADER, TRUE);
		//curl_setopt($ch, CURLOPT_NOBODY, TRUE);

		// grab URL and pass it to the browser
		$result = curl_exec($ch);

		if (isset($result)) {	
			$chunk = substr($result, 0, 32);
			$temp = explode(" ", $chunk, 3);
		    $code = $temp[1];
		}

		// close CURL resource, and free up system resources
		curl_close($ch);


		// find out what the status is
		if (isset($code)) {
			if ($code >= 100 && $code < 200) {
				//Informational 1xx
				$status = "informational";
			} else if ($code >= 200 && $code < 300) {
				//Successful 2xx
				$status = "successful";
			} else if ($code >= 300 && $code < 400) {
				//Redirection 3xx
				$status = "redirect";
			} else if ($code >= 400 && $code < 500) {
				//Client Error 4xx
				$status = "client_error";
			} else if ($code >= 500 && $code < 600) {
				//Server Error 5xx
				$status = "server_error";
			}
			// urlhelper will return -1 for unregistered domains, i'll mark it 'unknown'.
		}

		return $status;
	}	

	
	//
	// HTTP URL query building function
	// This is being added to PHP. So use system function if it exists
	// 
	
	if (!function_exists('http_build_query')) {
	
		function http_build_query($formdata, $numeric_prefix = '') {
			return _http_build_query($formdata,$numeric_prefix);
		}
	
		function _http_build_query($formdata, $numeric_prefix = '',$key_prefix='') {
			if ($numeric_prefix != '' && !is_numeric($numeric_prefix)) {
				$prefix = $numeric_prefix;
			} else {
				$prefix = '';
			}
	
			if (!is_array($formdata)) return;
			$str = '';
			foreach ($formdata as $key => $val) {
				if (is_numeric($key)) $key = $prefix.$key;
				if ($str != '') $str.='&';
				if ($key_prefix != '') {
					$mykey = $key_prefix."[$key]";
				} else {
					$mykey = $key;
				}
				if (is_array($val)) {
					$str .= _http_build_query($val,'',$mykey);
				} else {
					$str .= $mykey.'='.urlencode($val);
				}
			}
			return $str;
		}
	}
	
?>