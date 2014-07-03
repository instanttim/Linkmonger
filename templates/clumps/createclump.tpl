<html>
{include file="header.tpl"}
<body class="main">
{include file="masthead.tpl"}
	<div class="center">
		<div id="content">
			<p>
				<div style="text-align:right">
					<form action="search.php" method="get">
						<input type="text" name="searchText" size="20"><input type="submit" value="Search">
					</form>
				</div>
			</p>

			{if $error}
				<span class="error">{$error}</span>
			{/if}
			
			<form name="bookmark_form" action="functions/proc_clump.php" method="post">
				<table border="0">
					<tr>
						<td align="right">Title:</td>
						<td colspan="2"><input name="titleText" type="text" size="40" maxlength="64"></td>
					</tr>
					<tr>
				<td align="right" valign="top">Description:</td>
				<td colspan="2"><textarea name="descriptionText" rows="4" cols="35"></textarea></td>
			</tr>
			<tr>
				<td align="right">Created by:</td>
				<td colspan="2">
					<input name="creatorHidden" type="hidden" value="{$who}">
					{$who}
				</td>
			</tr>
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td colspan="2">
					<input name="action" type="hidden" value="add">
					<input name="submitButton" type="submit" value="Create Clump">
					<!-- <input type="button" value="Cancel" onClick="go.back();"> -->
			</tr>
		</table>
			</form>			

		</div>
	</div>

{include file="footer.tpl"}
</body>
</html>