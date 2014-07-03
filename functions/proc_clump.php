<?php
require_once("../configs/config.php");
require_once("../include/linkmonger.php");
require_once(EZSQL_INCLUDE);

// we need functions for:
// adding clump (from form submit)
// editing clump (from form submit)
// deleting clump (from form submit)
// adding link to clump (from form submit)
// subtracting link clump (from url)
// changing sort order up/down (from url)

if (isset($_POST['action'])) {
	// DEBUG
	//print('<pre>');print_r($_POST);print('</pre>');
	
	switch ($_POST['action']) {
		case "add":
			// add a new clump
			// validate the form data
			$validData = validateClumpSubmission($_POST);
			
			if (!isset($validData['error'])) {
				$return = addClump($db, $validData);
	
				if ($return == FALSE) {
					print("There was an unknown error in the database operations.");
				} else {	
					//print("Something else");
					header("Location: ".LM_URL."/clumps.php");
				}
			} else {
				//if we don't add the clump, we explain why...
				print($validData['error']);
			}
			break;
			
		case "edit":
			// edit a clump
			break;
			
		case "delete":
			if (isset($_POST['clump_id'])) {
				deleteClump($db, $_POST['clump_id']);
			} else {
				echo '<p>Malformed Request</p>';
			}
			break;
			
		case "addlink":
			if ($_POST['clump_id'] && $_POST['link_id'] && $_COOKIE['who']) {
				addClumpLink($db, $_POST['clump_id'], $_POST['link_id'], $_COOKIE['who']);				
			}
			break;
			
		default:
			echo "you submitted data (post) but i didn't know what to do with it.";
	}
} elseif (isset($_GET['action'])) {
	switch ($_GET['action']) {
		case "subtractlink":
			if (isset($_GET['clump_id']) && isset($_GET['link_id'])) {
				removeClumpLink($db, $_GET['clump_id'], $_GET['link_id']);
			} else {
				echo '<p>Malformed Request</p>';
			}
			break;
		case "sortorder":
			// we need a few things: direction, clump_id, link_id
			
			$clumpLinkResult = pg_exec($database, "SELECT sortorder FROM clump_links WHERE clump_id=".$_GET['clump_id']." AND link_id=".$_GET['link_id']);
	
			if (pg_numrows($clumpLinkResult) == 1) {
				$clumpLinkArray = pg_fetch_array($clumpLinkResult, 0, PGSQL_ASSOC);
				$currentOrder = $clumpLinkArray['sortorder'];
				
				if ($dir == "up") {
					$newOrder = $currentOrder - 1;		
				} else if ($dir == "down") {
					$newOrder = $currentOrder + 1;		
				}
				
				//construct the query
				$update = "UPDATE clumps_links ";			
				$update .= "SET sortorder=".$newOrder." ";
				$update .= "WHERE clump_id=".$clump_id." AND link_id=".$link_id;
				
				//print($update);
				$return = pg_exec($database, $update);
				
				if ($return == FALSE) {
					echo '<p>There was an unknown error in the database operations</p>.';
				} else { 
					// everythign is okay
					header("Location: ".LM_URL."clump.php?id=".$clump_id);
				}
			}
			break;
		default:
			echo "you submitted data (get) but i didn't know what to do with it.";
	}
}
?>