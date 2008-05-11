$header
	<form method="post" action="$filename">
		<input type="hidden" name="send" value="send" />
		<input type="hidden" name="s" value="$s" />
		<input type="hidden" name="page" value="$page" />
		<input type="hidden" name="action" value="$action" />
		<table cellpadding="5" cellspacing="4" border="0" align="center" class="maintable">
			<tr>
				<td class="maintitle_apply_left">
					<b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['panelsettings']}</b>
				</td>
				<td class="maintitle_apply_right" nowarp="nowrap">
					<input class="bottom" type="reset" value="{$lng['panel']['reset']}" /><input class="bottom" type="submit" value="{$lng['panel']['save']}" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['login']['language']}:</b><br />{$lng['serversettings']['language']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><select class="dropdown_noborder" name="panel_standardlanguage">$languages</select></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['natsorting']['title']}:</b><br />{$lng['serversettings']['natsorting']['description']}</td>
				<td class="main_field_display" nowrap="nowrap">{$natsorting}</td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['no_robots']['title']}:</b></td>
				<td class="main_field_display" nowrap="nowrap">{$no_robots}</td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['paging']['title']}:</b><br />{$lng['serversettings']['paging']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="panel_paging" value="{$settings['panel']['paging']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['pathedit']['title']}:</b><br />{$lng['serversettings']['pathedit']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><select class="dropdown_noborder" name="panel_pathedit">$pathedit</select></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['adminmail']['title']}:</b><br />{$lng['serversettings']['adminmail']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="panel_adminmail" value="{$settings['panel']['adminmail']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['phpmyadmin_url']['title']}:</b><br />{$lng['serversettings']['phpmyadmin_url']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="panel_phpmyadmin_url" value="{$settings['panel']['phpmyadmin_url']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['webmail_url']['title']}:</b><br />{$lng['serversettings']['webmail_url']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="panel_webmail_url" value="{$settings['panel']['webmail_url']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['webftp_url']['title']}:</b><br />{$lng['serversettings']['webftp_url']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="panel_webftp_url" value="{$settings['panel']['webftp_url']}" /></td>
			</tr>
			<tr>
				<td class="maintitle_apply_left">
					<b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['accountsettings']}</b>
				</td>
				<td class="maintitle_apply_right" nowrap="nowrap">
					<input class="bottom" type="reset" value="{$lng['panel']['reset']}" /><input class="bottom" type="submit" value="{$lng['panel']['save']}" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['session_timeout']['title']}:</b><br />{$lng['serversettings']['session_timeout']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="session_sessiontimeout" value="{$settings['session']['sessiontimeout']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['maxloginattempts']['title']}:</b><br />{$lng['serversettings']['maxloginattempts']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="login_maxloginattempts" value="{$settings['login']['maxloginattempts']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['deactivatetime']['title']}:</b><br />{$lng['serversettings']['deactivatetime']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="login_deactivatetime" value="{$settings['login']['deactivatetime']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['accountprefix']['title']}:</b><br />{$lng['serversettings']['accountprefix']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="customer_accountprefix" value="{$settings['customer']['accountprefix']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['mysqlprefix']['title']}:</b><br />{$lng['serversettings']['mysqlprefix']['description']} ({$settings['customer']['accountprefix']}X{$settings['customer']['mysqlprefix']}Y)</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="customer_mysqlprefix" value="{$settings['customer']['mysqlprefix']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['ftpprefix']['title']}:</b><br />{$lng['serversettings']['ftpprefix']['description']} ({$settings['customer']['accountprefix']}X{$settings['customer']['ftpprefix']}Y)</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="customer_ftpprefix" value="{$settings['customer']['ftpprefix']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['ftpdomain']['title']}:</b><br />{$lng['serversettings']['ftpdomain']['description']}</td>
				<td class="main_field_display" nowrap="nowrap">{$ftpatdomain}</td>
			</tr>
			<tr>
				<td class="maintitle_apply_left">
					<b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['systemsettings']}</b>
				</td>
				<td class="maintitle_apply_right" nowrap="nowrap">
					<input class="bottom" type="reset" value="{$lng['panel']['reset']}" /><input class="bottom" type="submit" value="{$lng['panel']['save']}" />
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
					<b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['webserversettings']}</b>
				</td>
				<td class="maintitle_apply_right" nowrap="nowrap">
					<input class="bottom" type="reset" value="{$lng['panel']['reset']}" /><input class="bottom" type="submit" value="{$lng['panel']['save']}" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['apacheconf_vhost']['title']}:</b><br />{$lng['serversettings']['apacheconf_vhost']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="system_apacheconf_vhost" value="{$settings['system']['apacheconf_vhost']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['apacheconf_diroptions']['title']}:</b><br />{$lng['serversettings']['apacheconf_diroptions']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="system_apacheconf_diroptions" value="{$settings['system']['apacheconf_diroptions']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['apacheconf_htpasswddir']['title']}:</b><br />{$lng['serversettings']['apacheconf_htpasswddir']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="system_apacheconf_htpasswddir" value="{$settings['system']['apacheconf_htpasswddir']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['apachereload_command']['title']}:</b><br />{$lng['serversettings']['apachereload_command']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="system_apachereload_command" value="{$settings['system']['apachereload_command']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['mod_log_sql']['title']}:</b><br />{$lng['serversettings']['mod_log_sql']['description']}</td>
				<td class="main_field_display" nowrap="nowrap">{$system_modlogsql}</td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['logfiles_directory']['title']}:</b><br />{$lng['serversettings']['logfiles_directory']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="system_logfiles_directory" value="{$settings['system']['logfiles_directory']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['mod_fcgid']['title']}:</b><br />{$lng['serversettings']['mod_fcgid']['description']}</td>
				<td class="main_field_display" nowrap="nowrap">{$system_modfcgid}</td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['mod_fcgid']['configdir']}:</b><br />{$lng['serversettings']['mod_fcgid']['configdir_desc']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="system_mod_fcgid_configdir" value="{$settings['system']['mod_fcgid_configdir']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['mod_fcgid']['tmpdir']}:</b></td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="system_mod_fcgid_tmpdir" value="{$settings['system']['mod_fcgid_tmpdir']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['phpappendopenbasedir']['title']}:</b><br />{$lng['serversettings']['phpappendopenbasedir']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="system_phpappendopenbasedir" value="{$settings['system']['phpappendopenbasedir']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['deactivateddocroot']['title']}:</b><br />{$lng['serversettings']['deactivateddocroot']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="system_deactivateddocroot" value="{$settings['system']['deactivateddocroot']}" /></td>
			</tr>
			<tr>
				<td class="maintitle_apply_left">
					<b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['webalizersettings']}</b>
				</td>
				<td class="maintitle_apply_right" nowrap="nowrap">
					<input class="bottom" type="reset" value="{$lng['panel']['reset']}" /><input class="bottom" type="submit" value="{$lng['panel']['save']}" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['webalizer_quiet']['title']}:</b><br />{$lng['serversettings']['webalizer_quiet']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><select class="dropdown_noborder" name="system_webalizer_quiet">$webalizer_quiet</select></td>
            </tr>
			<tr>
				<td class="maintitle_apply_left">
					<b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['mailserversettings']}</b>
				</td>
				<td class="maintitle_apply_right" nowrap="nowrap">
					<input class="bottom" type="reset" value="{$lng['panel']['reset']}" /><input class="bottom" type="submit" value="{$lng['panel']['save']}" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['vmail_uid']['title']}:</b><br />{$lng['serversettings']['vmail_uid']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="system_vmail_uid" value="{$settings['system']['vmail_uid']}" maxlength="5" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['vmail_gid']['title']}:</b><br />{$lng['serversettings']['vmail_gid']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="system_vmail_gid" value="{$settings['system']['vmail_gid']}" maxlength="5" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['vmail_homedir']['title']}:</b><br />{$lng['serversettings']['vmail_homedir']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="system_vmail_homedir" value="{$settings['system']['vmail_homedir']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['mailpwcleartext']['title']}:</b><br />{$lng['serversettings']['mailpwcleartext']['description']}<br /><a href="$filename?page=wipecleartextmailpws&amp;s=$s">{$lng['serversettings']['mailpwcleartext']['removelink']}</a></td>
				<td class="main_field_display" nowrap="nowrap">{$mailpwcleartext}</td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['sendalternativemail']['title']}:</b><br />{$lng['serversettings']['sendalternativemail']['description']}</td>
				<td class="main_field_display" nowrap="nowrap">{$panel_sendalternativemail}</td>
			</tr>
			<tr>
				<td class="maintitle_apply_left">
					<b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['nameserversettings']}</b>
				</td>
				<td class="maintitle_apply_right" nowrap="nowrap">
					<input class="bottom" type="reset" value="{$lng['panel']['reset']}" /><input class="bottom" type="submit" value="{$lng['panel']['save']}" />
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
					<b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['loggersettings']}</b>
				</td>
				<td class="maintitle_apply_right" nowrap="nowrap">
					<input class="bottom" type="reset" value="{$lng['panel']['reset']}" /><input class="bottom" type="submit" value="{$lng['panel']['save']}" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['logger']['enable']}:</b></td>
				<td class="main_field_display" nowrap="nowrap">$loggingenabled</td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['logger']['severity']}:</b></td>
				<td class="main_field_display" nowrap="nowrap"><select class="dropdown_noborder" name="logger_severity">$loggingseverity</select></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['logger']['types']}:</b><br />{$lng['serversettings']['logger']['types_desc']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="logger_logtypes" value="{$settings['logger']['logtypes']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['logger']['logfile']}:</b></td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="logger_logfile" value="{$settings['logger']['logfile']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['logger']['logcron']}:</b></td>
				<td class="main_field_display" nowrap="nowrap">$logginglogcron</td>
			</tr>      
			<tr>
				<td class="maintitle_apply_left">
					<b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['ticketsettings']}</b>
				</td>
				<td class="maintitle_apply_right" nowrap="nowrap">
					<input class="bottom" type="reset" value="{$lng['panel']['reset']}" /><input class="bottom" type="submit" value="{$lng['panel']['save']}" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['ticket']['enable']}:</b></td>
				<td class="main_field_display" nowrap="nowrap">$ticketsystemenabled</td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['ticket']['noreply_email']}:</b><br />{$lng['serversettings']['ticket']['noreply_email_desc']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="ticket_noreply_email" value="{$settings['ticket']['noreply_email']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['ticket']['noreply_name']}:</b></td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="ticket_noreply_name" value="{$settings['ticket']['noreply_name']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['ticket']['reset_cycle']}:</b><br />{$lng['serversettings']['ticket']['reset_cycle_desc']}</td>
				<td class="main_field_display" nowrap="nowrap"><select class="dropdown_noborder" name="ticket_reset_cycle">{$ticket_reset_cycle}</select></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['ticket']['concurrentlyopen']}:</b></td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="ticket_concurrently_open" value="{$settings['ticket']['concurrently_open']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['ticket']['archiving_days']}:</b></td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="ticket_archiving_days" value="{$settings['ticket']['archiving_days']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['ticket']['worktime_all']}:</b><br />{$lng['serversettings']['ticket']['worktime_all_desc']}</td>
				<td class="main_field_display" nowrap="nowrap">{$ticket_worktime_all}</td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['ticket']['worktime_begin']}:</b><br />{$lng['serversettings']['ticket']['worktime_begin_desc']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="ticket_worktime_begin" value="{$settings['ticket']['worktime_begin']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['ticket']['worktime_end']}:</b><br />{$lng['serversettings']['ticket']['worktime_end_desc']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="ticket_worktime_end" value="{$settings['ticket']['worktime_end']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['ticket']['worktime_sat']}:</b></td>
				<td class="main_field_display" nowrap="nowrap">{$ticket_worktime_sat}</td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['ticket']['worktime_sun']}:</b></td>
				<td class="main_field_display" nowrap="nowrap">{$ticket_worktime_sun}</td>
			</tr>
                        <tr>
                                <td class="maintitle_apply_left">
                                        <b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['sslsettings']}</b>
                                </td>
                                <td class="maintitle_apply_right" nowrap="nowrap">
                                        <input class="bottom" type="reset" value="{$lng['panel']['reset']}" /><input class="bottom" type="submit" value="{$lng['panel']['save']}" />
                                </td>
                        </tr>
                        <tr>
                                <td class="main_field_name"><b>{$lng['serversettings']['ssl']['use_ssl']}:</b></td>
                                <td class="main_field_display" nowrap="nowrap">$ssl_enabled</td>
                        </tr>
                        <tr>
                                <td class="main_field_name"><b>{$lng['serversettings']['ssl']['ssl_cert_file']}:</b></td>
                                <td class="main_field_display" nowrap="nowrap"><input type="text" name="ssl_cert_file" value="{$settings['system']['ssl_cert_file']}" /></td>
                        </tr>
                        <tr>
                                <td class="main_field_name"><b>{$lng['serversettings']['ssl']['openssl_cnf']}:</b></td>
                                <td class="main_field_display" nowrap="nowrap"><textarea class="textarea_border" rows="12" cols="40" name="openssl_cnf">{$settings['system']['openssl_cnf']}</textarea></td>
                        </tr>
		</table>
	</form>
	<br />
	<br />
$footer
