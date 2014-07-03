<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
{include file="header.tpl"}
<body class="main">
	{include file="masthead.tpl"}
	<div class="center">
		<div id="content">
			<h1>Help</h1>
			<p>
				Here are some links you can drag into your favorites to get quick and easy access to Linkmonger.
			</p>
			<p>
				<strong>Note:</strong> Don't bookmark these links by clicking on them and then adding the resulting page -- be sure to copy the link (right-click) and paste it into your favorite or simply drag and drop them.
			</p>
			<p>Linkmonger Bookmark:</p>
				<ul>
				<li><a href="{$lmURL}">Today's Linkmonger</a></li>
			</ul>
			<p>Linkmonger Bookmarklets:</p>
			<ul>
				<li><a href="{$feedBookmarklet}">Feed Linkmonger&hellip;</a></li>
				<li><a href="{$searchBookmarklet}">Search Linkmonger&hellip;</a></li>
				<li><a href="{$clumpBookmarklet}">Clumpy Linkmonger</a> (mini-window)</li>
			</ul>
			<p>Here are some new ones that use selected text to do searches:</p>
			<ul>
				<li>In the current window: <a href="{$searchSelectionBookmarklet}">Search Linkmonger&hellip;</a></li>
				<li>In a new window: <a href="{$searchSelectionWindowBookmarklet}">Search Linkmonger&hellip;</a></li>
			</ul>
		</div>
	</div>
{include file="footer.tpl"}
</body>
</html>
