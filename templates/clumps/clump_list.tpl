{if $clumpArray}
	<div class="linklist">
		{if $nextPageURL or $prevPageURL}
		<ul class="navigation">
			{if $nextPageURL}<li class="right"><a href="{$nextPageURL}"><img src="images/page_next.gif" alt="Next Page" width="58" height="16" /></a></li>{/if}
			{if $prevPageURL}<li class="left"><a href="{$prevPageURL}"><img src="images/page_prev.gif" alt="Prev Page" width="59" height="16" /></a></li>{/if}
		</ul>
		{/if}
		<div class="itemhiddendivider"></div>
	{section name=row loop=$clumpArray}
		<div class="listitem">
			<span class="info">
				{$clumpArray[row].creator}<br/>
				{$clumpArray[row].time_created}<br/>
				<a href='clump-rss.php?id={$clumpArray[row].id}'><img src="images/rss.png" alt="rss feed" width="29" height="15" /></a>
				{if $clumpArray[row].func_edit}<a class="link" href="clumps.php?action=edit&amp;id={$clumpArray[row].id}"><img class="tools" src="images/func_edit.gif" width="12" height="12" alt="Edit Clump"></a>{/if}
			</span>
			<span class="title">
				<a class="link" href="clumps.php?action=view&amp;id={$clumpArray[row].id}">{$clumpArray[row].title}</a>
				<a class="link" href="clumps.php?action=view&amp;id={$clumpArray[row].id}&amp;popup" onclick="popupWindow('lm_mini', this.href, 300, 600, 'yes', 'yes'); return false;" onkeypress="popupWindow('lm_mini', this.href, 300, 600, 'yes', 'yes'); return false;"><img height="11" width="11" src="images/minwindow.gif" alt="open in a mini window" /></a>
			</span>
			<span class="stats">

			</span>
			{$clumpArray[row].description}
		</div>
		{if %row.rownum% != count($clumpArray)}<div class="itemhiddendivider"></div><div class="itemdivider"></div>{/if}
	{/section}
		<div class="itemhiddendivider"></div>
		{if $nextPageURL or $prevPageURL}
		<ul class="navigation">
			{if $nextPageURL}<li class="right"><a href="{$nextPageURL}"><img src="images/page_next.gif" alt="Next Page" width="58" height="16" /></a></li>{/if}
			{if $prevPageURL}<li class="left"><a href="{$prevPageURL}"><img src="images/page_prev.gif" alt="Prev Page" width="59" height="16" /></a></li>{/if}
		</ul>
		{/if}
	</div>
{elseif $noResults}
	{$noResults}
{/if}