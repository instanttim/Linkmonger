<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
{include file="header.tpl"}
<body class="popup">
{if $error}
	<div class="error">There has been an error: {$error}</div>
{else}
	{if $clumpArray}
		Add link to:
		<form action="functions/proc_clump.php" method="post">
			<input type="hidden" name="action" value="addlink"/>
			<input type="hidden" name="link_id" value="{$link_id}"/>
			<select name="clump_id">
		{section name=row loop=$clumpArray}
			{if $clumpArray[row].open eq 1}
				<option value="{$clumpArray[row].id}" selected="selected">{$clumpArray[row].title}</option>
			{else}
				<option value="{$clumpArray[row].id}">{$clumpArray[row].title}</option>
			{/if}
		{/section}
				<!--
				<option label="--------" value="_invalid"/>
				<option label="Other..." value="_other"/>
				-->
			</select>
			<input type="submit" value="Add"/>
		</form>
	{/if}
{/if}
</body>
</html>
