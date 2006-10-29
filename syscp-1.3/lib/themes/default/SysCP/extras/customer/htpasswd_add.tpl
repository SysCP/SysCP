	<form method="post" action="{url module=extras action=addHtpasswds}">
		<table cellpadding="5" cellspacing="4" border="0" align="center" class="maintable_60">
			<tr>
				<td colspan="2" class="maintitle">
					<img src="{$imagedir}title.gif" alt="" />&nbsp;{l10n get=SysCP.extras.directoryprotection_add}
				</td>
			</tr>
			<tr>
				<td class="main_field_name">{l10n get=SysCP.globallang.path}:</td>
				<td class="main_field_display" nowrap="nowrap">{$pathSelect}</td>
			</tr>
			<tr>
				<td class="main_field_name">{l10n get=SysCP.globallang.username}:</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="username" size="30" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">{l10n get=SysCP.globallang.password}:</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="password" name="password" size="30" />
				</td>
			</tr>
			<tr>
				<td class="main_field_confirm" colspan="2">
					<input type="hidden" name="send" value="send" />
					<input class="bottom" type="submit" value="{l10n get=SysCP.extras.directoryprotection_add}" />
				</td>
			</tr>
		</table>
	</form>
