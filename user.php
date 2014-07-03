<?php
	/**
	* Linkmonger User - a user detail page for registered linkmongerers
	*
	* @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
	* @author     Timothy B Martin <tim@design1st.org>
	*/

	require_once("configs/config.php");
	require_once("include/linkmonger.lib.php");
	
	$smarty = createSmarty();

	$smarty->assign("pageTitle", "Sign In to Linkmonger");
	$smarty->assign("navSelected", "none");
	
	if (isset($_GET['error'])) {
		$smarty->assign("error", $_GET['error']);
	}
		
	if (isset($_COOKIE['who'])) {
		//if a cookie already exists, define who
		$who = $_COOKIE['who'];
		$smarty->assign("who", $who);
	}
	
	$smarty->assign("linkCount", dbLinkCount($db));
	$smarty->assign("currentTime", date("F j, Y, g:i a"));
	
	$smarty->display("user.tpl");
?>
