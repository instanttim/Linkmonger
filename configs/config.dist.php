<?php
	/**
	* Linkmonger configuration
	*
	* @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
	* @author     Timothy B Martin <tim@design1st.org>
	*/

	//
	// PHP system configuration
	// 
	ini_set('display_errors','1');
	error_reporting(E_ALL);
	// If you get too many notices, you can exclude them
	// error_reporting(E_ALL ^ E_NOTICE);
	set_magic_quotes_runtime(0);

	//
	// important include files that linkmonger needs to operate
	//
	define("SMARTY_INCLUDE","/path/to/smarty/Smarty.class.php");
	define("FEEDCREATOR_INCLUDE","/path/to/feedcreator/feedcreator.class.php");
	define("EZSQL_CORE_INCLUDE","/path/to/ezsql/ez_sql_core.php");
	define("EZSQL_DB_INCLUDE","/path/to/ezsql/ez_sql_mysql.php");
	define("SIMPLESTYLE_INCLUDE","/path/to/simplestyle/SimpleStyle.class.php");

	//
	// REQUIRED linkmonger configuration
	//
	define("LM_HOST", "yourdomain.co.uk");		// the fully qualified host name (e.g. "yourdomain.com")
	define("LM_PATH","/");						// the absolute URL path to the linkmonger php files, starting and ending with "/" (unless it's the root)
	define("LM_URL","http://".LM_HOST.LM_PATH);	// the URL to the directory containing the linkmonger php files, as seen in a browser, ending with a "/"

	// Optional linkmonger configuration
	define("LM_TITLE","Linkmonger");
	define("LM_DESC","Your feeding ground for interesting links.");
	define("LM_LOGO","images/logo.png");
	define("LM_COOKIE_HOST", LM_HOST);
	define("LM_COOKIE_PATH", LM_PATH);

	// page vars
	define("LM_PAGELIMIT",20);

	// ezSQL configuration
	define("EZSQL_DB_USER","www");		// <-- SQL db user
	define("EZSQL_DB_PASSWORD","www");	// <-- SQL db password
	define("EZSQL_DB_NAME","linkmonger");	// <-- SQL db pname
	define("EZSQL_DB_HOST","");		// <-- SQL server host (may be empty or localhost)

	// Simple Style config
	define("SS_LINK_REPLACEMENT", '<img width="11" height="11" src="images/link.gif">');

?>
