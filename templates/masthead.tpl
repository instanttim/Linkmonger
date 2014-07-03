	<div id="logo"><a href="recent.php"><img src="images/linkmonger_logo.gif" width="226" height="73" alt="Linkmonger" /></a></div>
	<div id="nav">
		{strip}
		<a href="recent.php"><img src="images/nav/nav_recent{if $navSelected eq 'recent'}1{else}0{/if}.gif" width="52" height="16" alt="recent" /></a>
		<a href="popular.php"><img src="images/nav/nav_popular{if $navSelected eq 'popular'}1{else}0{/if}.gif" width="52" height="16" alt="popular" /></a>
		<a href="clumps.php"><img src="images/nav/nav_clumps{if $navSelected eq 'clumps'}1{else}0{/if}.gif" width="52" height="16" alt="clumps" /></a>
		<a href="search.php"><img src="images/nav/nav_search{if $navSelected eq 'search'}1{else}0{/if}.gif" width="52" height="16" alt="search" /></a>
		<a href="help.php"><img src="images/nav/nav_help{if $navSelected eq 'help'}1{else}0{/if}.gif" width="53" height="16" alt="help" /></a>
		<img src="images/spacer.gif" width="10" height="2" alt="" />
		<a href="feed.php"><img src="images/nav/nav_feed{if $navSelected eq 'feed'}1{else}0{/if}.gif" width="112" height="16" alt="feed linkmonger" /></a>
		{/strip}<br/>
		<a href="?links">Links</a> - <a href="?comments">Comments</a> - <a href="?dead">Deaths</a>
	</div>
{if $signin and !$errors.db}
	{if $who}
	<div id="signin">
		Hi, {$who}<br/><a href="functions/auth.php?remove"><img src="images/signout.gif" alt="sign out" width="38" height="13" /></a>
	</div>
	{else}
		<form id="auth" action="functions/auth.php" method="post" accept-charset="utf-8">
			<div id="signin">
			<label for="usernameText">Your Name:</label><input type="text" id="usernameText" name="usernameText" size="10" maxlength="32" />
			<label for="passwordText">Password:</label><input type="password" id="passwordText" name="passwordText" size="10" maxlength="32" />
			<input type="submit" name="submitButton" value="Sign In" />
			</div>
		</form>
	{/if}
{/if}
