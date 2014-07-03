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
				{$clumpArray[row].creator}
			</span>
			<span class="title">
				<a class="link" href="clumps.php?action=view&amp;id={$clumpArray[row].id}&amp;popup">{$clumpArray[row].title}</a>
				<a class="link" href="clumps.php?action=view&amp;id={$clumpArray[row].id}" onclick="window.open(this.href, 'lm_target'); return false;" onkeypress="window.open(this.href, 'lm_target'); return false;"><img height="11" width="11" src="images/newwindow.gif" alt="open in a new window" /></a>
			</span>
			<span class="stats">
				(##)
			</span>
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