		<table cellpadding="5" cellspacing="4" border="0" align="center" class="maintable">
			<tr>
				<td class="maintitle_apply_left">
					<b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['mailserversettings']}</b>
				</td>
				<td class="maintitle_apply_right" nowrap="nowrap"><a href="$filename?page=settings&amp;s=$s">{$lng['panel']['backtooverview']}</a>
				</td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['vmail_uid']['title']}:</b><br />{$lng['serversettings']['vmail_uid']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="text" name="system_vmail_uid" value="{$settings['system']['vmail_uid']}" maxlength="5" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['vmail_gid']['title']}:</b><br />{$lng['serversettings']['vmail_gid']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="text" name="system_vmail_gid" value="{$settings['system']['vmail_gid']}" maxlength="5" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['vmail_homedir']['title']}:</b><br />{$lng['serversettings']['vmail_homedir']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="text" name="system_vmail_homedir" value="{$settings['system']['vmail_homedir']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['sendalternativemail']['title']}:</b><br />{$lng['serversettings']['sendalternativemail']['description']}</td>
				<td class="main_field_display" nowrap="nowrap">{$panel_sendalternativemail}</td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['mail_quota_enabled']['title']}:</b><br />{$lng['serversettings']['mail_quota_enabled']['description']}<br /><a href="$filename?page=wipequotas&amp;s=$s">{$lng['serversettings']['mail_quota_enabled']['removelink']}</a></td>
				<td class="main_field_display" nowrap="nowrap">{$quota_enabled}</td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['mail_quota']['title']}:</b><br />{$lng['serversettings']['mail_quota']['description']}</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="system_mail_quota" value="{$quota}" size="6" />&nbsp;<select class="dropdown_noborder" name="system_mail_quota_type">$quota_type_option</select>
				</td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['autoresponder_active']['title']}:</b><br />{$lng['serversettings']['autoresponder_active']['description']}</td>
				<td class="main_field_display" nowrap="nowrap">{$autoresponder_active}</td>
			</tr>
			<tr>
				<td class="maintitle_apply_right" nowrap="nowrap" colspan="2">
					<if $_part == 'mail'>
						<input type="hidden" name="part" value="mail" />
					</if>
					<input class="bottom" type="reset" value="{$lng['panel']['reset']}" /><input class="bottom" type="submit" value="{$lng['panel']['save']}" />
				</td>
			</tr>
		</table>
