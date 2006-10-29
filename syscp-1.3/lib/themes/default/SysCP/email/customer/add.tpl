	<form method="post" action="{url module=email action=add}">
		<table cellpadding="5" cellspacing="4" border="0" align="center" class="maintable_60">
			<tr>
				<td colspan="2" class="maintitle">
					<img src="{$imagedir}title.gif" alt="" />&nbsp;{l10n get=SysCP.email.add}
				</td>
			</tr>
			<tr>
				<td class="main_field_name">{l10n get=SysCP.email.emailaddress}:</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="email_part" value="" size="15" /> @
					<select class="dropdown_noborder" name="domain">
						{html_options options=$domainList}
					</select>
				</td>
			</tr>
			<tr>
				<td class="main_field_name">{l10n get=SysCP.email.iscatchall}</td>
				<td class="main_field_display" nowrap="nowrap">
					{html_radios name="iscatchall" options=$isCatchall selected=$isCatchallSel}
				</td>
			</tr>
			<tr>
				<td class="main_field_confirm" colspan="2">
					<input type="hidden" name="send" value="send" />
					<input class="bottom" type="submit" value="{l10n get=SysCP.email.add}" />
				</td>
			</tr>
		</table>
	</form>