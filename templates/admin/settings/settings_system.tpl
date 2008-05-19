		<table cellpadding="5" cellspacing="4" border="0" align="center" class="maintable">
			<tr>
				<td class="maintitle_apply_left">
					<b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['systemsettings']}</b>
				</td>
				<td class="maintitle_apply_right" nowrap="nowrap">
                                        <a href="$filename?page=settings&amp;s=$s">{$lng['panel']['backtooverview']}</a>
				</td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['documentroot_prefix']['title']}:</b><br />{$lng['serversettings']['documentroot_prefix']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="system_documentroot_prefix" value="{$settings['system']['documentroot_prefix']}" /></td>
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
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="system_hostname" value="{$settings['system']['hostname']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['mysql_access_host']['title']}:</b><br />{$lng['serversettings']['mysql_access_host']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="system_mysql_access_host" value="{$settings['system']['mysql_access_host']}" /></td>
			</tr>
			<tr>
				<td class="maintitle_apply_left">
				</td>
				<td class="maintitle_apply_right" nowrap="nowrap">
					<input class="bottom" type="reset" value="{$lng['panel']['reset']}" /><input class="bottom" type="submit" value="{$lng['panel']['save']}" />
				</td>
			</tr>
		</table>
