	<form method="post" action="{url module=extras action=editHtpasswds}">
		<input type="hidden" name="id" value="{$Config->get('env.id')}">
		<table cellpadding="5" cellspacing="4" border="0" align="center" class="maintable_60">
			<tr>
				<td colspan="2" class="maintitle">
					<img src="{$imagedir}title.gif" alt="" />&nbsp;{l10n get=SysCP.globallang.changepassword}
				</td>
			</tr>
			<tr>
				<td class="main_field_name">{l10n get=SysCP.globallang.path}:</td>
				<td class="main_field_display" nowrap="nowrap">{$result.path}</td>
			</tr>
			<tr>
				<td class="main_field_name">{l10n get=SysCP.globallang.username}:</td>
				<td class="main_field_display" nowrap="nowrap">{$result.username}</td>
			</tr>
			<tr>
				<td class="main_field_name">{l10n get=SysCP.globallang.password}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="password" name="password" maxlength="50" /></td>
			</tr>
			<tr>
				<td class="main_field_confirm" colspan="2">
					<input type="hidden" name="send" value="send" />
					<input class="bottom" type="submit" value="{l10n get=SysCP.globallang.changepassword}" />
				</td>
			</tr>
		</table>
	</form>