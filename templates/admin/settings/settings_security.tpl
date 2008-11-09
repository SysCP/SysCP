<table cellpadding="5" cellspacing="4" border="0" align="center" class="maintable">
	<tr>
		<td class="maintitle_apply_left">
			<b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['security_settings']}</b>
		</td>
		<td class="maintitle_apply_right" nowrap="nowrap"><a href="$filename?page=settings&amp;s=$s">{$lng['panel']['backtooverview']}</a>
		</td>
	</tr>
	<tr>
        	<td class="main_field_name">
			<b>{$lng['serversettings']['unix_names']['title']}:</b><br />{$lng['serversettings']['unix_names']['description']}<br />
				<div style="color:red">{$lng['admin']['know_what_youre_doing']}</div>
		</td>
                <td class="main_field_display" nowrap="nowrap">{$unix_names}</td>
	</tr>
	<tr>
		<td class="main_field_name">
			<b>{$lng['serversettings']['mailpwcleartext']['title']}:</b>
			<br />{$lng['serversettings']['mailpwcleartext']['description']}<br /><a href="$filename?page=wipecleartextmailpws&amp;s=$s">{$lng['serversettings']['mailpwcleartext']['removelink']}</a><br />
			<div style="color:red">{$lng['admin']['know_what_youre_doing']}</div>
		</td>
		<td class="main_field_display" nowrap="nowrap">{$mailpwcleartext}</td>
	</tr>
	<tr>
		<td class="maintitle" colspan="2">
			<b><img src="images/title.gif" alt="" />&nbsp;FastCGI / mod_fcgid</b>
		</td>
	</tr>
	<tr>
		<td class="main_field_name">
			<b>{$lng['serversettings']['mod_fcgid']['title']}:</b><br />{$lng['serversettings']['mod_fcgid']['description']}<br />
			<div style="color:red">{$lng['admin']['know_what_youre_doing']}</div>
		</td>
		<td class="main_field_display" nowrap="nowrap">{$system_modfcgid}</td>
	</tr>
	<tr>
		<td class="main_field_name">
			<b>{$lng['serversettings']['mod_fcgid']['configdir']}:</b><br />{$lng['serversettings']['mod_fcgid']['configdir_desc']}<br />
		</td>
		<td class="main_field_display" nowrap="nowrap"><input type="text" class="text" name="system_mod_fcgid_configdir" value="{$settings['system']['mod_fcgid_configdir']}" /></td>
	</tr>
	<tr>
		<td class="main_field_name">
			<b>{$lng['serversettings']['mod_fcgid']['tmpdir']}:</b><br />{$lng['serversettings']['mod_fcgid']['tmpdir_desc']}
		</td>
		<td class="main_field_display" nowrap="nowrap"><input type="text" class="text" name="system_mod_fcgid_tmpdir" value="{$settings['system']['mod_fcgid_tmpdir']}" /></td>
	</tr>
	<tr>
		<td class="main_field_name">
			<b>{$lng['serversettings']['mod_fcgid']['peardir']}:</b><br />{$lng['serversettings']['mod_fcgid']['peardir_desc']}
		</td>
		<td class="main_field_display" nowrap="nowrap"><input type="text" class="text" name="system_mod_fcgid_peardir" value="{$settings['system']['mod_fcgid_peardir']}" /></td>
	</tr>
	<tr>
		<td class="main_field_name">
			<b>{$lng['serversettings']['mod_fcgid']['wrapper']}:</b><br />{$lng['serversettings']['mod_fcgid']['wrapper_desc']}
		</td>
		<td class="main_field_display" nowrap="nowrap"><select name="system_mod_fcgid_wrapper">$system_modfcgid_wrapper</select></td>
	</tr>
	<tr>
		<td class="main_field_name">
			<b>{$lng['serversettings']['mod_fcgid']['starter']}:</b><br />{$lng['serversettings']['mod_fcgid']['starter_desc']}
		</td>
		<td class="main_field_display" nowrap="nowrap"><input type="text" class="text" name="system_mod_fcgid_starter" value="{$settings['system']['mod_fcgid_starter']}" /></td>
	</tr>
	<tr>
		<td class="main_field_name">
			<b>{$lng['serversettings']['mod_fcgid']['maxrequests']}:</b><br />{$lng['serversettings']['mod_fcgid']['maxrequests_desc']}
		</td>
		<td class="main_field_display" nowrap="nowrap"><input type="text" class="text" name="system_mod_fcgid_maxrequests" value="{$settings['system']['mod_fcgid_maxrequests']}" /></td>
	</tr>
	<tr>
		<td class="maintitle_apply_right" nowrap="nowrap" colspan="2">
			<if $_part == 'security'>
				<input type="hidden" name="part" value="security" />
			</if>
			<input class="bottom" type="reset" value="{$lng['panel']['reset']}" />&nbsp;<input class="bottom" type="submit" value="{$lng['panel']['save']}" />
		</td>
	</tr>
</table>
