    <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
     <tr>
      <td colspan="2" class="title"><b>{l10n get=index.customerdetails}</b></td>
     </tr>
     <tr>
      <td class="maintable" align="right">{l10n get=customer.name}:</td>
      <td class="maintable">{$User.firstname} {$User.name}</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{l10n get=customer.company}:</td>
      <td class="maintable">{$User.company}</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{l10n get=customer.street}:</td>
      <td class="maintable">{$User.street}</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{l10n get=customer.zipcode}/{l10n get=customer.city}:</td>
      <td class="maintable">{$User.zipcode} {$User.city}</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{l10n get=customer.email}:</td>
      <td class="maintable">{$User.email}</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{l10n get=customer.customernumber}:</td>
      <td class="maintable">{$User.customernumber}</td>
     </tr>
     <tr>
      <td colspan="2" class="title"><b>{l10n get=index.accountdetails}</b></td>
     </tr>
     <tr>
      <td class="maintable" align="right">{l10n get=login.username}:</td>
      <td class="maintable">{$User.loginname}</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{l10n get=customer.domains}:</td>
      <td class="maintable">{$domains}</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{l10n get=customer.subdomains}:</td>
      <td class="maintable">{$User.subdomains_used} ({$User.subdomains})</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{l10n get=customer.diskspace}:</td>
      <td class="maintable">{$User.diskspace_used} ({$User.diskspace})</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{l10n get=customer.traffic} ({$month}):</td>
      <td class="maintable">{$User.traffic_used} ({$User.traffic})</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{l10n get=customer.emails}:</td>
      <td class="maintable">{$User.emails_used} ({$User.emails})</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{l10n get=customer.accounts}:</td>
      <td class="maintable">{$User.email_accounts_used} ({$User.email_accounts})</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{l10n get=customer.forwarders}:</td>
      <td class="maintable">{$User.email_forwarders_used} ({$User.email_forwarders})</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{l10n get=customer.mysqls}:</td>
      <td class="maintable">{$User.mysqls_used} ({$User.mysqls})</td>
     </tr>
     <tr>
      <td class="maintable" align="right">{l10n get=customer.ftps}:</td>
      <td class="maintable">{$User.ftps_used} ({$User.ftps})</td>
     </tr>
    </table>