	<table cellpadding="5" cellspacing="0" border="0" align="center" class="maintable">
		<tr>
			<td colspan="7" class="maintitle">
				<img src="{$imagedir}title.gif" alt="" />&nbsp;{l10n get=SysCP.extras.pathoptions}
			</td>
		</tr>
		<tr>
			<td class="field_display_border_left">{l10n get=SysCP.globallang.path}</td>
			<td class="field_display">{l10n get=SysCP.extras.view_directory}</td>
			<td class="field_display">{l10n get=SysCP.extras.error404path}</td>
			<td class="field_display">{l10n get=SysCP.extras.error403path}</td>
			<td class="field_display">{l10n get=SysCP.extras.error500path}</td>
			<td class="field_display" colspan="2">&nbsp;</td>
		</tr>
		{foreach from=$htaccess item=row}
		<tr>
			<td class="field_name_border_left">{$row.path}</td>
			<td class="field_name">{$row.options_indexes}</td>
			<td class="field_name">{$row.error404path}</td>
			<td class="field_name">{$row.error403path}</td>
			<td class="field_name">{$row.error500path}</td>
			<td class="field_name">
				<a href="{url module=extras action=editHtaccess id=$row.id}">{l10n get=SysCP.globallang.edit}</a>
			</td>
			<td class="field_name">
				<a href="{url module=extras action=deleteHtaccess id=$row.id}">{l10n get=SysCP.globallang.delete}</a>
			</td>
		</tr>
		{/foreach}

		<tr>
			<td class="field_display_border_left" colspan="7">
				<a href="{url module=extras action=addHtaccess}">{l10n get=SysCP.extras.pathoptions_add}</a>
			</td>
		</tr>
	</table>
