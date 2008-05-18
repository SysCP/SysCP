$header
	<form method="post" action="$filename">
		<input type="hidden" name="send" value="send" />
		<input type="hidden" name="s" value="$s" />
		<input type="hidden" name="page" value="$page" />
		<input type="hidden" name="action" value="$action" />
		<table cellpadding="5" cellspacing="4" border="0" align="center" class="maintable">
			<tr>
				<td class="maintitle_apply_left">
					<b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['dkimsettings']}</b>
				</td>
				<td class="maintitle_apply_right" nowrap="nowrap">
				</td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['dkim']['use_dkim']['title']}</b><br />{$lng['dkim']['use_dkim']['description']}</td>
				<td class="main_field_display" nowrap="nowrap">$dkimenabled</td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['dkim']['dkim_prefix']['title']}:</b><br />{$lng['dkim']['dkim_prefix']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="dkim_prefix" value="{$settings['dkim']['dkim_prefix']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['dkim']['dkim_domains']['title']}:</b><br />{$lng['dkim']['dkim_domains']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="dkim_domains" value="{$settings['dkim']['dkim_domains']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['dkim']['dkim_dkimkeys']['title']}:</b><br />{$lng['dkim']['dkim_dkimkeys']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="dkim_dkimkeys" value="{$settings['dkim']['dkim_dkimkeys']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['dkim']['dkimrestart_command']['title']}:</b><br />{$lng['dkim']['dkimrestart_command']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="dkimrestart_command" value="{$settings['dkim']['dkimrestart_command']}" /></td>
			</tr>
			<tr>
				<td class="maintitle_apply_left">
				</td>
				<td class="maintitle_apply_right" nowrap="nowrap">
					<input class="bottom" type="reset" value="{$lng['panel']['reset']}" /><input class="bottom" type="submit" value="{$lng['panel']['save']}" />
				</td>
			</tr>
		</table>
	</form>
	<br />
	<br />
$footer
