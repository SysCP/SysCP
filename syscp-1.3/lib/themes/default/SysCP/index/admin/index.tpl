	<table cellpadding="5" cellspacing="0" border="0" align="center" class="maintable">
		<tr>
			<td colspan="2" class="maintitle">
				<img src="{$imagedir}title.gif" alt="" />&nbsp;{l10n get=SysCP.index.resource_details}
			</td>
		</tr>
		<tr>
			<td class="field_name_border_left">{l10n get=SysCP.index.customers}:</td>
			<td class="field_display">{$overview.number_customers} ({$User.customers})</td>
		</tr>
		<tr>
			<td class="field_name_border_left">{l10n get=SysCP.index.domains}:</td>
			<td class="field_display">{$overview.number_domains} ({$User.domains})</td>
		</tr>
		<tr>
			<td class="field_name_border_left">{l10n get=SysCP.index.diskspace}:</td>
			<td class="field_display">{$overview.diskspace_used} ({$User.diskspace_used}/{$User.diskspace})</td>
		</tr>
		<tr>
			<td class="field_name_border_left">{l10n get=SysCP.index.traffic}:</td>
			<td class="field_display">{$overview.traffic_used} ({$User.traffic_used}/{$User.traffic})</td>
		</tr>
		<tr>
			<td class="field_name_border_left">{l10n get=SysCP.index.mysqls}:</td>
			<td class="field_display">{$overview.mysqls_used} ({$User.mysqls_used}/{$User.mysqls})</td>
		</tr>
		<tr>
			<td class="field_name_border_left">{l10n get=SysCP.index.emails}:</td>
			<td class="field_display">{$overview.emails_used} ({$User.emails_used}/{$User.emails})</td>
		</tr>
		<tr>
			<td class="field_name_border_left">{l10n get=SysCP.index.accounts}:</td>
			<td class="field_display">{$overview.email_accounts_used} ({$User.email_accounts_used}/{$User.email_accounts})</td>
		</tr>
		<tr>
			<td class="field_name_border_left">{l10n get=SysCP.index.forwarders}:</td>
			<td class="field_display">{$overview.email_forwarders_used} ({$User.email_forwarders_used}/{$User.email_forwarders})</td>
		</tr>
		<tr>
			<td class="field_name_border_left">{l10n get=SysCP.index.ftps}:</td>
			<td class="field_display">{$overview.ftps_used} ({$User.ftps_used}/{$User.ftps})</td>
		</tr>
		<tr>
			<td class="field_name_border_left">{l10n get=SysCP.index.subdomains}:</td>
			<td class="field_display">{$overview.subdomains_used} ({$User.subdomains_used}/{$User.subdomains})</td>
		</tr>
		<tr>
			<td colspan="2" class="maintitle">
				<img src="{$imagedir}title.gif" alt="" />&nbsp;{l10n get=SysCP.index.system_details}
			</td>
		</tr>
		<tr>
			<td class="field_name_border_left">{l10n get=SysCP.index.server_software}:</td>
			<td class="field_display">{$serversoftware}</td>
		</tr>
		<tr>
			<td class="field_name_border_left">{l10n get=SysCP.index.php_version}:</td>
			<td class="field_display">{$phpversion}</td>
		</tr>
		<tr>
			<td class="field_name_border_left">{l10n get=SysCP.index.php_memlimit}:</td>
			<td class="field_display">{$phpmemorylimit}</td>
		</tr>
		<tr>
			<td class="field_name_border_left">{l10n get=SysCP.index.mysql_server}:</td>
			<td class="field_display">{$mysqlserverversion}</td>
		</tr>
		<tr>
			<td class="field_name_border_left">{l10n get=SysCP.index.mysql_client}:</td>
			<td class="field_display">{$mysqlclientversion}</td>
		</tr>
		<tr>
			<td class="field_name_border_left">{l10n get=SysCP.index.php_sapi}:</td>
			<td class="field_display">{$webserverinterface}</td>
		</tr>
		<tr>
			<td colspan="2" class="maintitle">
				<img src="{$imagedir}title.gif" alt="" />&nbsp;{l10n get=SysCP.index.syscp_details}
			</td>
		</tr>
		<tr>
			<td class="field_name_border_left">{l10n get=SysCP.index.cron_lastrun}:</td>
			<td class="field_display">{$cronlastrun}</td>
		</tr>
		<tr>
			<td class="field_name_border_left">{l10n get=SysCP.index.installed_version}:</td>
			<td class="field_display">{$Config->get('env.version')}</td>
		</tr>
	</table>