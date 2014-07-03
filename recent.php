<?php
	/**
	* Linkmonger Recent - the most recent links feed to linkmonger
	*
	* @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
	* @author     Timothy B Martin <tim@design1st.org>
	*/

	require_once("configs/config.php");
	require_once("include/linkmonger.lib.php");
	require_once("include/Links.class.php");
	
	$smarty = createSmarty();
	
	// Page vars
	$smarty->assign("pageTitle", "Today&rsquo;s Linkmonger");
	$smarty->assign("pageRefresh", $_SERVER['REQUEST_URI']);
	$smarty->assign("navSelected", "recent");

	// User auth
	$smarty->assign("signin", true);
	$smarty->assign("who", setWho($_COOKIE));

	if (!empty($db->captured_errors)) {
		$errors['db'][] = errorMsg("db_fail");		
	} else {
		if (isset($_GET['comments'])) { print('comments!'); }
		
		// Get list of users for submitter filter
		$smarty->assign("submitters", dbUsernames($db));
	
		// Link functions
		$smarty->assign("tool_clumpadd", true);
	
		// create the almighty links object!
		$links =& new Links($db);
	
		// add submitter to query and assign to template for preselected popup
		if (isset($_GET['submitter']) && $_GET['submitter'] != "_all") {
			$smarty->assign("submitter", $_GET['submitter']);
			$links->addWhere("submitter", $_GET['submitter']);
		}
	
		// add the default parts for this query
		$links->addWhere("archived", 1);

		if (isset($_GET['dead'])) {
			$links->addWhere("dead", 1);
		}

		$links->setOrder("submit_time");
	
		// set the page
		$page = setPage($_GET);
	
		// get one page of links and assign page urls
		if ($pageOfLinks = $links->get($page)) {
			$smarty->assign("linkArray", $pageOfLinks);
			$smarty->assign("nextPageURL", pageQueryString($_GET, $links->getCount(), $page, 1));
			$smarty->assign("prevPageURL", pageQueryString($_GET, $links->getCount(), $page, -1));
		} else {
			$errors['link'][] = errorMsg("link_none");
		}
		
		$smarty->assign("linkCount", dbLinkCount($db));
		$smarty->assign("currentTime", date("F j, Y, g:i a"));
	}

	if (isset($errors)) $smarty->assign("errors", $errors);
	$smarty->display("list.tpl");
?>