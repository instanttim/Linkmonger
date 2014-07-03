<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
{include file="header.tpl"}
<body class="main">
{include file="masthead.tpl"}
	<div id="tools">
		<form action="search.php" method="get" accept-charset="utf-8">
			<div><input type="text" name="searchText" size="20" /><input type="submit" value="Search" /></div>
		</form>
	</div>
{if $error}
	<div class="error">There has been an error: {$error}</div>
{else}
	<div id="content2">
{include file="clumps/clump_list-edit.tpl"}
	</div>
	<form name="deleteForm" action="functions/proc_clump.php" method="post">
		<input name="action" type="hidden" value="delete">
		<input name="clump_id" type="hidden" value="{$clump_id}">
		<input type="submit" value="Delete">
	</form>						
	<div id="content">
{include file="list/list_links.tpl"}
	</div>
{/if}
{include file="footer.tpl"}
</body>
</html>