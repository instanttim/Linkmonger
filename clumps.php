<?php
	/**
	* Linkmonger Clumps
	*
	// i'm not currently paging these
	//$sql_links .= "LIMIT ".LM_PAGELIMIT." OFFSET ".($_GET['page'] * LM_PAGELIMIT);

	* @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
	* @author     Timothy B Martin <tim@design1st.org>
	*/

	require_once("configs/config.php");
	require_once("include/linkmonger.lib.php");
	//require_once("include/linkmonger.php");

	$smarty = createSmarty();

	// Page vars
	$smarty->assign("pageTitle", "Clumpy Linkmonger");
	$smarty->assign("navSelected", "clumps");
	$smarty->assign("signin", TRUE);
	
	// User auth
	$smarty->assign("signin", true);
	$smarty->assign("who", setWho($_COOKIE));

	if (!empty($db->captured_errors)) {
		$errors['db'][] = errorMsg("db_fail");
		$template = "list.tpl";	
	} else {
		
		if (isset($_GET['action'])) {
	
			switch ($_GET['action']) {
	
				case "addlink":
					if (isset($_GET['id'])) {
						// let's look at all the clumps
				
						$sql_clumps  = "SELECT id, title, description, time, creator, open ";
						$sql_clumps .= "FROM clumps ";
						$sql_clumps .= "WHERE creator = '".$who."' ";
						$sql_clumps .= "ORDER BY title";
				
						if ($clumps = $db->get_results($sql_clumps, ARRAY_A)) {
							$f_clumps = formatClumps($clumps);
							$smarty->assign("clumpArray", $f_clumps);
						} else {
							$errorMsg = "A database connection couldn't be made.";
							$smarty->assign("error", $errorMsg);
						}
					
						$smarty->assign("link_id", $_GET['id']);
						$template = "clumps/addtoclump-popup.tpl";
					}
					break;
	
				case "view":
					if ($_GET['id'] == "_all") {
	
						$sql_clumps  = "SELECT id, title, description, time, creator ";
						$sql_clumps .= "FROM clumps ";
						$sql_clumps .= "ORDER BY title ASC";
					
						if ($clumps = $db->get_results($sql_clumps, ARRAY_A)) {
							$f_clumps = formatClumps($clumps);
							$smarty->assign("clumpArray", $f_clumps);
						} else {
							$errorMsg = "A database connection couldn't be made.";
							$smarty->assign("error", $errorMsg);
						}
	
					} else {
						// let's look at one clump in particular.
						$smarty->assign("clump_id", $_GET['id']);
	
						$sql_clumps  = "SELECT id, title, description, time, creator ";
						$sql_clumps .= "FROM clumps ";
						$sql_clumps .= "WHERE id=".$_GET['id'];
				
						if ($clumps = $db->get_results($sql_clumps, ARRAY_A)) {
							$f_clumps = formatClumps($clumps);
							$smarty->assign("clumpArray", $f_clumps);
							$smarty->assign("pageTitle", $f_clumps[0]['title']." Clump");
						} else {
							$errorMsg = "A database connection couldn't be made.";
							$smarty->assign("error", $errorMsg);
						}
				
						//search for the link and clump data
						$sql_links  = "SELECT link.id, link.title, link.description, link.access_count, link.comment_count, link.submitter, link.submit_time, clump.reason, clump.sortorder ";
						$sql_links .= "FROM clump_links clump, links link ";
						$sql_links .= "WHERE link.id=clump.link_id AND clump.clump_id=".$_GET['id']." ";
						$sql_links .= "ORDER BY upper(link.title) ASC";
	
						// i'm not currently paging these
						//$sql_links .= "LIMIT ".LM_PAGELIMIT." OFFSET ".($_GET['page'] * LM_PAGELIMIT);
									
						if ($links = $db->get_results($sql_links, ARRAY_A)) {
							$f_links = formatLinks($links);
							$smarty->assign("linkArray", $f_links);
						}
					}
	
					if (isset($_GET['popup'])) {
						$sql_uclumps  = "SELECT clump.id, clump.title, clump.description, clump.time AS time, clump.creator ";
						$sql_uclumps .= "FROM clumps clump ";
						$sql_uclumps .= "WHERE clump.creator='".$who."' ";
						$sql_uclumps .= "ORDER BY upper(clump.title) ASC";
					
						if ($uclumps = $db->get_results($sql_uclumps, ARRAY_A)) {
							$f_uclumps = formatClumps($uclumps, $_GET['id']);
							$smarty->assign("userClumpArray", $f_uclumps);
						} else {
							$errorMsg = "A database connection couldn't be made.";
							$smarty->assign("error", $errorMsg);
						}
	
						$template = "clumps/clumps-popup.tpl";
					} else {
						$template = "clumps/clump.tpl";
					}
	
					break;
	
				case "edit":
					if (isset($_GET['id'])) {
						// let's get one clump for editing.
						$smarty->assign("clump_id", $_GET['id']);
						$smarty->assign("tool_clumpremove", TRUE);
	
						$sql_clumps  = "SELECT clump.id, clump.title, clump.description, clump.time, clump.creator ";
						$sql_clumps .= "FROM clumps clump ";
						$sql_clumps .= "WHERE id=".$_GET['id'];
				
						if ($clumps = $db->get_results($sql_clumps, ARRAY_A)) {
							$f_clumps = formatClumps($clumps);
							$smarty->assign("clumpArray", $f_clumps);
							$smarty->assign("pageTitle", $f_clumps[0]['title']." Clump");
						} else {
							$errorMsg = "A database connection couldn't be made.";
							$smarty->assign("error", $errorMsg);
						}
				
						//search for the link and clump data
						$sql_links  = "SELECT link.id, link.title, link.description, link.access_count, link.comment_count, link.submitter, link.submit_time, clump.reason, clump.sortorder ";
						$sql_links .= "FROM clump_links clump, links link ";
						$sql_links .= "WHERE link.id=clump.link_id AND clump.clump_id=".$_GET['id']." ";
						$sql_links .= "ORDER BY upper(link.title) ASC";
	
						// i'm not currently paging these
						//$sql_links .= "LIMIT ".LM_PAGELIMIT." OFFSET ".($_GET['page'] * LM_PAGELIMIT);
									
						if ($links = $db->get_results($sql_links, ARRAY_A)) {
							$f_links = formatLinks($links);
							$smarty->assign("linkArray", $f_links);
						}
					
						$template = "clumps/editclump.tpl";
					}
					break;
	
				case "Create Clump":
					// create
					$smarty->assign("who", $who);
					$template = "clumps/createclump.tpl";		
					break;
	
			}
	
		} else {
			// let's look at all the clumps
			// we don't know what else to do, so show them all
		
			$sql  = "SELECT clump.id, clump.title, clump.description, clump.time, clump.creator ";
			$sql .= "FROM clumps clump ";
			$sql .= "ORDER BY time DESC";
		
			if ($clumps = $db->get_results($sql, ARRAY_A)) {
				$f_clumps = formatClumps($clumps);
				$smarty->assign("clumpArray", $f_clumps);
			} else {
				$errorMsg = "A database connection couldn't be made.";
				$smarty->assign("error", $errorMsg);
			}
	
			if (isset($_GET['popup'])) {
				$sql_uclumps  = "SELECT clump.id, clump.title, clump.description, clump.time, clump.creator ";
				$sql_uclumps .= "FROM clumps clump ";
				$sql_uclumps .= "WHERE clump.creator='".$who."' ";
				$sql_uclumps .= "ORDER BY upper(clump.title) ASC";
			
				if ($uclumps = $db->get_results($sql_uclumps, ARRAY_A)) {
					$f_uclumps = formatClumps($uclumps, $_GET['id']);
					$smarty->assign("userClumpArray", $f_uclumps);
				} else {
					$errorMsg = "A database connection couldn't be made.";
					$smarty->assign("error", $errorMsg);
				}
		
				$template = "clumps/clumps-popup.tpl";
			} else {
				$template = "clumps/clumps.tpl";
			}
		}
	
		$smarty->assign("linkCount", $db->get_var("SELECT count(*) FROM links"));
		$smarty->assign("currentTime", date("F j, Y, g:i a"));
	}
	
	if (isset($errors)) $smarty->assign("errors", $errors);
	$smarty->display($template);
?>
