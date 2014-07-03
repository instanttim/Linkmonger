<div id="content">
	<div class="linklist">
		{if $errors.link}
		<div class="error">
			{section name="num" loop=$errors.link}
			<div class="listitem">{$errors.link[num]}</div>
			{/section}
		</div>
		{else}
		{if $nextPageURL or $prevPageURL}
		<ul class="navigation">
			{if $nextPageURL}<li class="right"><a href="{$nextPageURL}"><img src="images/page_next.gif" alt="Next Page" width="58" height="16" /></a></li>{/if}
			{if $prevPageURL}<li class="left"><a href="{$prevPageURL}"><img src="images/page_prev.gif" alt="Prev Page" width="59" height="16" /></a></li>{/if}
		</ul>
		{/if}
		<div class="itemhiddendivider"></div>
		{section name=row loop=$linkArray}
		<div class="listitem">
			<span class="info">
				{$linkArray[row].submitter}<br/>
				{$linkArray[row].time_submitted}<br/>
				{if $linkArray[row].submitter eq $who}<a href="link.php?id={$linkArray[row].id}&edit"><img class="tools" src="images/func_edit.gif" width="12" height="12" alt="Edit Link Info" /></a>{/if}
				{if $tool_clumpadd}<a href="clumps.php?action=addlink&amp;id={$linkArray[row].id}" onclick="popupWindow('_new', this.href, 300, 50, 'yes', 'yes'); return false;" onkeypress="popupWindow('_new', this.href, 300, 50, 'yes', 'yes'); return false;"><img class="tools" src="images/func_add.gif" width="12" height="12" alt="Add to Clump"/></a>{/if}
				{if $tool_clumpremove}<a href="functions/proc_clump.php?action=subtractlink&amp;clump_id={$clump_id}&amp;link_id={$linkArray[row].id}"><img class="funcIcon" src="images/func_subtract.gif" width="12" height="12" border="0"></a>{/if}
			</span>
			<span class="title">
				{if $linkArray[row].dead eq 1}<img src="images/status_dead.gif" width="12" height="12" alt="Dead Link" title="Dead Link" />{/if}
				<a class="link" href="functions/proc_link.php?action=load&amp;id={$linkArray[row].id}">{$linkArray[row].ss_title}</a>
				{if $linkArray[row].mediatype != "blank" && $linkArray[row].mediatype != "domain" && $linkArray[row].mediatype != "page"}[{$linkArray[row].mediatype}]{/if}
				<a class="link" href="functions/proc_link.php?action=load&amp;id={$linkArray[row].id}" onclick="window.open(this.href, 'lm_link'); return false;" onkeypress="window.open(this.href, 'lm_link'); return false;"><img height="11" width="11" src="images/newwindow.gif" alt="open in a new window" /></a>
			</span>
			<span class="stats">
				({$linkArray[row].access_count} clicks; <a href="link.php?id={$linkArray[row].id}">{$linkArray[row].comment_count} comments</a>)
			</span><br/>
			{$linkArray[row].ss_description}
		</div>
			{if %row.rownum% != count($linkArray)}<div class="itemhiddendivider"></div><div class="itemdivider"></div>{/if}
		{/section}
		<div class="itemhiddendivider"></div>
		{if $nextPageURL or $prevPageURL}
		<ul class="navigation">
			{if $nextPageURL}<li class="right"><a href="{$nextPageURL}"><img src="images/page_next.gif" alt="Next Page" width="58" height="16" /></a></li>{/if}
			{if $prevPageURL}<li class="left"><a href="{$prevPageURL}"><img src="images/page_prev.gif" alt="Prev Page" width="59" height="16" /></a></li>{/if}
		</ul>
		{/if}
		{/if}
	</div>
</div>
