<?php
	//ob_start();
	require_once("../configs/config.php");
	require_once("../include/linkmonger.lib.php");

	$returnURL = getenv("HTTP_REFERER");
	$kCookieLifetime = time()+7776000;
	
	if (isset($_POST['usernameText'], $_POST['passwordText'])) {
		//db auth on a post

		if ($userInfoArray = $db->get_row("SELECT username, password, usergroup, valid FROM users WHERE username = '".$_POST['usernameText']."'", ARRAY_A)) {
			if (md5($_POST['passwordText']) == $userInfoArray['password']) {
				//password is valid, write a new cookie	and take them to the feed page
				//expire time is 1 month, 60sec * 60min * 24hrs * 30days *3 months = 7776000
				setcookie("who", $userInfoArray['username'], $kCookieLifetime, LM_COOKIE_PATH, LM_COOKIE_HOST);
				setcookie("group", $userInfoArray['usergroup'], $kCookieLifetime, LM_COOKIE_PATH, LM_COOKIE_HOST);

				header("Location: ".$returnURL);
				exit;
			} else {
				//password is not valid
				$error = "badpassword";
				header("Location: ../user.php?error=".$error);
			}	
		} else {
			// user is not found in the database
			$error = "baduser";
			header("Location: ../user.php?error=".$error);
		}
	

	} else if (isset($_GET['remove'])) {
		if (isset($_COOKIE["who"])) {
			setcookie("who", "", time() - 3600, LM_COOKIE_PATH, LM_COOKIE_HOST);
			setcookie("group", "", time() - 3600, LM_COOKIE_PATH, LM_COOKIE_HOST);
		}
		//header("Location: ".$returnURL);
	} else {
		$error = "incomplete";
		//header("Location: ../user.php?error=".$error);
	}

	//ob_end_flush();
?>