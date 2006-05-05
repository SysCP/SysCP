     <tr>
      <td class="maintable">{$row['loginname']}</td>
      <td class="maintable">{$row['name']}</td>
      <td class="maintable">{$row['customers_used']}/{$row['customers']}<br />{$row['domains_used']}/{$row['domains']}</td>
      <td class="maintable">{$row['diskspace_used']}/{$row['diskspace']} (MB)<br />{$row['traffic_used']}/{$row['traffic']} (GB)</td>
      <td class="maintable">{$row['mysqls_used']}/{$row['mysqls']}<br />{$row['ftps_used']}/{$row['ftps']}</td>
      <td class="maintable">{$row['emails_used']}/{$row['emails']}<br />{$row['subdomains_used']}/{$row['subdomains']}</td>
      <td class="maintable">{$row['email_accounts_used']}/{$row['email_accounts']}<br />{$row['email_forwarders_used']}/{$row['email_forwarders']}</td>
      <td class="maintable">{$row['deactivated']}</td>
      <td class="maintable"><a href="{$config->get('env.filename')}?s={$config->get('env.s')}&page={$config->get('env.page')}&action=delete&id={$row['adminid']}">{$lng['panel']['delete']}</a><br /><a href="{$config->get('env.filename')}?s={$config->get('env.s')}&page={$config->get('env.page')}&action=edit&id={$row['adminid']}">{$lng['panel']['edit']}</a></td>
     </tr>