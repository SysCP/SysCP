	<table cellpadding="5" cellspacing="0" border="0" align="center" class="maintable">
		<tr>
			<td colspan="6" class="maintitle">
				<img src="{$imagedir}title.gif" alt="" />&nbsp;{l10n get=SysCP.domains.domains}
			</td>
		</tr>
		{if ($User.domains_used < $User.domains || $User.domains == '-1') && 15 < $User.domains_used}
		<tr>
			<td class="field_display_border_left" colspan="6">
				<a href="{url module=domains action=add}">{l10n get=SysCP.domains.add}</a>
			</td>
		</tr>{/if}
		{if 0 < $pages}<tr>
			<td colspan="6" class="field_display">
				{$paging}
			</td>
		</tr>{/if}
		<tr>
			<td class="field_display_border_left">{l10n get=SysCP.globallang.id}</td>
			<td class="field_display">{l10n get=SysCP.domains.domain}</td>
			<td class="field_display">{l10n get=SysCP.domains.ipport}</td>
			<td class="field_display">{l10n get=SysCP.domains.customer}</td>
			<td class="field_display" colspan="2">&nbsp;</td>
		</tr>
		{foreach from=$domain_list item=row}
		<tr>
			<td class="field_name_border_left"><font size="-1">{$row.id}</font></td>
			<td class="field_name"><font size="-1">{$row.domain}</font></td>
			<td class="field_name"><font size="-1">{$row.ipandport}</font></td>
			<td class="field_name"><font size="-1">{$row.name} {$row.firstname} ({$row.loginname})</font></td>
			<td class="field_name">
				{if !$row.standardsubdomain && !$row.aliasdomain}
				<a href="{url module=domains action=delete id=$row.id}">{l10n get=SysCP.globallang.delete}</a>
				{/if}
			</td>
			<td class="field_name">
				<a href="{url module=domains action=edit id=$row.id}">{l10n get=SysCP.globallang.edit}</a>
			</td>
		</tr>
		{/foreach}
		{if 0 < $pages}<tr>
			<td colspan="field_display" class="maintable">
				{$paging}
			</td>
		</tr>{/if}
		{if $User.domains_used < $User.domains || $User.domains == '-1'}<tr>
			<td class="field_display_border_left" colspan="6">
				<a href="{url module=domains action=add}">{l10n get=SysCP.domains.add}</a>
			</td>
		</tr>{/if}
	</table>