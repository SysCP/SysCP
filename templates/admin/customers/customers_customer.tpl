     <tr>
      <td class="maintable"><a href="$filename?s=$s&page=$page&action=su&id={$row['customerid']}" target="_blank">{$row['loginname']}</a></td>
      <if $userinfo['customers_see_all']><td class="maintable">{$row['adminname']}</td></if>
      <td class="maintable">{$row['name']}<br />{$row['firstname']}</td>
      <td class="maintable">{$row['domains']}</td>
      <td class="maintable"><span<if $row['diskspace'] < $row['diskspace_used'] && $row['diskspace'] != 'UL'> style="color:red"</if>>{$row['diskspace_used']}/{$row['diskspace']}</span> (MB)<br /><span<if $row['traffic'] < $row['traffic_used'] && $row['traffic'] != 'UL'> style="color:red"</if>>{$row['traffic_used']}/{$row['traffic']}</span> (GB)</td>
      <td class="maintable">{$row['mysqls_used']}/{$row['mysqls']}<br />{$row['ftps_used']}/{$row['ftps']}</td>
      <td class="maintable">{$row['emails_used']}/{$row['emails']}<br />{$row['subdomains_used']}/{$row['subdomains']}</td>
      <td class="maintable">{$row['email_accounts_used']}/{$row['email_accounts']}<br />{$row['email_forwarders_used']}/{$row['email_forwarders']}</td>
      <td class="maintable">{$row['deactivated']}</td>
      <td class="maintable"><a href="$filename?s=$s&page=$page&action=delete&id={$row['customerid']}">{$lng['panel']['delete']}</a><br /><a href="$filename?s=$s&page=$page&action=edit&id={$row['customerid']}">{$lng['panel']['edit']}</a></td>
     </tr>