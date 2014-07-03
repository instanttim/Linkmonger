<?php
	/**
	* Linkmonger Help
	*
	* @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
	* @author     Timothy B Martin <tim@design1st.org>
	*/

	require_once("configs/config.php");
	require_once("include/linkmonger.lib.php");
	
	$smarty = createSmarty();
	
	// Page vars
	$smarty->assign("pageTitle", "Help with Linkmonger");
	$smarty->assign("navSelected", "help");

	// User auth
	$smarty->assign("signin", true);
	$smarty->assign("who", setWho($_COOKIE));

	// this is completely lame to have all these hardcoded urls here. they need to be generated and escaped and entified.
	$smarty->assign("lmURL", LM_URL."recent.php");
	$smarty->assign("feedBookmarklet", "javascript:void(open('".LM_URL."feed.php?popup&amp;title='+escape(document.title)+'&amp;url='+escape(location.href),'lm_feed','toolbar=no,location=no,directories=no,status=no,menubar=no,width=362,height=310,scrollbars=no,resizable=no'));");
	$smarty->assign("searchBookmarklet", "javascript:Qr=prompt('Search%20Linkmonger%20for:',window.getSelection());if(Qr)location.href='".LM_URL."search.php?text='+escape(Qr);");
	$smarty->assign("clumpBookmarklet", "javascript:void(open('".LM_URL."clumps.php?id=_all&amp;popup','lm_mini','toolbar=no,location=no,directories=no,status=no,menubar=no,width=300,height=600,scrollbars=auto,resizable=no'));");
	$smarty->assign("searchSelectionBookmarklet", "javascript:if(window.getSelection())%20{%20location.href='".LM_URL."search.php?text='+escape(window.getSelection());%20}%20else%20{%20%20Qr=prompt('Search%20Linkmonger%20for:','');if(Qr)location.href='".LM_URL."search.php?text='+escape(Qr);%20%20}");
	$smarty->assign("searchSelectionWindowBookmarklet", "javascript:if(window.getSelection())%20{%20void(open('".LM_URL."search.php?text='+escape(window.getSelection()),'lm_feed',''));%20}%20else%20{%20%20Qr=prompt('Search%20Linkmonger%20for:','');if(Qr)void(open('".LM_URL."search.php?text='+escape(Qr),'lm_feed',''));%20%20}");

	if (!empty($db->captured_errors)) {
		$errors['db'][] = errorMsg("db_fail");		
	} else {	
		$smarty->assign("linkCount", dbLinkCount($db));
		$smarty->assign("currentTime", date("F j, Y, g:i a"));
	}
	
	if (isset($errors)) $smarty->assign("errors", $errors);
	$smarty->display("help.tpl");
?>
