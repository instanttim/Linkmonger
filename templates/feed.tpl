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
	<div id="feedform">
		<h1>Feed Linkmonger</h1>
		<form name="linkForm" action="functions/proc_link.php" method="post" accept-charset="utf-8">
			<div class="lineitem">
				<label for="titleText">Title:</label><br/>
				<input name="titleText" type="text" value="{$formTitle}" />
			</div>
			<div class="lineitem">
				<label for="linkText">URL:</label><br/>
				<input name="linkText" type="text" value="{$formURL}" />
			</div>
			<div class="lineitem">
				<label for="descriptionText">Description of Link:</label><br/>
				<textarea name="descriptionText"></textarea>
			</div>
			<div class="lineitem">
				<input name="archivedCheck" type="checkbox" checked="checked" />
				<label for="archivedCheck">Display this link in the recent list</label>
			</div>
			{if $userClumpArray}
			<div class="lineitem">
				<input name="clumpCheck" type="checkbox" />
				<label for="clumpCheck">Add to clump:</label>
				<select name="clumpSelect">
					{section name=row loop=$userClumpArray}
					{if $userClumpArray[row].open eq 1}
					<option value="{$userClumpArray[row].id}" selected="selected">{$userClumpArray[row].title}</option>
					{else}
					<option value="{$userClumpArray[row].id}">{$userClumpArray[row].title}</option>
					{/if}
					{/section}
				</select>
			</div>
			{/if}
			<div class="buttonbar">
				<input name="submitterHidden" type="hidden" value="{$who}" />
				<input name="action" type="hidden" value="add" />
				<input type="button" class="button" value="Cancel" onClick="window.location='{$returnURL}'" />
				<input type="submit" class="button" value="Feed" />
			</div>
		</form>
	</div>
	{include file="footer.tpl"}
	{/if}
</body>
</html>
