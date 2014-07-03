<?php
	/***********************************************************************
		Links.class.php
		-------------------
		begin		:	unknown
		copyright	:	(C) 2006 Timothy B Martin
		email		:	tim@design1st.org
		authors		:	Timothy B Martin
		  
	***********************************************************************
	*
	*   This program is free software; you can redistribute it and/or 
	*   modify it under the terms of the GNU General Public License as 
	*   published by the Free Software Foundation; either version 2 of the 
	*   License, or (at your option) any later version.
	*
	***********************************************************************/
	require_once(SIMPLESTYLE_INCLUDE);

	class Links
	{
		var $db;					// database
		var $where = array();		// the where clause as name:value pairs
		var $order = "submit_time";	// the column to sort by
		var $count = NULL;			// number of links
		var $search;				// complex search query
		
		function Links(&$db)
		{
			$this->db =& $db;
		}

		function test()
		{
			print('<pre>');
			print_r($this);
			print('</pre>');
			$this->get();
			$this->db->debug();
		}
		
		function addWhere($item, $value)
		{
				$this->where[$item] = $value;
		}
		
		function addSearch($text, $submitter = NULL) {
			$searchText = $text;
			$searchArray = explode(" ", $searchText);
			$searchStr = "";
		
			for ($i=0; $i < count($searchArray); $i++) {
				$searchStr .= "(title like '".$this->db->escape("%".$searchArray[$i]."%")."' OR description like '".$this->db->escape("%".$searchArray[$i]."%")."')";
				if ($i+1 < count($searchArray)) {
					$searchStr .= " AND ";
				}
			}

			if ($submitter != NULL && $submitter != "") {
				if (isset($searchStr)) {
					$searchStr .= " AND ";
				} else {
					$searchStr = "";
				}
				$searchStr .= "submitter = '".$submitter."'";
			}
			
			$this->search = $searchStr;
		}
		
		function setOrder($order)
		{
				$this->order = $order;
		}
			
		function get($page = 0)
		{
			
			// build the where string
			//
			$whereStr = "";
			if (isset($this->search)) {
				$whereStr = $this->search;
			} else {	
				reset($this->where);
				for ($i=0; $i < count($this->where); $i++)
				{
					$pair = each($this->where);
					$whereStr .= $pair['key']." = '".$this->db->escape($pair['value'])."' ";
					if ($i < count($this->where) - 1)
					{
						$whereStr .= " AND ";
					}
				}
			}
			
			// count the results
			//
			unset($sql);
			$sql  = "SELECT count(*)";
			$sql .= " FROM links ";
			$sql .= " WHERE ".$whereStr;		
			$this->count = $this->db->get_var($sql);
			
			// get the results
			//
			unset($sql);
			$sql  = "SELECT id, link, title, description, access_count, comment_count, submitter, submit_time, archived, dead";
			$sql .= " FROM links";
			$sql .= " WHERE ".$whereStr;
			$sql .= " ORDER BY ".$this->order." DESC";
			$sql .= " LIMIT ".LM_PAGELIMIT." OFFSET ".($page * LM_PAGELIMIT);
			
			if ($result = $this->db->get_results($sql, ARRAY_A))
			{
				$formatted_result = $this->format($result);
				return $formatted_result;
			} else {
				return;
			}
		}
		
		function getCount()
		{
			return $this->count;
		}
		
		function format ($links)
		{	
			// 7 days... in seconds.
			$dayInSeconds = 60*60*24;
			$weekInSeconds = 60*60*24*7;
			
			//get each row from the result
			foreach ($links as $link)
			{	
				// format the title
				$title =& new SimpleStyle($link['title']);
				$title->sanitize();
				$title->specialize();
				$link['ss_title'] = $title->getResult();
				// for forms and normal html
				$link['title'] = htmlentities($link['title']);

				// format the description
				$description =& new SimpleStyle($link['description']);
				$description->simplify();
				$link['ss_description'] = $description->getResult();
				// for forms and normal html				
				$link['description'] = htmlentities($link['description']);

				
				// do the date thing
				if (strtotime($link['submit_time']) > (strtotime(date("F j, Y")) + $dayInSeconds - $weekInSeconds))
				{
					$timeSubmitted = date("D @ g:i a", strtotime($link['submit_time']));
				} else {
					$timeSubmitted = date("M j, Y", strtotime($link['submit_time']));
				}
				$link['time_submitted'] = $timeSubmitted;
				
				// add any special features based on who you are...
				// this should be changed in favor of an info icon which leads to link info
				if (isset($_COOKIE['who']))
				{
					if ($_COOKIE['who'] == $link['submitter'])
					{
						$link['func_edit'] = TRUE;
					}
				}
				
				// media type
				switch (end(explode(".", $link['link']))) 
				{
					case "html":
					case "htm":
					case "php":
					case "asp":
						// web page
						$link['mediatype'] = "page";
						break;
						
					case "jpg":
					case "jpeg":
					case "gif":
					case "png":
						// image
						$link['mediatype'] = "image";
						break;
						
					case "mov":
					case "qt":
					case "mpeg":
					case "mpg":
					case "mp4":
					case "qtvr":
					case "avi":
					case "wmv":
					case "swf":
						// video
						$link['mediatype'] = "video";
						break;
						
					case "mp3":
					case "wav":
					case "aiff":
						// audio
						$link['mediatype'] = "audio";						
						break;	
					
					case "com":
					case "com/":
					case "net":
					case "net/":
					case "org":
					case "org/":
					case "edu":
					case "edu/":
						// domain
						$link['mediatype'] = "domain";
						break;	
					
					case "qtvr":
						// vr
						$link['mediatype'] = "QTVR";
						break;
						
					case "swf":
						// shockwave
						$link['mediatype'] = "shockwave";						
						break;
						
					default:
						$link['mediatype'] = "blank";
						break;
				}
				
				if (isset($_COOKIE['group']))
				{
					if ($_COOKIE['group'] == "admin")
					{
						$link['func_edit'] = TRUE;
					}
				}
				
				// add this link to the big ol' link array
				$f_links[] = $link;
			}
			return $f_links;
		}
	}
?>