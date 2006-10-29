	<br />
	<form method="post" action="{url module=login}" name="loginform">
		<table cellpadding="5" cellspacing="0" border="0" align="center" class="maintable_40">
			<tr>
				<td class="maintitle" colspan="3">
					<img src="{$imagedir}title.gif" alt="" />&nbsp;{l10n get=SysCP.login.syscplogin}
				</td>
			</tr>

			<tr>
				<td rowspan="4" class="field_name_center">
					<img src="{$imagedir}login.gif" alt="" />
				</td>
				<td class="field_name">
					<font size="-1">{l10n get=SysCP.globallang.username}:</font>
				</td>
				<td class="field_display">
					<input type="text" name="loginname" value="" maxlength="50" />
				</td>
			</tr>
			<tr>
				<td class="field_name">
					<font size="-1">{l10n get=SysCP.globallang.password}:</font>
				</td>
				<td class="field_display">
					<input type="password" name="password" maxlength="50" />
				</td>
			</tr>
			<tr>
				<td class="field_name">
					<font size="-1">{l10n get=SysCP.login.language}:</font>
				</td>
				<td class="field_display">
					<select class="dropdown_noborder" name="language">
						{html_options options=$lang_list selected=$lang_sel}
					</select>
				</td>
			</tr>
			<tr>
				<td class="field_name">
					<font size="-1">{l10n get=SysCP.login.theme}:</font>
				</td>
				<td class="field_display">
					<select class="dropdown_noborder" name="theme">
						{html_options options=$theme_list selected=$theme_sel}
					</select>
				</td>
			</tr>
			<tr>
				<td class="field_name_center" colspan="3">
					<input type="hidden" name="send" value="send" />
					<input type="submit" class="bottom" value="{l10n get=SysCP.login.login}" />
				</td>
			</tr>
		</table>
	</form>
	<br />
	<br />
	<br />