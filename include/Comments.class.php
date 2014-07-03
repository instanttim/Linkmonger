<?php
	/***********************************************************************
		Comments.class.php
		-------------------
		begin		:	unknown
		copyright	:	(C) 2005 Timothy B Martin
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

	class Comments
	{
		var $db;					// database
		var $link;					// link id
		var $order = "submit_time";	// default sorting column
		var $count = NULL;			// number of comments returned
		
		function Comments(&$db)
		{
			$this->db =& $db;
		}
		
		function setLink ($id) 
		{
			$this->link = $id;
		}
		
		function get ()
		{
			$sql =  "SELECT id, commenter, comment, time ";
			$sql .= "FROM comments ";
			$sql .= "WHERE link_id=".$this->link." ";
			$sql .= "ORDER BY time ASC";

			if ($result = $this->db->get_results($sql, ARRAY_A)) {
				$formatted_result = $this->format($result);
				return $formatted_result;
			} else {
				return;
			}
		}
		
		function format ($comments)
		{
			foreach ($comments as $comment)
			{
				// format the comment
				$text =& new SimpleStyle($comment['comment']);
				$text->simplify();
				$comment['comment'] = $text->getResult();
				
				// do the date thing
				$dateSubmitted = date("M j, Y", strtotime($comment['time']));
				$timeSubmitted = date("g:i a", strtotime($comment['time']));
				$comment['date_submitted'] = $dateSubmitted;
				$comment['time_submitted'] = $timeSubmitted;
				
				// add this comment to the comment array
				$f_comments[] = $comment;
			}
			
			return $f_comments;
		}
	}
?>