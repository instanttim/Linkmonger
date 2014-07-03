<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
{include file="header.tpl"}
<body class="popup">
<!--
	<div id="tools">
		<form action="search.php" method="get" accept-charset="utf-8">
			<div><input type="text" name="searchText" size="20" /><input type="submit" value="Search" /></div>
		</form>
	</div>
-->
{if $error}
	<div class="error">There has been an error: {$error}</div>
{else}
	<form action="clumps.php" method="get">
		<div>
			<input name="action" type="hidden" value="view" />
			Clump:
			<select name="id" onchange="javascript:submit();">
				<option value="_all">All Clumps</option>
				<optgroup label="My Clumps">
					{section name=row loop=$userClumpArray}	
					<option value="{$userClumpArray[row].id}" {$userClumpArray[row].selected}>
						{$userClumpArray[row].title}
					</option>
					{/section}
				</optgroup>
			</select>
			<input type="hidden" name="popup" />
		</div>
	</form>

	<div id="content2">
{include file="clumps/clump_list-popup.tpl"}
	</div>
	&nbsp;
{if $linkArray}
	<div id="content">
{include file="list/list_links-popup.tpl"}
	</div>
{/if}	
{/if}
</body>
</html>