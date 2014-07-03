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
	<div id="tools">
		<form method="get" action="{$self}">
			<div style="float: right;">
				Show:
				<select name="submitter" onchange="javascript:submit();">
					<option value="_all" selected="selected">Everyone</option>
					<optgroup label="Users">
						{section name=row loop=$submitters}
						{if $submitters[row].username eq $submitter}
						<option selected="selected">{$submitters[row].username}</option>
						{else}
						<option>{$submitters[row].username}</option>
						{/if}
						{/section}
					</optgroup>
				</select>
			</div>
		</form>
		<form action="search.php" method="get" accept-charset="utf-8">
			<div>
				<input type="text" name="text" size="20" />
				<input type="submit" value="Search" />
			</div>
		</form>
	</div>
	{include file="list/list_links.tpl"}
	{include file="footer.tpl"}
	{/if}
</body>
</html>
