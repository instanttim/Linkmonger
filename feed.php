<?php
	/**
	* Linkmonger Feed - the mechanics to feed links into linkmonger
	*
	* @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
	* @author     Timothy B Martin <tim@design1st.org>
	*/

	require_once("configs/config.php");
	require_once("include/linkmonger.lib.php");

	$smarty = createSmarty();

	// Page vars
	$smarty->assign("pageTitle", "Feed Linkmonger");
	$smarty->assign("navSelected", "feed");
	$smarty->assign("signin", true);

	$returnURL = getenv("HTTP_REFERER");
	$smarty->assign("returnURL", $returnURL);

	// User auth -- you need to be signed in to feed!
	$smarty->assign("signin", true);
	$smarty->assign("who", setWho($_COOKIE));

	if (!empty($db->captured_errors)) {
		$errors['db'][] = errorMsg("db_fail");		
	} else {

		// set the title and url if they were included in the request
		if (isset($_GET['title'])) {
			$smarty->assign("formTitle", $_GET['title']);
		}
		if (isset($_GET['url'])) {
			$smarty->assign("formURL", $_GET['url']);
		} else {
			$smarty->assign("formURL", "http://");
		}


		// get the clumps for this user
		$sql_uclumps  = "SELECT id, title, open ";
		$sql_uclumps .= "FROM clumps ";
		$sql_uclumps .= "WHERE creator='".setWho($_COOKIE)."' ";
		$sql_uclumps .= "ORDER BY title ASC";

		if ($uclumps = $db->get_results($sql_uclumps, ARRAY_A)) {
			$smarty->assign("userClumpArray", $uclumps);
			$smarty->assign("clumpsAvailable", TRUE);
		}
		
	$smarty->assign("linkCount", dbLinkCount($db));
	$smarty->assign("currentTime", date("F j, Y, g:i a"));

	}
	
	if (isset($errors)) $smarty->assign("errors", $errors);
	
	// figure out which template to use and use it!
	if (isset($_GET['popup'])) {
		$smarty->display("feed-popup.tpl");
	} else {	
		$smarty->display("feed.tpl");
	}

?>