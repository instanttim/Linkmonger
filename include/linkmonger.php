<?php

// THIS FILE IS DEPRECATED AND WILL BE REMOVED!
// DO NOT USE FUNCTIONS IN THIS FILE!
//
// Currently in use by:
// proc_clump.php
// clumps.php

// rewrite notes:
//	recent uses: 
//		dbLinks
//		dbUsernames
//		dbLinkCount
//		pageChange
//		errorMsg

//
// Page Functions
//

function createSmarty() {
	require(SMARTY_INCLUDE);
	$smarty = new Smarty;
	$smarty->compile_check = TRUE;
	$smarty->debugging = FALSE;
	return $smarty;
}


//
// SQL Formation
//

function sql_build_linksWhere($submitter, $archived, $dead) {
	if ($submitter != NULL) {
		$whereclause = "WHERE submitter = '".$submitter."' AND archived = ".$archived." AND dead = ".$dead;
	} else {
		$whereclause = "WHERE archived = ".$archived." AND dead = ".$dead;
	}
	return $whereclause;
}

function sql_build_linksSearch($text, $submitter) {
	if ($text != NULL && $text != "") {
		$searchText = dbText($text);
		
		$searchArray = explode(" ", $searchText);
		$searchStr = "";
	
		for ($i=0; $i < count($searchArray); $i++) {
			$searchStr .= "(title like '%".$searchArray[$i]."%' OR description like '%".$searchArray[$i]."%')";
			if ($i+1 < count($searchArray)) {
				$searchStr .= " AND ";
			}
		}
	}
	
	if ($submitter != NULL && $submitter != "") {
		if (isset($searchStr)) {
			$searchStr .= " AND ";
		} else {
			$searchStr = "";
		}
		$searchStr .= "submitter = '".$submitter."'";
	}
	
	return $searchStr;
}

//
// Database Calls
//

// get the list of users for the filter popups
// remove the db arg - go global
/*
function dbUsernames($db) {
	$sql  = "SELECT username ";
	$sql .= "FROM users ";
	$sql .= "ORDER BY username ASC";
	
	return $db->get_results($sql, ARRAY_A);
}
*/

// get the total link count for the footer
// remove all args
function dbLinkCount($db, $whereclause = NULL) {
	$sql  = "SELECT count(*) ";
	$sql .= "FROM links ";
	$sql .= $whereclause;
	
	return $db->get_var($sql);
}

// semi old function
// remove entirely
function dbLinks($db, $whereclause = NULL, $orderby, $pageNum) {
	$sql  = "SELECT id, title, description, access_count, comment_count, submitter, submit_time ";
	$sql .= "FROM links ";
	$sql .= $whereclause." ";
	$sql .= "ORDER BY ".$orderby." DESC ";
	$sql .= "LIMIT ".LM_PAGELIMIT." OFFSET ".($pageNum * LM_PAGELIMIT);

	$result = $db->get_results($sql, ARRAY_A);
	return($result);
}

// remove entirely in favor of the dbLinks class
function dbLinkSearch($db, $searchclause) {
	$sql  = "SELECT id, title, description, access_count, comment_count, submitter, submit_time ";
	$sql .= "FROM links ";
	$sql .= "WHERE ".$searchclause." ";
	$sql .= "ORDER BY submit_time DESC";
	
	$result = $db->get_results($sql, ARRAY_A);
	return $result;
}

//
// Text Formatting
//

function dbText($text) {
	$dbtext = mysql_real_escape_string(utf8_decode($text));
	return $dbtext;
}

function dbValue($value) {
	if (is_string($value)) {
		$dbvalue = "'".mysql_real_escape_string(utf8_decode($value))."'";
	} else {
		$dbvalue = $value;
	}
	return $dbvalue;
}

function formatLinks ($links) {

	// 7 days... in seconds.
	$dayInSeconds = 60*60*24;
	$weekInSeconds = 60*60*24*7;
	
	//get each row from the result
	foreach ($links as $link) {		
		
		// format the title
		$title = $link['title'];
		$htmltitle = htmlentities($title,ENT_QUOTES,"UTF-8");
		$htmltitle = keyCap($htmltitle);
		$link['ss_title'] = $htmltitle;
		
		// format the description
		$text = $link['description'];
		$htmltext = htmlize($text);
		$htmltext = keyCap($htmltext);
		$link['ss_description'] = $htmltext;
		
		// do the date thing
		if (strtotime($link['submit_time']) > (strtotime(date("F j, Y")) + $dayInSeconds - $weekInSeconds)) {
			$timeSubmitted = date("D @ g:i a", strtotime($link['submit_time']));
		} else {
			$timeSubmitted = date("M j, Y", strtotime($link['submit_time']));
		}
		$link['time_submitted'] = $timeSubmitted;
		
		// add any special features based on who you are...
		if (isset($_COOKIE['who'], $_COOKIE['group'])) {
			if ($_COOKIE['who'] == $link['submitter'] || $_COOKIE['group'] == "admin") {
				$link['func_edit'] = TRUE;
			}
		}
		
		// add this row to the big ol' link array
		$f_links[] = $link;
	}
	return $f_links;
}

function formatClumps ($clumps, $currentID = NULL) {

	// 7 days... in seconds.
	$dayInSeconds = 60*60*24;
	$weekInSeconds = 60*60*24*7;
	
	//get each row from the result
	foreach ($clumps as $clump) {

		// format the title
		$title = $clump['title'];
		$htmltitle = htmlentities($title,ENT_QUOTES,"UTF-8");
		$htmltitle = keyCap($htmltitle);
		$clump['title'] = $htmltitle;
		
		// format the description
		$text = $clump['description'];
		$htmltext = htmlize($text);
		$htmltext = keyCap($htmltext);
		$clump['description'] = $htmltext;
		
		// do the date thing
		if (strtotime($clump['time']) > (strtotime(date("F j, Y")) + $dayInSeconds - $weekInSeconds)) {
			$timeCreated = date("D @ g:i a", strtotime($clump['time']));
		} else {
			$timeCreated = date("M j, Y", strtotime($clump['time']));
		}
		$clump['time_created'] = $timeCreated;
		
		// add any special features based on who you are...
		if (isset($_COOKIE['who'])) {
			if ($_COOKIE['who'] == $clump['creator']) {
				$clump['func_edit'] = TRUE;
			}
		}
		
		if ($clump['id'] == $currentID) {
			$clump['selected'] = 'selected="selected"';
		}
		
		// add this row to the big ol' clump array
		$f_clumps[] = $clump;
	}
	return $f_clumps;
}	


// OLD I SHOULD REMOVE THIS
//
function createCommentArray ($comments) {
	require_once(SIMPLESTYLE_INCLUDE);

	//get each row from the result
	foreach ($comments as $comment) {		

		// format the comment
		$text =& new SimpleStyle($comment['comment']);
		$text->simplify();
		$comment['comment'] = $text->getResult();;

		
		// do the date thing
		$dateSubmitted = date("M j, Y", strtotime($comment['time']));
		$timeSubmitted = date("g:i a", strtotime($comment['time']));
		$comment['date_submitted'] = $dateSubmitted;
		$comment['time_submitted'] = $timeSubmitted;
		
		$commentArray[] = $comment;
	}
	if (isset($commentArray)) {
		return $commentArray;
	} else {
		return FALSE;
	}
}




function validateClumpSubmission($formData) {
	
	// TITLE
	if ($formData['titleText'] == "") {
		$blankFields[] = "Title";
	} else {
		$validData['title'] = dbText($formData['titleText']);
	}
	
	// DESCRIPTION
	if ($formData['descriptionText'] == "") {
		$blankFields[] = "Description";
	} else {
		$validData['description'] = dbText($formData['descriptionText']);
	}

	// CREATOR
	if ($formData['creatorHidden'] == "") {
		$blankFields[] = "Created by";
	} else {
		$validData['creator'] = dbText($formData['creatorHidden']);
	}
	
	if (isset($blankFields)) {
		$validData['error'] =  "Your clump was not created because required fields were left blank.";
		$validData['blankfields'] = $blankFields;
	}
	
	return $validData;
}

function addClump ($db, $validData) {
	//close any open clumps for this user
	$db->query("UPDATE clumps SET open=FALSE where creator='".$validData['creator']."' AND open=TRUE");
					
	//construct the query
	$addition = "INSERT INTO clumps (";
	$addition .= "title, ";
	$addition .= "description, ";
	$addition .= "creator, ";
	$addition .= "time, ";
	$addition .= "open";
	$addition .= ") VALUES (";
	$addition .= "'".$validData['title']."', ";
	$addition .= "'".$validData['description']."', ";
	$addition .= "'".$validData['creator']."', ";
	$addition .= "now(), ";
	$addition .= "true";
	$addition .= ")";
	
	$return = $db->query($addition);
	
	//debug
	//print($addition."<br>\n");

	return $return;
}

function editClump ($database) {
}

function deleteClump ($db, $clump_id) {
	$removeClump = FALSE;
	
	// remove the associated links
	if ($db->query("SELECT link_id FROM clump_links WHERE clump_id=".$clump_id)) {
		if ($db->query("DELETE FROM clump_links WHERE clump_id=".$clump_id)) {
			$removeClump = TRUE;
		} else {
			echo '<p>There was an unknown error in the database operations. Could not remove associated links.</p>';
		}
	} else {
		$removeClump = TRUE;
	}
				
	if ($removeClump) {
		//remove the clump
		$return = $db->query("DELETE FROM clumps WHERE id=".$clump_id);

		if ($return == FALSE) {
			echo '<p>There was an unknown error in the database operations. Removed associated links but could not remove Clump.</p>';
		} else { 
			// everythign is okay
			header("Location: ".LM_URL."/clumps.php");
		}
	}
}

function addClumpLink ($db, $clump_id, $link_id, $who) {
	// construct the query
	$addition = "INSERT INTO clump_links (";
	$addition .= "clump_id, ";
	$addition .= "link_id";
	$addition .= ") VALUES (";
	$addition .= "'".$clump_id."', ";
	$addition .= "'".$link_id."'";
	$addition .= ")";
	
	//debug
	//print($addition."<br>\n");
	
	$return = $db->query($addition);

	if ($return == FALSE) {
		print("There was an unknown error in the database operations.");
	} else { 
		// everythign is okay

		//close any open clumps for this user and open this one.
		$db->query("UPDATE clumps SET open=FALSE WHERE creator='".$who."' AND open=TRUE AND id!=".$clump_id);
		$db->query("UPDATE clumps SET open=TRUE WHERE id=".$clump_id);
		
		// close the window, since it's always a popup now.
		//
		print('<html>');
		print('closing...');
		print('<body onLoad="window.close();"></body>');
		print('</html>');
	}
}

function removeClumpLink ($db, $clump_id, $link_id) {
	//remove the link
	$return = $db->query("DELETE FROM clump_links WHERE clump_id=".$clump_id." AND link_id=".$link_id);
	
	if ($return == FALSE) {
		echo '<p>There was an unknown error in the database operations.</p>';
	} else { 
		// everythign is okay
		header("Location: ".LM_URL."/clumps.php?action=edit&id=".$clump_id."");
	}
}

function pageChange($queryArray, $count, $page, $change) {	
	// set up the paging urls
	if ($change > 0 && $count > LM_PAGELIMIT && ($page + $change) * LM_PAGELIMIT < $count) {
			$queryArray['page'] = $page + $change;
			$queryStr = http_build_query($queryArray);
			return "?".$queryStr;
			
	} else if ($change < 0 && $page > 0) {
		$queryArray['page'] = $page - 1;
		$queryStr = http_build_query($queryArray);
		return "?".$queryStr;
	}

	return;
}

// This is being added to PHP. So use system function if it exists
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
	
function errorMsg($errorcode) {
	$messages = array(
		"nolinks" => "No links were found."
	);
	return $messages[$errorcode];
}

function htmlize($pretext) {
	
	// remove the extra crap
	$pretext = preg_replace("/\r/","", $pretext); // remove carriage returns
	$pretext = preg_replace("/(?<=\n)\s*\n/","\n", $pretext); // remove extra whitespace and duplicate empty lines

	// entify
	$pretext = htmlentities($pretext,ENT_QUOTES,"UTF-8");

	$exploded = explode("\n\n", $pretext);
	$htmltext = "";
	for($i=0; $i < count($exploded); $i++) {
		if (preg_match("/^\s*[-|#](.*?)/", $exploded[$i])) {
			// detect a list
			if (preg_match("/^\s*#/", $exploded[$i])) {
				$listTag = "ol";
			} else {
				$listTag = "ul";
			}
			$exploded[$i] = preg_replace("/^[-|#]\s*(.*?)$/m", "\t<li>$1</li>", $exploded[$i]); // add the tab and li tags on each line
			$exploded[$i] = preg_replace("/^(.*?)$/s", "<".$listTag.">\n$1\n</".$listTag.">\n", $exploded[$i]); // wrap with ul tags
		} else {
			// assume everything else is a paragraph
			$exploded[$i] = preg_replace("/^\s*(.*?)$/m", "\t$1<br/>", $exploded[$i]); // add the tab and break tag on each line

			// we don't want to wrap them in paragraph tags
			$exploded[$i] = preg_replace("/^(.*?)$/s", "<div class=\"itemdesc\">\n$1\n</div>\n", $exploded[$i]); // wrap with p tags
		}
		$htmltext .= $exploded[$i];
	}

	// images
	// with dimensions and without
	$htmltext = preg_replace("/\[(?:&quot;)?(.*?)(?:&quot;)?:?([0-9]+)x([0-9]+):?(http:\/\/.*?)\]/", "<img width=\"$2\" height=\"$3\" src=\"$4\" alt=\"$1\" />", $htmltext);
	$htmltext = preg_replace("/\[(?:&quot;)?(.*?)(?:&quot;)?:?(http:\/\/.*?)\]/", "<img src=\"$2\" alt=\"$1\" />", $htmltext);	

	// links
	// quoted named link, unquoted named link, unnamed link
	$htmltext = preg_replace("/&quot;(.*?)&quot;:(https?:\/\/[^\s\"]*)(\.\s|\s)/", "<a href=\"$2\">$1</a>$3", $htmltext);
	$htmltext = preg_replace("/([^\s]*):(https?:\/\/[^\s\"]*)(\.\s|\s)/", "<a href=\"$2\">$1</a>$3", $htmltext);
	$htmltext = preg_replace("/(https?:\/\/[^\s\"]*?)(\s|\.\s|<)/", "<a href=\"$1\"><img width=\"11\" height=\"11\" src=\"/images/link.gif\"></a>$2", $htmltext);
	
	// BUG: the styling checks don't work when the string is less than two chars!
	// valid string to be styled matches this rule
	$validStylePattern = "([^\s]|[^\s].*?[^\s])";

	// bold	
	$htmltext = preg_replace("/(?<!&)(#)".$validStylePattern."(#)/", "<b>$2</b>", $htmltext); // #
	//$htmltext = preg_replace("/(\*\*)".$validStylePattern."(\*\*)/", "<b>$2</b>", $htmltext); // **


	// italics
	$htmltext = preg_replace("/(\*)".$validStylePattern."(\*)/", "<i>$2</i>", $htmltext);
	// underline
	$htmltext = preg_replace("/(_)".$validStylePattern."(_)/", "<u>$2</u>", $htmltext);
	
	// acronyms
	$htmltext = preg_replace("/([A-Z]+)\((.*?)\)/", "<acronym title=\"$2\">$1</acronym>", $htmltext);

	return $htmltext;
}

function keyCap($htmltext) {

	// char replacements (tm) and (c)
	$htmltext = preg_replace("/\(tm\)/", "&trade;", $htmltext);
	$htmltext = preg_replace("/\(c\)/", "&copy;", $htmltext);

	// en dash (number ranges)
	$htmltext = preg_replace("/(\d)(-)(\d|\s)/", "$1&ndash;$3", $htmltext);
	
	// em dash
	$htmltext = preg_replace("/--/", "&mdash;", $htmltext);

	// elipsis
	$htmltext = preg_replace("/\.\.\./", "&hellip;", $htmltext);
	
	// double quotes
	$htmltext = preg_replace("/(&quot;)([^\d\s].*?[^\d\s])(&quot;)/", " &ldquo;$2&rdquo; ", $htmltext);
	
	// contractions
	$htmltext = preg_replace("/([^\d\s])(&#039;)([^\d\s])/", "$1&rsquo;$3", $htmltext);

	// single quotations 
	$htmltext = preg_replace("/(&#039;)([^\d\s][^&#039;]*?[^\d\s])(&#039;)/", "&lsquo;$2&rsquo;", $htmltext);

	// year abbreviations
	$htmltext = preg_replace("/(\s)(&#039;)(\w)/", "$1&rsquo;$3", $htmltext);
	
	return $htmltext;
}
?>