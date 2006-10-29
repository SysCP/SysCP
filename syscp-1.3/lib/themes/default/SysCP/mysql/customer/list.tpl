	<table cellpadding="5" cellspacing="0" border="0" align="center" class="maintable">
		<tr>
			<td colspan="4" class="maintitle">
				<img src="{$imagedir}title.gif" alt="" />&nbsp;{l10n get=SysCP.mysql.databases}
			</td>
		</tr>
		<tr>
			<td class="field_display_border_left">{l10n get=SysCP.mysql.db_name}</td>
			<td class="field_display">{l10n get=SysCP.mysql.db_desc}</td>
			<td class="field_display" colspan="2">&nbsp;</td>
		</tr>
		{if ($User.mysqls_used < $User.mysqls || $User.mysqls == '-1') && 15 < $mysqls_count}
		<tr>
			<td class="field_display_border_left" colspan="4">
				<a href="{url module=mysql action=add}">{l10n get=SysCP.mysql.db_create}</a>
			</td>
		</tr>
		{/if}
		{foreach from=$mysqlList item=row}
		<tr>
			<td class="field_name_border_left">{$row.databasename}</td>
			<td class="field_name">{$row.description}</td>
			<td class="field_name"><a href="{url module=mysql action=edit id=$row.id}">{l10n get=SysCP.globallang.edit}</a></td>
			<td class="field_name"><a href="{url module=mysql action=delete id=$row.id}">{l10n get=SysCP.globallang.delete}</a></td>
		</tr>
		{/foreach}
		{if ($User.mysqls_used < $User.mysqls || $User.mysqls == '-1')}
		<tr>
			<td class="field_display_border_left" colspan="4">
				<a href="{url module=mysql action=add}">{l10n get=SysCP.mysql.db_create}</a>
			</td>
		</tr>
		{/if}
	</table>