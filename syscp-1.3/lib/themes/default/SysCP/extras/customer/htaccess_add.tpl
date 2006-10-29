	<form method="post" action="{url module=extras action=addHtaccess}">
		<table cellpadding="5" cellspacing="4" border="0" align="center" class="maintable_60">
			<tr>
				<td colspan="2" class="maintitle">
					<img src="{$imagedir}title.gif" alt="" />&nbsp;{l10n get=SysCP.extras.pathoptions_add}
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					<b>{l10n get=SysCP.globallang.path}:</b>
				</td>
				<td class="main_field_display" nowrap="nowrap">
					{$pathSelect}
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					<b>{l10n get=SysCP.extras.directory_browsing}:</b>
				</td>
				<td class="main_field_display" nowrap="nowrap">
					{html_radios name=options_indexes options=$options_indexes selected=$options_indexes_sel}
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					<b>{l10n get=SysCP.extras.errordocument404path}:</b><br />
					{l10n get=SysCP.globallang.emptyfordefaults}
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="error404path" value="" maxlength="50" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					<b>{l10n get=SysCP.extras.errordocument403path}:</b><br />
					{l10n get=SysCP.globallang.emptyfordefaults}
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="error403path" value="" maxlength="50" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					<b>{l10n get=SysCP.extras.errordocument500path}:</b><br />
					{l10n get=SysCP.globallang.emptyfordefaults}
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="error500path" value="" maxlength="50" />
				</td>
			</tr>
			<tr>
				<td class="main_field_confirm" colspan="2">
					<input type="hidden" name="send" value="send" />
					<input class="bottom" type="submit" value="{l10n get=SysCP.extras.pathoptions_add}" />
				</td>
			</tr>
		</table>
	</form>
