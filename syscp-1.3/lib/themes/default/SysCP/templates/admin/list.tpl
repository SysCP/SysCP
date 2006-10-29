	<table cellpadding="5" cellspacing="0" border="0" align="center" class="maintable">
		<tr>
			<td colspan="4" class="maintitle">
				<img src="{$imagedir}title.gif" alt="" />&nbsp;{l10n get=SysCP.templates.templates}
			</td>
		</tr>
		{if $add}<tr>
			<td colspan="4" class="field_display_border_left">
				<a href="{url module=templates action=add}">{l10n get=SysCP.templates.template_add}</a>
			</td>
		</tr>{/if}
		<tr>
			<td class="field_display_border_left">{l10n get=SysCP.templates.language}</td>
			<td class="field_display">{l10n get=SysCP.templates.action}</td>
			<td class="field_display" colspan="2">&nbsp;</td>
		</tr>
		{foreach from=$templates item=row}
		<tr>
			<td class="field_name_border_left">{$row.language}</td>
			<td class="field_name">{$row.template}</td>
			<td class="field_name"><a href="{url module=templates action=delete subjectid=$row.subjectid mailbodyid=$row.mailbodyid}">{l10n get=SysCP.globallang.delete}</a></td>
			<td class="field_name"><a href="{url module=templates action=edit   subjectid=$row.subjectid mailbodyid=$row.mailbodyid}">{l10n get=SysCP.globallang.edit}</a></td>
		</tr>
		{/foreach}
		{if $add}<tr>
			<td colspan="4" class="field_display_border_left">
				<a href="{url module=templates action=add}">{l10n get=SysCP.templates.template_add}</a>
			</td>
		</tr>{/if}
	</table>