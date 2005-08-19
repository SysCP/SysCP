$header
    <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
     <tr>
      <td colspan="2" class="title"><b>{$lng['index']['customerdetails']}</b></td>
     </tr>
     <tr>
      <td class="maintable" align="right">{$lng['customer']['name']}:</td>
      <td class="maintable">{$userinfo['firstname']} {$userinfo['name']}</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{$lng['customer']['company']}:</td>
      <td class="maintable">{$userinfo['company']}</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{$lng['customer']['street']}:</td>
      <td class="maintable">{$userinfo['street']}</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{$lng['customer']['zipcode']}/{$lng['customer']['city']}:</td>
      <td class="maintable">{$userinfo['zipcode']} {$userinfo['city']}</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{$lng['customer']['email']}:</td>
      <td class="maintable">{$userinfo['email']}</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{$lng['customer']['customernumber']}:</td>
      <td class="maintable">{$userinfo['customernumber']}</td>
     </tr>
     <tr>
      <td colspan="2" class="title"><b>{$lng['index']['accountdetails']}</b></td>
     </tr>
     <tr>
      <td class="maintable" align="right">{$lng['login']['username']}:</td>
      <td class="maintable">{$userinfo['loginname']}</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{$lng['customer']['domains']}:</td>
      <td class="maintable">$domains</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{$lng['customer']['subdomains']}:</td>
      <td class="maintable">{$userinfo['subdomains_used']} ({$userinfo['subdomains']})</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{$lng['customer']['diskspace']}:</td>
      <td class="maintable">{$userinfo['diskspace_used']} ({$userinfo['diskspace']})</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{$lng['customer']['traffic']} ($month):</td>
      <td class="maintable">{$userinfo['traffic_used']} ({$userinfo['traffic']})</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{$lng['customer']['emails']}:</td>
      <td class="maintable">{$userinfo['emails_used']} ({$userinfo['emails']})</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{$lng['customer']['accounts']}:</td>
      <td class="maintable">{$userinfo['email_accounts_used']} ({$userinfo['email_accounts']})</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{$lng['customer']['forwarders']}:</td>
      <td class="maintable">{$userinfo['email_forwarders_used']} ({$userinfo['email_forwarders']})</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{$lng['customer']['mysqls']}:</td>
      <td class="maintable">{$userinfo['mysqls_used']} ({$userinfo['mysqls']})</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{$lng['customer']['ftps']}:</td>
      <td class="maintable">{$userinfo['ftps_used']} ({$userinfo['ftps']})</td>
     </tr>
    </table>
$footer