	<form method="post" action="{url module=index action=changeTheme}">
		<table cellpadding="5" cellspacing="4" border="0" align="center" class="maintable_60">
			<tr>
				<td class="none" rowspan="4">
					<img src="{$imagedir}changetheme.gif" alt="" />
				</td>
			</tr>
			<tr>
				<td colspan="2" class="maintitle">
					<img src="{$imagedir}title.gif" alt="" />&nbsp;{l10n get=SysCP.index.changetheme}
				</td>
			</tr>
			<tr>
				<td class="main_field_name">{l10n get=SysCP.index.theme}:</td>
				<td class="main_field_display" nowrap="nowrap">
					<select class="dropdown_noborder" name="new_theme">
						{html_options options=$theme_list selected=$theme_sel}
					</select>
				</td>
			</tr>
			<tr>
				<td class="main_field_confirm" colspan="2">
					<input type="hidden" name="send" value="send" />
					<input class="bottom" type="submit" value="{l10n get=SysCP.index.changetheme}" />
				</td>
			</tr>
		</table>
	</form>