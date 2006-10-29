	<form method="post" action="{url module=ipsandports action=add}">
		<table cellpadding="5" cellspacing="4" border="0" align="center" class="maintable_40">
			<tr>
				<td colspan="2" class="maintitle">
					<img src="{$imagedir}title.gif" alt="" />&nbsp;{l10n get=SysCP.ipsandports.add}
				</td>
			</tr>
			<tr>
				<td class="main_field_name">{l10n get=SysCP.ipsandports.ip}:</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="ip" value="" size="15" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">{l10n get=SysCP.ipsandports.port}:</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="port" value="" size="5" />
				</td>
			</tr>
			<tr>
				<td class="main_field_confirm" colspan="2">
					<input type="hidden" name="send" value="send" />
					<input class="bottom" type="submit" value="{l10n get=SysCP.globallang.save}" />
				</td>
			</tr>
		</table>
	</form>
