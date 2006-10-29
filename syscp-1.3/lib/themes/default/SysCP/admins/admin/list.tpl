	<table cellpadding="5" cellspacing="0" border="0" align="center" class="maintable">
		<tr>
			<td colspan="9" class="maintitle">
				<img src="{$imagedir}title.gif" alt="" />&nbsp;{l10n get=SysCP.admins.admins}
			</td>
		</tr>
		<tr>
			<td colspan="9" class="field_display_border_left">
				<a href="{url module=admins action=add}">{l10n get=SysCP.admins.admin_add}</a>
			</td>
		</tr>
		{if 0 < $pages}<tr>
			<td colspan="9" class="field_display">{$paging}</td>
		</tr>{/if}
		<tr>
			<td class="field_display_border_left">{l10n get=SysCP.globallang.id}</td>
			<td class="field_display">{l10n get=SysCP.globallang.name}</td>
			<td class="field_display">{l10n get=SysCP.admins.customers}<br >{l10n get=SysCP.admins.domains}</td>
			<td class="field_display">{l10n get=SysCP.admins.space}<br />{l10n get=SysCP.admins.traffic}</td>
			<td class="field_display">{l10n get=SysCP.admins.mysql}<br />{l10n get=SysCP.admins.ftp}</td>
			<td class="field_display">{l10n get=SysCP.admins.emails}<br />{l10n get=SysCP.admins.subdomains}</td>
			<td class="field_display">{l10n get=SysCP.admins.email_accounts}<br />{l10n get=SysCP.admins.email_forwarders}</td>
			<td class="field_display">{l10n get=SysCP.admins.active}</td>
			<td class="field_display">&nbsp;</td>
		</tr>
		{foreach from=$admin_list item=row}
		<tr>
			<td class="field_name_border_left">{$row.loginname}</td>
			<td class="field_name">{$row.name}</td>
			<td class="field_name">{$row.customers_used}/{$row.customers}<br />{$row.domains_used}/{$row.domains}</td>
			<td class="field_name">{$row.diskspace_used}/{$row.diskspace} ({l10n get=SysCP.globallang.mb})<br />{$row.traffic_used}/{$row.traffic} ({l10n get=SysCP.globallang.gb})</td>
			<td class="field_name">{$row.mysqls_used}/{$row.mysqls}<br />{$row.ftps_used}/{$row.ftps}</td>
			<td class="field_name">{$row.emails_used}/{$row.emails}<br />{$row.subdomains_used}/{$row.subdomains}</td>
			<td class="field_name">{$row.email_accounts_used}/{$row.email_accounts}<br />{$row.email_forwarders_used}/{$row.email_forwarders}</td>
			<td class="field_name">{$row.deactivated}</td>
			<td class="field_name">
				<a href="{url action=delete id=$row.adminid module=admins}">{l10n get=SysCP.globallang.delete}</a><br />
				<a href="{url action=edit id=$row.adminid module=admins}">{l10n get=SysCP.globallang.edit}</a>
			</td>
		</tr>
		{/foreach}
		{if 0 < $pages}<tr>
			<td colspan="9" class="field_display">
				{$paging}
			</td>
		</tr>{/if}
		<tr>
			<td colspan="9" class="field_display_border_left">
				<a href="{url module=admins action=add}">
					{l10n get=SysCP.admins.admin_add}
				</a>
			</td>
		</tr>
	</table>