<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
{include file="header.tpl"}
<body class="main">
{include file="masthead.tpl"}
	<div id="tools">
		<form action="clumps.php" method="get">
			<div style="float:right;">
				<input name="action" type="submit" value="Create Clump" />
			</div>
		</form>
		<form action="search.php" method="get" accept-charset="utf-8">
			<div><input type="text" name="searchText" size="20" /><input type="submit" value="Search" /></div>
		</form>
	</div>
{if $error}
	<div class="error">There has been an error: {$error}</div>
{else}
	<div id="content">
{include file="clumps/clump_list.tpl"}
	</div>
{/if}
{include file="footer.tpl"}
</body>
</html>
