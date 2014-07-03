<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
{include file="header.tpl"}

<body class="main">
{include file="masthead.tpl"}
{if $error}
	<div class="error">There has been an error: {$error}</div>
{elseif $linkArray}
	{if $edit}
		{include file="link/link_edit.tpl"}
	{else}
		{include file="list/list_links.tpl"}
	{/if}
	{if $commentArray}
	<h1>Comments:</h1>
		{include file="link/link_comments.tpl"}
	{/if}
	{if $who}
	<h1>Add a comment:</h1>
	<form action="functions/proc_comment.php" method="post">
		<input name="action" type="hidden" value="add" />
		<input name="returnURL" type="hidden" value="{$returnURL}" />
		<input name="link_id" type="hidden" value="{$link_id}" />
		<input name="commenterText" type="hidden" value="{$who}" />
		<textarea name="commentText" rows="4" cols="60"></textarea><br/>
		<input type="submit" value="Comment" />(no HTML allowed)
	</form>
	{/if}
{/if}
{include file="footer.tpl"}
</body>
</html>
