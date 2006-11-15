	<form method="post" action="{url module=settings action=edit}">
		<table cellpadding="5" cellspacing="4" border="0" align="center" class="maintable">
			<tr>
				<td colspan="2" class="maintitle">
					<img src="{$imagedir}title.gif" alt="" />&nbsp;{l10n get=SysCP.settings.settings} {l10n get=SysCP.globallang.edit}
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					<b>{l10n get=SysCP.settings.session_timeout_title}:</b><br />
					{l10n get=SysCP.settings.session_timeout_desc}
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="session_sessiontimeout" value="{$Config->get('session.sessiontimeout')}" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					<b>{l10n get=SysCP.settings.maxloginattempts_title}:</b><br />
					{l10n get=SysCP.settings.maxloginattempts_desc}
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="login_maxloginattempts" value="{$Config->get('login.maxloginattempts')}" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					<b>{l10n get=SysCP.settings.deactivatetime_title}:</b><br />
					{l10n get=SysCP.settings.deactivatetime_desc}
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="login_deactivatetime" value="{$Config->get('login.deactivatetime')}" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					<b>{l10n get=SysCP.settings.accountprefix_title}:</b><br />
					{l10n get=SysCP.settings.accountprefix_desc}
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="customer_accountprefix" value="{$Config->get('customer.accountprefix')}" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					<b>{l10n get=SysCP.settings.sqlprefix_title}:</b><br />
					{l10n get=SysCP.settings.sqlprefix_desc} ({$Config->get('customer.accountprefix')}X{$Config->get('customer.mysqlprefix')}Y)
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="customer_mysqlprefix" value="{$Config->get('customer.mysqlprefix')}" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					<b>{l10n get=SysCP.settings.ftpprefix_title}:</b><br />
					{l10n get=SysCP.settings.ftpprefix_desc} ({$Config->get('customer.accountprefix')}X{$Config->get('customer.ftpprefix')}Y)
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="customer_ftpprefix" value="{$Config->get('customer.ftpprefix')}" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					<b>{l10n get=SysCP.settings.documentroot_prefix_title}:</b><br />
					{l10n get=SysCP.settings.documentroot_prefix_desc}
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="system_documentroot_prefix" value="{$Config->get('system.documentroot_prefix')}" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					<b>{l10n get=SysCP.settings.user_homedir_title}:</b><br />
					{l10n get=SysCP.settings.user_homedir_desc}
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="system_user_homedir" value="{$Config->get('system.user_homedir')}" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					<b>{l10n get=SysCP.settings.apache_access_log_title}:</b><br />
					{l10n get=SysCP.settings.apache_access_log_desc}
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="system_apache_access_log" value="{$Config->get('system.apache_access_log')}" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					<b>{l10n get=SysCP.settings.apache_error_log_title}:</b><br />
					{l10n get=SysCP.settings.apache_error_log_desc}
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="system_apache_error_log" value="{$Config->get('system.apache_error_log')}" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					<b>{l10n get=SysCP.settings.ipaddress_title}:</b><br />
					{l10n get=SysCP.settings.ipaddress_desc}
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<select class="dropdown_noborder" name="system_ipaddress">
						{html_options options=$system_ipaddress selected=$Config->get('system.ipaddress')}
					</select>
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					<b>{l10n get=SysCP.settings.hostname_title}:</b><br />
					{l10n get=SysCP.settings.hostname_desc}
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="system_hostname" value="{$Config->get('system.hostname')}" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					<b>{l10n get=SysCP.settings.apacheconf_directory_title}:</b><br />
					{l10n get=SysCP.settings.apacheconf_directory_desc}
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="system_apacheconf_directory" value="{$Config->get('system.apacheconf_directory')}" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					<b>{l10n get=SysCP.settings.apacheconf_filename_title}:</b><br />
					{l10n get=SysCP.settings.apacheconf_filename_desc}
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="system_apacheconf_filename" value="{$Config->get('system.apacheconf_filename')}" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					<b>{l10n get=SysCP.settings.apachereload_command_title}:</b><br />
					{l10n get=SysCP.settings.apachereload_command_desc}
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="system_apachereload_command" value="{$Config->get('system.apachereload_command')}" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					<b>{l10n get=SysCP.settings.bindconf_directory_title}:</b><br />
					{l10n get=SysCP.settings.bindconf_directory_desc}
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="system_bindconf_directory" value="{$Config->get('system.bindconf_directory')}" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					<b>{l10n get=SysCP.settings.bindreload_command_title}:</b><br />
					{l10n get=SysCP.settings.bindreload_command_desc}
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="system_bindreload_command" value="{$Config->get('system.bindreload_command')}" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					<b>{l10n get=SysCP.settings.binddefaultzone_title}:</b><br />
					{l10n get=SysCP.settings.binddefaultzone_desc}
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="system_binddefaultzone" value="{$Config->get('system.binddefaultzone')}" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					<b>{l10n get=SysCP.settings.vmail_uid_title}:</b><br />
					{l10n get=SysCP.settings.vmail_uid_desc}
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="system_vmail_uid" value="{$Config->get('system.vmail_uid')}" maxlength="5" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					<b>{l10n get=SysCP.settings.vmail_gid_title}:</b><br />
					{l10n get=SysCP.settings.vmail_gid_desc}
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="system_vmail_gid" value="{$Config->get('system.vmail_gid')}" maxlength="5" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					<b>{l10n get=SysCP.settings.vmail_homedir_title}:</b><br />
					{l10n get=SysCP.settings.vmail_homedir_desc}
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="system_vmail_homedir" value="{$Config->get('system.vmail_homedir')}" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					<b>{l10n get=SysCP.settings.adminmail_title}:</b><br />
					{l10n get=SysCP.settings.adminmail_desc}
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="panel_adminmail" value="{$Config->get('panel.adminmail')}" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					<b>{l10n get=SysCP.settings.paging_title}:</b><br />
					{l10n get=SysCP.settings.paging_desc}
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="panel_paging" value="{$Config->get('panel.paging')}" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					<b>{l10n get=SysCP.settings.language_title}:</b><br />
					{l10n get=SysCP.settings.language_desc}
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<select class="dropdown_noborder" name="panel_standardlanguage">
						{html_options options=$lang_list selected=$Config->get('panel.standardlanguage')}
					</select>
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					<b>{l10n get=SysCP.settings.pathedit_title}:</b><br />
					{l10n get=SysCP.settings.pathedit_desc}
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<select class="dropdown_noborder" name="panel_pathedit">
						{html_options options=$pathedit selected=$Config->get('panel.pathedit')}
					</select>
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					<b>{l10n get=serversettings.customerpathedit.title}:</b><br />
					{l10n get=serversettings.customerpathedit.description}
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<select name="panel_customerpathedit">
						{html_options options=$customerpathedit selected=$Config->get('panel.customerpathedit')}
					</select>
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					<b>{l10n get=SysCP.settings.phpmyadmin_url_title}:</b><br />
					{l10n get=SysCP.settings.phpmyadmin_url_desc}
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="panel_phpmyadmin_url" value="{$Config->get('panel.phpmyadmin_url')}" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					<b>{l10n get=SysCP.settings.webmail_url_title}:</b><br />
					{l10n get=SysCP.settings.webmail_url_desc}
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="panel_webmail_url" value="{$Config->get('panel.webmail_url')}" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					<b>{l10n get=SysCP.settings.webftp_url_title}:</b><br />
					{l10n get=SysCP.settings.webftp_url_desc}
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="panel_webftp_url" value="{$Config->get('panel.webftp_url')}" />
				</td>
			</tr>
			<tr>
				<td class="main_field_confirm" colspan="2">
					<input type="hidden" name="send" value="send" />
					<input class="bottom" type="submit" value="{l10n get=SysCP.globallang.save}" />
				</td>
			</tr>
		</table>
	</form>
