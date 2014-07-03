<?php
/**
* Linkmonger RSS - syndicated feed for the most recent links
*
* @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
* @author     Timothy B Martin <tim@design1st.org>
*/

	require_once("configs/config.php");
	require_once("include/linkmonger.lib.php");
	require_once(FEEDCREATOR_INCLUDE); 
	
	// turn off error reporting for rss
	error_reporting(0);
	
	// 7 days... in seconds.
	$dayInSeconds = 60*60*24;
	$weekInSeconds = 60*60*24*7;
	$timeZone = date("Z")/60/60;
	
	$sqlTime = date("Y-m-d H:i:s",time()+$dayInSeconds-$weekInSeconds).$timeZone;

	$sql  = "SELECT id, title, description, access_count, comment_count, submitter, submit_time";
	$sql .= " FROM links";
	$sql .= " WHERE dead = 0 AND archived = 1 AND submit_time > '".$sqlTime."' ";
	$sql .= " ORDER BY submit_time DESC";

	if ($linklist = $db->get_results($sql, ARRAY_A)) {
		$rss = new UniversalFeedCreator(); 
		$rss->useCached(); 
		$rss->title = LM_TITLE; 
		$rss->description = LM_DESC; 
		$rss->link = LM_URL; 
		$rss->syndicationURL = LM_URL.end(explode("/",$_SERVER["PHP_SELF"]));
		
		$image = new FeedImage(); 
		$image->title = "Linkmonger.net logo"; 
		$image->url = LM_URL.LM_LOGO; 
		$image->link = LM_URL; 
		$image->description = "Feed provided Linkmonger.net"; 
		$rss->image = $image;

		for ($i=0; $i < count($linklist); $i++) {
			// albums
			$item = new FeedItem();
			$item->title = utf8_decode($linklist[$i]['title']);
			$item->link = LM_URL."functions/proc_link.php?action=load&id=".$linklist[$i]['id'];
			$item->description = $linklist[$i]['description'];
			
			// comments link
			$item->comments = LM_URL."link.php?id=".$linklist[$i]['id'];
			
			// this really should be contributor
			$item->author = $linklist[$i]['submitter'];
			
			// until i can make the time better formatted.
			$item->date = strtotime($linklist[$i]['submit_time']);
			$rss->addItem($item);
		}

		// valid format strings are: RSS0.91, RSS1.0, RSS2.0, PIE0.1, MBOX, OPML
		print($rss->saveFeed("RSS2.0", "index.rdf"));	
	
	} else {
		header("HTTP/1.0 500 Internal Server Error");
		print('HTTP/1.0 500 Internal Server Error');
	}
?>