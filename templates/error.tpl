<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
{include file="header.tpl"}
<body class="main">
{include file="masthead.tpl"}
	<div class="center">
		<div id="content">
			<h1>The following errors occurred:</h1>
			{section name=num loop=$errors}
			<span class="error">{$errors[num]}</span>
			{/section}
		</div>
	</div>
{include file="footer.tpl"}
</body>
</html>
