	<table cellpadding="5" cellspacing="0" border="0" align="center" class="maintable">
		<tr>
			<td colspan="4" class="maintitle">
				<img src="{$imagedir}title.gif" alt="" />&nbsp;{l10n get=SysCP.ftp.accounts}
			</td>
		</tr>
		<tr>
			<td class="field_display_border_left">{l10n get=SysCP.globallang.username}</td>
			<td class="field_display">{l10n get=SysCP.globallang.path}</td>
			<td class="field_display" colspan="2">&nbsp;</td>
		</tr>
		{if ($User.ftps_used < $User.ftps || $User.ftps == '-1') }<tr>
			<td class="field_display_border_left" colspan="4">
				<a href="{url module=ftp action=add}">{l10n get=SysCP.ftp.add}</a>
			</td>
		</tr>{/if}
		{if 0 < $pages}<tr>
			<td colspan="4" class="paging">
				{$paging}
			</td>
		</tr>{/if}
		{foreach from=$accounts item=row}
		<tr>
			<td class="field_name_border_left">{$row.username}</td>
			<td class="field_name">{$row.documentroot}</td>
			<td class="field_name"><a href="{url module=ftp action=edit id=$row.id}">{l10n get=SysCP.globallang.changepassword}</a></td>
			<td class="field_name"><a href="{url module=ftp action=delete id=$row.id}">{l10n get=SysCP.globallang.delete}</a></td>
		</tr>
		{/foreach}
		{if 0 < $pages}<tr>
			<td colspan="4" class="paging">
				{$paging}
			</td>
		</tr>{/if}
		{if ($User.ftps_used < $User.ftps || $User.ftps == '-1') }<tr>
			<td class="field_display_border_left" colspan="4">
				<a href="{url module=ftp action=add}">{l10n get=SysCP.ftp.add}</a>
			</td>
		</tr>{/if}
	</table>
