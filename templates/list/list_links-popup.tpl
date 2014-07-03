{if $linkArray}
	<div class="linklist">
		{if $nextPageURL or $prevPageURL}
		<ul class="navigation">
			{if $nextPageURL}<li class="right"><a href="{$nextPageURL}"><img src="images/page_next.gif" alt="Next Page" width="58" height="16" /></a></li>{/if}
			{if $prevPageURL}<li class="left"><a href="{$prevPageURL}"><img src="images/page_prev.gif" alt="Prev Page" width="59" height="16" /></a></li>{/if}
		</ul>
		{/if}
		<div class="itemhiddendivider"></div>
		{section name=row loop=$linkArray}
		<div class="listitem">
			<span class="itemtitle">
				<a class="link" href="functions/proc_link.php?action=load&amp;id={$linkArray[row].id}" onclick="window.open(this.href, 'lm_target'); return false;" onkeypress="window.open(this.href, 'lm_target'); return false;">{$linkArray[row].title}</a>
			</span>
		</div>
		{/section}
		<div class="itemhiddendivider"></div>
		{if $nextPageURL or $prevPageURL}
		<ul class="navigation">
			{if $nextPageURL}<li class="right"><a href="{$nextPageURL}"><img src="images/page_next.gif" alt="Next Page" width="58" height="16" /></a></li>{/if}
			{if $prevPageURL}<li class="left"><a href="{$prevPageURL}"><img src="images/page_prev.gif" alt="Prev Page" width="59" height="16" /></a></li>{/if}
		</ul>
		{/if}
	</div>
{elseif $noLinksMsg}
	{$noLinksMsg}
{/if}
