<?php
	/**
	* Linkmonger Link - the detail view for a link
	*
	* @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
	* @author     Timothy B Martin <tim@design1st.org>
	*/

	require_once("configs/config.php");
	require_once("include/linkmonger.lib.php");
	require_once("include/Links.class.php");
	require_once("include/Comments.class.php");

	$smarty = createSmarty();
	
	// Page vars
	$smarty->assign("navSelected", "none");

	// User auth
	$smarty->assign("signin", true);
	$smarty->assign("who", setWho($_COOKIE));
	
	// Link functions
	$smarty->assign("tool_clumpadd", true);
	
	if (isset($_GET['edit'])) {
		$smarty->assign("edit", TRUE);
		$smarty->assign("returnURL", $_SERVER['HTTP_REFERER']);
	} else {
		$smarty->assign("returnURL", $_SERVER['REQUEST_URI']);
	}
	
	// if we actually have an ID
	if (isset($_GET['id'])) {
		$smarty->assign("link_id", $_GET['id']);
	
		// create the almighty links object!
		$links =& new Links($db);
		
		// add submitter to query and assign to template for preselected popup
		if (isset($_GET['submitter']) && $_GET['submitter'] != "_all") {
			$smarty->assign("submitter", $_GET['submitter']);
			$links->addWhere("submitter", $_GET['submitter']);
		}
		
		// add the default parts for this query
		$links->addWhere("id", $_GET['id']);
		
		if ($linkResult = $links->get()) {
			$smarty->assign("linkArray", $linkResult);
			$pageTitle = "&ldquo;".$linkResult[0]['title']."&rdquo; in Linkmonger";
			$smarty->assign("pageTitle", $pageTitle);
			
			$comments =& new Comments($db);
			$comments->setLink($_GET['id']);
			if ($commentResult = $comments->get()) {
				$smarty->assign("commentArray", $commentResult);
			}
		} else {
			$errors[] = errorMsg("badlinkid");
		}
	} else {
		$errors[] = errorMsg("missinglinkid");
	}

	$smarty->assign("linkCount", dbLinkCount($db));
	$smarty->assign("currentTime", date("F j, Y, g:i a"));

	if (isset($errors)) errorPage($smarty, $errors);
	$smarty->display("link.tpl");
?>