	<table cellpadding="5" cellspacing="0" border="0" align="center" class="maintable_60">
		<tr>
			<td colspan="4" class="maintitle">
				<img src="{$imagedir}title.gif" alt="" />&nbsp;{l10n get=SysCP.ipsandports.ipsandports}
			</td>
		</tr>
		<tr>
			<td class="field_display_border_left" colspan="4" nowrap="nowrap" align="right">
				<form method="post" action="{url module=ipsandports action=list}">
					{l10n get=SysCP.ipsandports.default}:
					<select class="dropdown_noborder" name="defaultipandport">
						{html_options options=$ipsandports_default selected=$ipsandports_default_id}
					</select>
					<input type="hidden" name="send" value="send" />
					<input class="bottom" type="submit" value="{l10n get=SysCP.globallang.save}" />
				</form>
			</td>
		</tr>
		<tr>
			<td class="field_display_border_left">{l10n get=SysCP.globallang.id}</td>
			<td class="field_display">{l10n get=SysCP.ipsandports.ipandport}</td>
			<td class="field_display" colspan="2">&nbsp;</td>
		</tr>
		{foreach from=$ipsandports item=row}
		<tr>
			<td class="field_name_border_left"><font size="-1">{$row.id}</font></td>
			<td class="field_name"><font size="-1">{$row.ip}:{$row.port}</font></td>
			<td class="field_name">
				<a href="{url module=ipsandports action=delete id=$row.id}">{l10n get=SysCP.globallang.delete}</a>
			</td>
			<td class="field_name">
				<a href="{url module=ipsandports action=edit id=$row.id}">{l10n get=SysCP.globallang.edit}</a>
			</td>
		</tr>
		{/foreach}
		<tr>
			<td class="field_display_border_left" colspan="4">
				<a href="{url module=ipsandports action=add}">{l10n get=SysCP.ipsandports.add}</a>
			</td>
		</tr>
	</table>