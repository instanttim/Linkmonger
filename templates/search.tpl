<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
{include file="header.tpl"}
<body class="main">
{include file="masthead.tpl"}
	{if $errors.db}
	<div class="error">
		<h2>Database Error!</h2>
		{section name="num" loop=$errors.db}
		<p>{$errors.db[num]}</p>
		{/section}
	</div>
	{else}
	<div id="content2">
		<div>
			<form action="search.php" method="get" accept-charset="utf-8">
				<table border="0">
					<tr>
						<td align="right"><label for="searchtext">Search for:</label></td>
						<td><input id="searchtext" type="text" name="text" size="20" value="{$searchText}" /></td>
					</tr>
					<tr>
						<td align="right"><label for="submittedby">Submitted by:</label></td>
						<td><input id="submittedby" type="text" name="submitter" size="20" value="{$submittedBy}" /></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td align="right"><input type="submit" value="Search" /></td>
					</tr>
				</table>
			</form>
		</div>
	</div>
	<div>&nbsp;</div>
	{include file="list/list_links.tpl"}
	{include file="footer.tpl"}
	{/if}
</body>
</html>