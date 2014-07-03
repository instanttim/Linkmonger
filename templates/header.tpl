{config_load file="template.conf" section="header"}
<head>
	<title>{$pageTitle|default:"Untitled"}</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="description" content="What's a link or two among friends?" />
	<meta name="keywords" content="linkmonger,links,sharing,articles,humor,funny,photos,videos" />
{if $pageRefresh}
	<meta http-equiv="refresh" content="300;url={$pageRefresh}" />
{/if}
	<link rel="Icon" href="images/icon.png" type="images/png" />
	<link rel="apple-touch-icon" href="images/logo-apple-touch.png"/>
	<link rel="Stylesheet" href="{$stylesheet|default:#tpl_stylesheet#}" type="text/css" />
	<link rel="alternate" type="application/rss+xml" title="Recent Linkmonger" href="rss.php" />
	<script type="text/javascript" src="include/linkmonger.js"></script>
</head>
