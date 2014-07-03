<?php
	/**
	* Linkmonger Search
	*
	* @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
	* @author     Timothy B Martin <tim@design1st.org>
	*/

	require_once("configs/config.php");
	require_once("include/linkmonger.lib.php");
	require_once("include/Links.class.php");
	
	$smarty = createSmarty();

	// Page vars
	$smarty->assign("pageTitle", "Search Linkmonger");
	$smarty->assign("navSelected", "search");
	$smarty->assign("signin", true);
	
	// User auth
	$smarty->assign("signin", true);
	$smarty->assign("who", setWho($_COOKIE));

	if (!empty($db->captured_errors)) {
		$errors['db'][] = errorMsg("db_fail");		
	} else {
	
		// Link functions
		//
		$smarty->assign("tool_clumpadd", true);
	
		// create the almighty links object!
		$links =& new Links($db);
	
		// if text was submitted
		//
		if (isset($_GET['text'])) {
	
			// if a submitter was also specified
			//
			if (isset($_GET['submitter'])) {
				$links->addSearch($_GET['text'], $_GET['submitter']);
			} else {
				$links->addSearch($_GET['text']);
			}
		
			$smarty->assign("searchText", htmlentities($_GET['text'], ENT_QUOTES, "UTF-8"));
			$pageTitle = htmlentities($_GET['text'], ENT_QUOTES, "UTF-8")." in Linkmonger";	
			$smarty->assign("pageTitle", $pageTitle);

			// set the page
			$page = setPage($_GET);
		
			if ($pageOfLinks = $links->get($page)) {
				$smarty->assign("linkArray", $pageOfLinks);
				$smarty->assign("nextPageURL", pageQueryString($_GET, $links->getCount(), $page, 1));
				$smarty->assign("prevPageURL", pageQueryString($_GET, $links->getCount(), $page, -1));
			} else {
				$errors[] = errorMsg("nolinks");
			}
		}
		
		$smarty->assign("linkCount", dbLinkCount($db));
		$smarty->assign("currentTime", date("F j, Y, g:i a"));
	}
	
	if (isset($errors)) $smarty->assign("errors", $errors);
	$smarty->display("search.tpl");

?>
