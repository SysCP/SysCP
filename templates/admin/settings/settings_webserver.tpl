		<table cellpadding="5" cellspacing="4" border="0" align="center" class="maintable">
			<tr>
				<td class="maintitle_apply_left">
					<b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['webserversettings']}</b>
				</td>
				<td class="maintitle_apply_right" nowrap="nowrap">
                                        <a href="$filename?page=settings&amp;s=$s">{$lng['panel']['backtooverview']}</a>
				</td>
			</tr>
                        <tr>
                                <td class="main_field_name"><b>{$lng['admin']['webserver']}:&nbsp;</b><br /></td>
				<td class="main_field_display" nowrap="nowrap"><select class="dropdown_noborder" name="panel_webserver_selected">$webserver_selected</select></td>
                        </tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['apacheconf_vhost']['title']}:</b><br />{$lng['serversettings']['apacheconf_vhost']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="text" name="system_apacheconf_vhost" value="{$settings['system']['apacheconf_vhost']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['apacheconf_diroptions']['title']}:</b><br />{$lng['serversettings']['apacheconf_diroptions']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="text" name="system_apacheconf_diroptions" value="{$settings['system']['apacheconf_diroptions']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['apacheconf_htpasswddir']['title']}:</b><br />{$lng['serversettings']['apacheconf_htpasswddir']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="text" name="system_apacheconf_htpasswddir" value="{$settings['system']['apacheconf_htpasswddir']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['apachereload_command']['title']}:</b><br />{$lng['serversettings']['apachereload_command']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="text" name="system_apachereload_command" value="{$settings['system']['apachereload_command']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['mod_log_sql']['title']}:</b><br />{$lng['serversettings']['mod_log_sql']['description']}</td>
				<td class="main_field_display" nowrap="nowrap">{$system_modlogsql}</td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['logfiles_directory']['title']}:</b><br />{$lng['serversettings']['logfiles_directory']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="text" name="system_logfiles_directory" value="{$settings['system']['logfiles_directory']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['phpappendopenbasedir']['title']}:</b><br />{$lng['serversettings']['phpappendopenbasedir']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="text" name="system_phpappendopenbasedir" value="{$settings['system']['phpappendopenbasedir']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['deactivateddocroot']['title']}:</b><br />{$lng['serversettings']['deactivateddocroot']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="text" name="system_deactivateddocroot" value="{$settings['system']['deactivateddocroot']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name" valign="top"><b>{$lng['serversettings']['default_vhostconf']['title']}:</b></td>
				<td class="main_field_display" nowrap="nowrap"><textarea class="textarea_noborder" rows="12" cols="40" name="system_default_vhostconf">{$settings['system']['default_vhostconf']}</textarea></td>
			</tr>
			<tr>
				<td class="maintitle_apply_right" nowrap="nowrap" colspan="2">
					<if $_part == 'webserver'>
						<input type="hidden" name="part" value="webserver" />
					</if>
					<input class="bottom" type="reset" value="{$lng['panel']['reset']}" /><input class="bottom" type="submit" value="{$lng['panel']['save']}" />
				</td>
			</tr>
		</table>
