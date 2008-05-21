		<table cellpadding="5" cellspacing="4" border="0" align="center" class="maintable">
			<tr>
				<td class="maintitle_apply_left">
					<b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['nameserversettings']}</b>
				</td>
				<td class="maintitle_apply_right" nowrap="nowrap"><a href="$filename?page=settings&amp;s=$s">{$lng['panel']['backtooverview']}</a>
				</td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['bindconf_directory']['title']}:</b><br />{$lng['serversettings']['bindconf_directory']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="text" name="system_bindconf_directory" value="{$settings['system']['bindconf_directory']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['bindreload_command']['title']}:</b><br />{$lng['serversettings']['bindreload_command']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="text" name="system_bindreload_command" value="{$settings['system']['bindreload_command']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['nameservers']['title']}:</b><br />{$lng['serversettings']['nameservers']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="text" name="system_nameservers" value="{$settings['system']['nameservers']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['mxservers']['title']}:</b><br />{$lng['serversettings']['mxservers']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="text" name="system_mxservers" value="{$settings['system']['mxservers']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['selfdns']['title']}:</b></td>
				<td class="main_field_display" nowrap="nowrap">{$system_userdns}</td>
			</tr>
			<if $settings['system']['userdns'] == '1'>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['selfdnscustomer']['title']}:</b></td>
				<td class="main_field_display" nowrap="nowrap">{$system_customerdns}</td>
			</tr>
			</if>
			<tr>
				<td class="maintitle_apply_right" nowrap="nowrap" colspan="2">
					<if $_part == 'nameserver'>
						<input type="hidden" name="part" value="nameserver" />
					</if>
					<input class="bottom" type="reset" value="{$lng['panel']['reset']}" /><input class="bottom" type="submit" value="{$lng['panel']['save']}" />
				</td>
			</tr>
		</table>