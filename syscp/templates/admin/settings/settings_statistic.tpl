		<table cellpadding="5" cellspacing="4" border="0" align="center" class="maintable">
			<tr>
				<td class="maintitle_apply_left">
					<b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['statisticsettings']}</b>
				</td>
				<td class="maintitle_apply_right" nowrap="nowrap"><a href="$filename?page=settings&amp;s=$s">{$lng['panel']['backtooverview']}</a>
				</td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['webalizer_quiet']['title']}:</b><br />{$lng['serversettings']['webalizer_quiet']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><select class="dropdown_noborder" name="system_webalizer_quiet">$webalizer_quiet</select></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['awstats_enabled']}:</b></td>
				<td class="main_field_display" nowrap="nowrap">$system_awstats_enabled</td>
			</tr>
			<if $settings['system']['awstats_enabled'] == '1'>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['awstats_domain_file']['title']}:</b></td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="text" name="system_awstats_domain_file" value="{$settings['system']['awstats_domain_file']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['awstats_model_file']['title']}:</b></td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="text" name="system_awstats_model_file" value="{$settings['system']['awstats_model_file']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['awstats_path']['title']}:</b><br />{$lng['serversettings']['awstats_path']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="text" name="system_awstats_path" value="{$settings['system']['awstats_path']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['awstats_updateall_command']['title']}:</b><br />{$lng['serversettings']['awstats_updateall_command']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="text" name="system_awstats_updateall_command" value="{$settings['system']['awstats_updateall_command']}" /></td>
			</tr>
			</if>
			<tr>
				<td class="maintitle_apply_right" nowrap="nowrap" colspan="2">
					<if $_part == 'statistic'>
						<input type="hidden" name="part" value="statistic" />
					</if>
					<input class="bottom" type="reset" value="{$lng['panel']['reset']}" /><input class="bottom" type="submit" value="{$lng['panel']['save']}" />
				</td>
			</tr>
		</table>