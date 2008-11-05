		<table cellpadding="5" cellspacing="4" border="0" align="center" class="maintable">
			<tr>
				<td class="maintitle_apply_left">
					<b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['systemsettings']}</b>
				</td>
				<td class="maintitle_apply_right" nowrap="nowrap"><a href="$filename?page=settings&amp;s=$s">{$lng['panel']['backtooverview']}</a>
				</td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['documentroot_prefix']['title']}:</b><br />{$lng['serversettings']['documentroot_prefix']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="text" name="system_documentroot_prefix" value="{$settings['system']['documentroot_prefix']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['ipaddress']['title']}:</b><br />{$lng['serversettings']['ipaddress']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><select class="dropdown_noborder" name="system_ipaddress">$system_ipaddress</select></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['defaultip']['title']}:</b><br />{$lng['serversettings']['defaultip']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><select class="dropdown_noborder" name="system_defaultip">$system_defaultip</select></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['hostname']['title']}:</b><br />{$lng['serversettings']['hostname']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="text" name="system_hostname" value="{$settings['system']['hostname']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['mysql_access_host']['title']}:</b><br />{$lng['serversettings']['mysql_access_host']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="text" name="system_mysql_access_host" value="{$settings['system']['mysql_access_host']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['system_realtime_port']['title']}:</b><br />{$lng['serversettings']['system_realtime_port']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="text" name="system_realtime_port" value="{$settings['system']['realtime_port']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['index_file_extension']['title']}:</b><br />{$lng['serversettings']['index_file_extension']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="text" name="index_file_extension" value="{$settings['system']['index_file_extension']}" /></td>
			</tr>
			<tr>
				<td class="maintitle_apply_right" nowrap="nowrap" colspan="2">
					<if $_part == 'system'>
						<input type="hidden" name="part" value="system" />
					</if>
					<input class="bottom" type="reset" value="{$lng['panel']['reset']}" /><input class="bottom" type="submit" value="{$lng['panel']['save']}" />
				</td>
			</tr>
		</table>
