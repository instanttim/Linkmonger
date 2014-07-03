<div id="content">
	<table class="links" width="400" border="0" cellspacing="1" cellpadding="4">
		<tr><th class="links" colspan="2">Link information</th></tr>
		<tr>
			<td colspan="2" class="info">
				<form name="linkForm" action="functions/proc_link.php" method="post" accept-charset="utf-8">
				<input name="action" type="hidden" value="edit">
				<input name="id" type="hidden" value="{$linkArray[0].id}">
				<input name="titleText" type="text" size="40" maxlength="64" value="{$linkArray[0].title}"><br/>
				<input name="linkText" type="text" size="40" value="{$linkArray[0].link}"><br/>
				<textarea name="descriptionText" rows="4" cols="35">{$linkArray[0].description}</textarea><br/>
				<input name="archivedCheck" type="checkbox" {if $linkArray[0].archived}checked="checked"{/if}/>Display this link in the recent list.<br/>
				<input name="deadCheck" type="checkbox" {if $linkArray[0].dead}checked="checked"{/if}/>This link is dead.
			</td>
		</tr>
		<tr>
			<td class="info">
				<input type="hidden" name="submitterHidden" value="{$linkArray[0].submitter}">
				<input type="hidden" name="returnURL" value="{$returnURL}">
				<input type="submit" value="Update">
				<input type="button" value="Cancel" onClick="window.location='{$returnURL}'">
				</form>
			</td>
			<td class="info">
				<form name="deleteForm" action="functions/proc_link.php" method="post" accept-charset="utf-8">
					<input name="action" type="hidden" value="delete">
					<input name="id" type="hidden" value="{$linkArray[0].id}">
					<input type="hidden" name="returnURL" value="{$returnURL}">
					<input type="submit" value="Delete">
				</form>
			</td>
		</tr>	
	</table>
</div>
