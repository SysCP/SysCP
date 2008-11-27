<table cellpadding="5" cellspacing="4" border="0" align="center" class="maintable">
	<tr>
		<td class="maintitle_apply_left">
			<b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['aps']}</b>
		</td>
		<td class="maintitle_apply_right" nowrap="nowrap"><a href="$filename?page=settings&amp;s=$s">{$lng['panel']['backtooverview']}</a>
		</td>
	</tr>
	<if $settings['system']['webserver'] == 'lighttpd'>
	<tr>
		<td class="maintitle_apply_left" colspan="2"><b style="color:red;">{$lng['aps']['lightywarning']}:</b><br />{$lng['aps']['lightywarningdescription']}</td>
	</tr>
	</if>
	<tr>
		<td class="main_field_name"><b>{$lng['aps']['activate_aps']['title']}:</b><br />{$lng['aps']['activate_aps']['description']}</td>
		<td class="main_field_display" nowrap="nowrap">$aps_activate_aps</td>
	</tr>
	<tr>
		<td class="main_field_name"><b>{$lng['aps']['packages_per_page']['title']}:</b><br />{$lng['aps']['packages_per_page']['description']}</td>
		<td class="main_field_display" nowrap="nowrap"><input type="text" name="items_per_page" size="60" value="{$settings['aps']['items_per_page']}"/></td>
	</tr>
	<tr>
		<td class="main_field_name"><b>{$lng['aps']['upload_fields']['title']}:</b><br />{$lng['aps']['upload_fields']['description']}</td>
		<td class="main_field_display" nowrap="nowrap"><input type="text" name="upload_fields" size="60" value="{$settings['aps']['upload_fields']}"/></td>
	</tr>
	<tr>
		<td class="maintitle_apply_left" colspan="2"><b>{$lng['aps']['exceptions']['title']}:</b><br />{$lng['aps']['exceptions']['description']}</td>
	</tr>
	<tr>
		<td class="main_field_name" valign="top"><b>{$lng['aps']['settings_php_extensions']}:</b></td>
		<td class="main_field_display" nowrap="nowrap">
			<input type="checkbox" name="gd" value="1" <if in_array('gd', explode(',', $settings['aps']['php-extension'])) >checked="checked"</if>/> GD Library<br/>
			<input type="checkbox" name="pcre" value="1" <if in_array('pcre', explode(',', $settings['aps']['php-extension'])) >checked="checked"</if>/> PCRE<br/>
			<input type="checkbox" name="ioncube" value="1" <if in_array('ioncube', explode(',', $settings['aps']['php-extension'])) >checked="checked"</if>/> ionCube Loader<br/>
			<input type="checkbox" name="curl" value="1" <if in_array('curl', explode(',', $settings['aps']['php-extension'])) >checked="checked"</if>/> curl<br/>
			<input type="checkbox" name="mcrypt" value="1" <if in_array('mcrypt', explode(',', $settings['aps']['php-extension'])) >checked="checked"</if>/> mcrypt<br/>
			<input type="checkbox" name="imap" value="1" <if in_array('imap', explode(',', $settings['aps']['php-extension'])) >checked="checked"</if>/> imap
		</td>
	</tr>
	<tr>
		<td class="main_field_name" valign="top"><b>{$lng['aps']['settings_php_configuration']}:</b></td>
		<td class="main_field_display" nowrap="nowrap">
			<input type="checkbox" name="short_open_tag" value="1" <if in_array('short_open_tag', explode(',', $settings['aps']['php-configuration'])) >checked="checked"</if>/> short_open_tag<br/>
			<input type="checkbox" name="file_uploads" value="1" <if in_array('file_uploads', explode(',', $settings['aps']['php-configuration'])) >checked="checked"</if>/> file_uploads<br/>
			<input type="checkbox" name="magic_quotes_gpc" value="1" <if in_array('magic_quotes_gpc', explode(',', $settings['aps']['php-configuration'])) >checked="checked"</if>/> magic_quotes_gpc<br/>
			<input type="checkbox" name="register_globals" value="1" <if in_array('register_globals', explode(',', $settings['aps']['php-configuration'])) >checked="checked"</if>/> register_globals<br/>
			<input type="checkbox" name="allow_url_fopen" value="1" <if in_array('allow_url_fopen', explode(',', $settings['aps']['php-configuration'])) >checked="checked"</if>/> allow_url_fopen<br/>
			<input type="checkbox" name="safe_mode" value="1" <if in_array('safe_mode', explode(',', $settings['aps']['php-configuration'])) >checked="checked"</if>/> safe_mode<br/>
			<input type="checkbox" name="post_max_size" value="1" <if in_array('post_max_size', explode(',', $settings['aps']['php-configuration'])) >checked="checked"</if>/> post_max_size<br/>
			<input type="checkbox" name="memory_limit" value="1" <if in_array('memory_limit', explode(',', $settings['aps']['php-configuration'])) >checked="checked"</if>/> memory_limit<br/>
			<input type="checkbox" name="max_execution_time" value="1" <if in_array('max_execution_time', explode(',', $settings['aps']['php-configuration'])) >checked="checked"</if>/> max_execution_time
		</td>
	</tr>
	<tr>
		<td class="main_field_name" valign="top"><b>{$lng['aps']['settings_webserver_modules']}:</b></td>
		<td class="main_field_display" nowrap="nowrap">
			<input type="checkbox" name="mod_perl" value="1" <if in_array('mod_perl', explode(',', $settings['aps']['webserver-module'])) >checked="checked"</if>/> mod_perl<br/>
			<input type="checkbox" name="mod_rewrite" value="1" <if in_array('mod_rewrite', explode(',', $settings['aps']['webserver-module'])) >checked="checked"</if>/> mod_rewrite<br/>
			<input type="checkbox" name="mod_access" value="1" <if in_array('mod_access', explode(',', $settings['aps']['webserver-module'])) >checked="checked"</if>/> mod_access
		</td>
	</tr>
	<tr>
		<td class="main_field_name" valign="top"><b>{$lng['aps']['settings_webserver_misc']}:</b></td>
		<td class="main_field_display" nowrap="nowrap">
			<input type="checkbox" name="htaccess" value="1" <if in_array('htaccess', explode(',', $settings['aps']['webserver-htaccess'])) >checked="checked"</if>/> .htaccess<br/>
			<input type="checkbox" name="fastcgi" value="1" <if in_array('fcgid-any', explode(',', $settings['aps']['webserver-module'])) >checked="checked"</if>/> FastCGI/mod_fcgid
		</td>
	</tr>
	<tr>
		<td class="maintitle_apply_right" nowrap="nowrap" colspan="2">
			<if $_part == 'aps'>
				<input type="hidden" name="part" value="aps" />
			</if>
			<input class="bottom" type="reset" value="{$lng['panel']['reset']}" />&nbsp;<input class="bottom" type="submit" value="{$lng['panel']['save']}" />
		</td>
	</tr>
</table>