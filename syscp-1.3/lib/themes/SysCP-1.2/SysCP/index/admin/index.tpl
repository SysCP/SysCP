    <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
     <tr>
      <td colspan="2" class="title"><b>{l10n get=SysCP.index.resource_details}</b></td>
     </tr>
     <tr>
      <td class="maintable" align="right">{l10n get=SysCP.index.customers}:</td>
      <td class="maintable">{$overview.number_customers} ({$User.customers})</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{l10n get=SysCP.index.domains}:</td>
      <td class="maintable">{$overview.number_domains} ({$User.domains})</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{l10n get=SysCP.index.diskspace}:</td>
      <td class="maintable">{$overview.diskspace_used} ({$User.diskspace_used}/{$User.diskspace})</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{l10n get=SysCP.index.traffic}:</td>
      <td class="maintable">{$overview.traffic_used} ({$User.traffic_used}/{$User.traffic})</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{l10n get=SysCP.index.mysqls}:</td>
      <td class="maintable">{$overview.mysqls_used} ({$User.mysqls_used}/{$User.mysqls})</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{l10n get=SysCP.index.emails}:</td>
      <td class="maintable">{$overview.emails_used} ({$User.emails_used}/{$User.emails})</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{l10n get=SysCP.index.accounts}:</td>
      <td class="maintable">{$overview.email_accounts_used} ({$User.email_accounts_used}/{$User.email_accounts})</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{l10n get=SysCP.index.forwarders}:</td>
      <td class="maintable">{$overview.email_forwarders_used} ({$User.email_forwarders_used}/{$User.email_forwarders})</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{l10n get=SysCP.index.ftps}:</td>
      <td class="maintable">{$overview.ftps_used} ({$User.ftps_used}/{$User.ftps})</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{l10n get=SysCP.index.subdomains}:</td>
      <td class="maintable">{$overview.subdomains_used} ({$User.subdomains_used}/{$User.subdomains})</td>
     </tr>
     <tr>
      <td colspan="2" class="title"><b>{l10n get=SysCP.index.system_details}</b></td>
     </tr>
     <tr>
      <td class="maintable" align="right">{l10n get=SysCP.index.server_software}:</td>
      <td class="maintable">{$serversoftware}</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{l10n get=SysCP.index.php_version}:</td>
      <td class="maintable">{$phpversion}</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{l10n get=SysCP.index.php_memlimit}:</td>
      <td class="maintable">{$phpmemorylimit}</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{l10n get=SysCP.index.mysql_server}:</td>
      <td class="maintable">{$mysqlserverversion}</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{l10n get=SysCP.index.mysql_client}:</td>
      <td class="maintable">{$mysqlclientversion}</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{l10n get=SysCP.index.php_sapi}:</td>
      <td class="maintable">{$webserverinterface}</td>
     </tr>
     <tr>
      <td colspan="2" class="title"><b>{l10n get=SysCP.index.syscp_details}</b></td>
     </tr>
     <tr>
      <td class="maintable" align="right">{l10n get=SysCP.index.cron_lastrun}:</td>
      <td class="maintable">{$cronlastrun}</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{l10n get=SysCP.index.installed_version}:</td>
      <td class="maintable">{$Config->get('env.version')}</td>
     </tr>
    </table>