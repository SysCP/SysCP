$header
    <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
     <tr>
      <td colspan="2" class="title"><b>{$lng['admin']['ressourcedetails']}</b></td>
     </tr>
     <tr>
      <td class="maintable" align="right">{$lng['admin']['customers']}:</td>
      <td class="maintable">{$overview['number_customers']}</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{$lng['customer']['domains']}:</td>
      <td class="maintable">{$overview['number_domains']}</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{$lng['customer']['diskspace']}:</td>
      <td class="maintable">{$overview['diskspace_used']}</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{$lng['customer']['traffic']}:</td>
      <td class="maintable">{$overview['traffic_used']}</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{$lng['customer']['mysqls']}:</td>
      <td class="maintable">{$overview['mysqls_used']}</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{$lng['customer']['emails']}:</td>
      <td class="maintable">{$overview['emails_used']}</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{$lng['customer']['accounts']}:</td>
      <td class="maintable">{$overview['email_accounts_used']}</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{$lng['customer']['forwarders']}:</td>
      <td class="maintable">{$overview['email_forwarders_used']}</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{$lng['customer']['ftps']}:</td>
      <td class="maintable">{$overview['ftps_used']}</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{$lng['customer']['subdomains']}:</td>
      <td class="maintable">{$overview['subdomains_used']}</td>
     </tr>
     <tr>
      <td colspan="2" class="title"><b>{$lng['admin']['systemdetails']}</b></td>
     </tr>
     <tr>
      <td class="maintable" align="right">Serversoftware:</td>
      <td class="maintable">{$_SERVER['SERVER_SOFTWARE']}</td>
     </tr>
     <tr>
      <td class="maintable" align="right">PHP-Version:</td>
      <td class="maintable">$phpversion</td>
     </tr>
     <tr>
      <td class="maintable" align="right">PHP-Memory-Limit:</td>
      <td class="maintable">$phpmemorylimit</td>
     </tr>
     <tr>
      <td class="maintable" align="right">MySQL Server Version:</td>
      <td class="maintable">$mysqlserverversion</td>
     </tr>
     <tr>
      <td class="maintable" align="right">MySQL Client Version:</td>
      <td class="maintable">$mysqlclientversion</td>
     </tr>
     <tr>
      <td class="maintable" align="right">Webserver Interface:</td>
      <td class="maintable">$webserverinterface</td>
     </tr>
     <tr>
      <td colspan="2" class="title"><b>{$lng['admin']['syscpdetails']}</b></td>
     </tr>
     <tr>
      <td class="maintable" align="right">{$lng['admin']['installedversion']}:</td>
      <td class="maintable">$version</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{$lng['admin']['latestversion']}:</td>
      <td class="maintable"><a href="$lookfornewversion_link">$lookfornewversion_lable</a></td>
     </tr>
    </table>
$footer
