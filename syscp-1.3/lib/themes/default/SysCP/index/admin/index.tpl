    <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
     <tr>
      <td colspan="2" class="title"><b>{l10n get=admin.ressourcedetails}</b></td>
     </tr>
     <tr>
      <td class="maintable" align="right">{l10n get=admin.customers}:</td>
      <td class="maintable">{$overview.number_customers} ({$User.customers})</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{l10n get=customer.domains}:</td>
      <td class="maintable">{$overview.number_domains} ({$User.domains})</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{l10n get=customer.diskspace}:</td>
      <td class="maintable">{$overview.diskspace_used} ({$User.diskspace_used}/{$User.diskspace})</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{l10n get=customer.traffic}:</td>
      <td class="maintable">{$overview.traffic_used} ({$User.traffic_used}/{$User.traffic})</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{l10n get=customer.mysqls}:</td>
      <td class="maintable">{$overview.mysqls_used} ({$User.mysqls_used}/{$User.mysqls})</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{l10n get=customer.emails}:</td>
      <td class="maintable">{$overview.emails_used} ({$User.emails_used}/{$User.emails})</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{l10n get=customer.accounts}:</td>
      <td class="maintable">{$overview.email_accounts_used} ({$User.email_accounts_used}/{$User.email_accounts})</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{l10n get=customer.forwarders}:</td>
      <td class="maintable">{$overview.email_forwarders_used} ({$User.email_forwarders_used}/{$User.email_forwarders})</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{l10n get=customer.ftps}:</td>
      <td class="maintable">{$overview.ftps_used} ({$User.ftps_used}/{$User.ftps})</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{l10n get=customer.subdomains}:</td>
      <td class="maintable">{$overview.subdomains_used} ({$User.subdomains_used}/{$User.subdomains})</td>
     </tr>
     <tr>
      <td colspan="2" class="title"><b>{l10n get=admin.systemdetails}</b></td>
     </tr>
     <tr>
      <td class="maintable" align="right">Serversoftware:</td>
      <td class="maintable">{$serversoftware}</td>
     </tr>
     <tr>
      <td class="maintable" align="right">PHP-Version:</td>
      <td class="maintable">{$phpversion}</td>
     </tr>
     <tr>
      <td class="maintable" align="right">PHP-Memory-Limit:</td>
      <td class="maintable">{$phpmemorylimit}</td>
     </tr>
     <tr>
      <td class="maintable" align="right">MySQL Server Version:</td>
      <td class="maintable">{$mysqlserverversion}</td>
     </tr>
     <tr>
      <td class="maintable" align="right">MySQL Client Version:</td>
      <td class="maintable">{$mysqlclientversion}</td>
     </tr>
     <tr>
      <td class="maintable" align="right">Webserver Interface:</td>
      <td class="maintable">{$webserverinterface}</td>
     </tr>
     <tr>
      <td colspan="2" class="title"><b>{l10n get=admin.syscpdetails}</b></td>
     </tr>
     <tr>
      <td class="maintable" align="right">{l10n get=admin.cronlastrun}:</td>
      <td class="maintable">{$cronlastrun}</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{l10n get=admin.installedversion}:</td>
      <td class="maintable">{$Config->get('env.version')}</td>
     </tr>
    </table>