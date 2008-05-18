$header
	<form method="post" action="$filename">
		<input type="hidden" name="send" value="send" />
		<input type="hidden" name="s" value="$s" />
		<input type="hidden" name="page" value="$page" />
		<input type="hidden" name="action" value="$action" />
		<table cellpadding="5" cellspacing="4" border="0" align="center" class="maintable">
			<tr>
				<td class="maintitle_apply_left">
					<b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['nameserversettings']}</b>
				</td>
				<td class="maintitle_apply_right" nowrap="nowrap">
                                        <a href="$filename?page=settings&amp;s=$s">{$lng['panel']['backtooverview']}</a>
				</td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['bindconf_directory']['title']}:</b><br />{$lng['serversettings']['bindconf_directory']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="system_bindconf_directory" value="{$settings['system']['bindconf_directory']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['bindreload_command']['title']}:</b><br />{$lng['serversettings']['bindreload_command']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="system_bindreload_command" value="{$settings['system']['bindreload_command']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['nameservers']['title']}:</b><br />{$lng['serversettings']['nameservers']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="system_nameservers" value="{$settings['system']['nameservers']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['mxservers']['title']}:</b><br />{$lng['serversettings']['mxservers']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="system_mxservers" value="{$settings['system']['mxservers']}" /></td>
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
