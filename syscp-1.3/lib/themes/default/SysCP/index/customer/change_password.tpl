	<form method="post" action="{url module=index action=changePassword}">
		<table cellpadding="5" cellspacing="4" border="0" align="center" class="maintable_60">
			<tr>
				<td class="none" rowspan="7"><img src="{$imagedir}logininternal.gif" alt="" /></td>
			</tr>
			<tr>
				<td colspan="2" class="maintitle">
					<img src="{$imagedir}title.gif" alt="" />&nbsp;{l10n get=SysCP.globallang.changepassword}
				</td>
			</tr>
			<tr>
				<td class="main_field_name">{l10n get=SysCP.index.old_password}:</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="password" name="old_password" maxlength="50" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">{l10n get=SysCP.index.new_password}:</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="password" name="new_password" maxlength="50 /">
				</td>
			</tr>
			<tr>
				<td class="main_field_name">{l10n get=SysCP.index.new_password_confirm}:</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="password" name="new_password_confirm" maxlength="50" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name" colspan="2">
					<input type="checkbox" name="change_main_ftp" value="true" />{l10n get=SysCP.index.change_ftp_pw}
				</td>
			</tr>
			<tr>
				<td class="main_field_confirm" colspan="2">
					<input type="hidden" name="send" value="send" />
					<input class="bottom" type="submit" value="{l10n get=SysCP.globallang.changepassword}" />
				</td>
			</tr>
		</table>
	</form>