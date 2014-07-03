<html>
{include file="header.tpl"}
<body class="main">
{include file="masthead.tpl"}
	<div class="center">
		<div id="content">
{if $error}
			<span class="error">There has been an error: {$error}</span>
{else}
	
{/if}
			<h1>Sign In/Out</h1>
{if $who}
			<form action="functions/auth.php" method="get" accept-charset="utf-8">
				If you aren't {$who} then sign out and sign back in.
				<input type="hidden" name="remove" value="">
				<input type="submit" value="Sign Out">
			</form>
			<h1>User Information</h1>
			This is where other exciting information about our users go. Such things as 
			email, password changing, a nice description about yourself, maybe a picture!
{else}
			<form action="functions/auth.php" method="post" accept-charset="utf-8">
				Your Name:<input type="text" name="usernameText" size="20" maxlength="32">
				Password:<input type="password" name="passwordText" size="20" maxlength="32">
				<input type="submit" name="submitButton" value="Sign In">
			</form>
{/if}
		</div>
	</div>

{include file="footer.tpl"}
</body>
</html>
