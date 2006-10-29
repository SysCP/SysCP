	<table cellpadding="5" cellspacing="0" border="0" align="center" class="maintable">
		<tr>
			<td colspan="4" class="maintitle">
				<img src="{$imagedir}title.gif" alt="" />{l10n get=SysCP.extras.directoryprotection}
			</td>
		</tr>
		<tr>
			<td class="field_display_border_left">{l10n get=SysCP.globallang.username}</td>
			<td class="field_display">{l10n get=SysCP.globallang.path}</td>
			<td class="field_display" colspan="2">&nbsp;</td>
		</tr>
		{foreach from=$htpasswds item=row}
		<tr>
			<td class="field_name_border_left">{$row.username}</td>
			<td class="field_name">{$row.path}</td>
			<td class="field_name"><a href="{url module=extras action=editHtpasswds id=$row.id}">{l10n get=SysCP.globallang.changepassword}</a></td>
			<td class="field_name"><a href="{url module=extras action=deleteHtpasswds id=$row.id}">{l10n get=SysCP.globallang.delete}</a></td>
		</tr>
		{/foreach}
		<tr>
			<td class="field_display_border_left" colspan="4">
				<a href="{url module=extras action=addHtpasswds}">{l10n get=SysCP.extras.directoryprotection_add}</a>
			</td>
		</tr>
	</table>