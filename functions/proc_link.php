<?php
ob_start();
require_once("../configs/config.php");
require_once("../include/linkmonger.lib.php");

// we need functions for:
// adding link (from form submit - POST)
// editing link (from form submit - POST)
// deleting link (from form submit - POST)
// loading link (url - GET)


if (isset($_GET['action'])) {
	if ($_GET['action'] == "load") {
		if (isset($_GET['id'])) {
		
			$result = $db->get_row("SELECT link, title, dead FROM links WHERE id=".$_GET['id'], ARRAY_A);

			//test that there's only one row, and that row is 0.				
			if ($result) {
				
				header("Location: ".$result['link']);

				// flush the buffers and don't wait until execution is complete
				echo str_pad('',4096)."\n";
				ob_flush();
				flush();
					
				$status = httpStatus($result['link']);
					
				//debug
				//echo 'Status Code: '.$status;
				//print('</pre>');
			
				if ($status == 'informational' || $status == 'successful' || $status == 'redirect') {

					//construct query to update the link stats	
					$update = "UPDATE links SET ";
					if ($result['dead'] == TRUE) {
							// self-healing dead links that aren't dead anymore.
							$update .= "dead = ";
							$update .= "FALSE, ";
					}
					$update .= "access_time = ";
					$update .= "now(), ";
					$update .= "access_count = ";
					$update .= "access_count + 1 ";
					$update .= "WHERE id = ".$_GET['id'];
				
					//debug
					//print($update."<br>\n");
					$db->query($update);
				
				} else if ($status == 'client_error' && $result['dead'] == FALSE) {

					//construct query to update the link stats	
					$update = "UPDATE links SET ";
					$update .= "dead = ";
					$update .= "TRUE ";
					$update .= "WHERE id = ".$_GET['id'];
			
					//debug
					//print($update."<br>\n");
					$db->query($update);
		
				}
		
				// i let 'server_error' fall on the floor, not dead, but not counted.

			} else {
				print('
					<html>
					<head>
						<title>Linkmonger Link Loader</title>
						<meta name="robots" CONTENT="NOINDEX,NOFOLLOW">
					</head>
					<body>
						Couldn\'t find the specified link.
					</body>
					</html>
				');
			}
		} else {
			print('
				<html>
				<head>
					<title>Linkmonger Link Loader</title>
					<meta name="robots" CONTENT="NOINDEX,NOFOLLOW">
				</head>
				<body>
					No link was specified.
				</body>
				</html>
			');
		}
	}
} else if (isset($_POST['action'])) {
	switch ($_POST['action']) {
		case "add":
			//validate the form data
			$validData = validateLinkSubmission($db, $_POST);
			
			if (!isset($validData['error'])) {
				//construct the query
				$addition = "INSERT into links (";
				$addition .= "title, ";
				$addition .= "link, ";
				$addition .= "description, ";
				$addition .= "submitter, ";
				$addition .= "submit_time, ";
				$addition .= "archived";
				$addition .= ") VALUES (";
				$addition .= "'".$validData['title']."', ";
				$addition .= "'".$validData['link']."', ";
				$addition .= "'".$validData['description']."', ";
				$addition .= "'".$validData['submitter']."', ";
				$addition .= "now(), ";				
				$addition .= $validData['archived'];
				$addition .= ")";

				$return = $db->query($addition);
				
				if ($return == FALSE) {
					print('There was an unknown error in the database operations.<br/>');
					$db->debug();
				} else {

					if (isset($_POST['clumpCheck'])) {
						$clump_id = $_POST['clumpSelect'];
						$link_id = mysql_insert_id();
						addClumpLink($db, $clump_id, $link_id, $_COOKIE['who']);
					}

					if (isset($_POST['popup'])) {
						print('<html>');
						print('closing...');
						print('<body onLoad="window.close();"></body>');
						print('</html>');
					} else {		
						header("Location: ".LM_URL);
					}
				}
						
				//debug
				//print($addition."<br>\n");

			} else {
				//if we don't add the link, we explain why...
				print($validData['error']);
			}
			break;
		case "edit":
			//validate the form data
			$validData = validateLinkSubmission($db, $_POST);
			
			if (!isset($validData['error'])) {
				$update = "UPDATE links SET ";
				$update .= "title = ";
				$update .= "'".$validData['title']."', ";
				$update .= "link = ";
				$update .= "'".$validData['link']."', ";
				$update .= "description = ";
				$update .= "'".$validData['description']."', ";
				$update .= "archived = ";
				$update .= $validData['archived'].", ";
				$update .= "dead = ";
				$update .= $validData['dead'].", ";
				$update .= "submitter = ";
				$update .= "'".$validData['submitter']."' ";
				$update .= "WHERE id = ".$_POST['id'];
			
				//DEBUG
				//print($update."<br>\n");
			
				$return = $db->query($update);
			
				if ($return == FALSE) {
					print("There was an unknown error in the database operations.");
				} else { 
					header("Location: ".$_POST['returnURL']);
				}
						
				//debug
				//print($addition."<br>\n");
			} else {
				//if we don't add the link, we explain why...
				print($validData['error']);
			}
			break;
		case "delete":
			$db->query("DELETE FROM links WHERE id=".$_POST['id']);
			// i should remove orphaneed comments here.
			header("Location: ".$_POST['returnURL']);
			break;
		default:
			print "something else";
	}
}

ob_end_flush();
?>