<div id="content">
	<div class="linklist">
		<div class="itemhiddendivider"></div>
		{section name=row loop=$commentArray}
		<div class="listitem">
				<span class="info">
					{$commentArray[row].date_submitted}<br/>
					{$commentArray[row].time_submitted}<br/>
					{if $who == $commentArray[row].commenter}
					<form action="functions/proc_comment.php" method="post" style="display: inline;">
						<input name="action" type="hidden" value="delete" />
						<input name="link_id" type="hidden" value="{$link_id}" />
						<input name="id" type="hidden" value="{$commentArray[row].id}" />
						<input name="commenter" type="hidden" value="{$commentArray[row].commenter}" />
						<input name="time" type="hidden" value="{$commentArray[row].time}" />
						<input name="returnURL" type="hidden" value="{$returnURL}" />
						<input type="image" src="images/func_delete.gif" />
					</form>
					{/if}
				</span>
			<b>{$commentArray[row].commenter} said&hellip;</b> {$commentArray[row].comment}
		</div>
		{if %row.rownum% != count($commentArray)}<div class="itemhiddendivider"></div><div class="itemdivider"></div>{/if}
		{/section}
		<div class="itemhiddendivider"></div>
	</div>
</div>