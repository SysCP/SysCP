     <tr>
      <td class="maintable"><font size="-1">{$row['loginname']}</font></td>
      <td class="maintable"><font size="-1">{$row['name']}</font></td>
      <td class="maintable"><font size="-1">{$row['customers_used']}/{$row['customers']}</font></td>
      <td class="maintable"><font size="-1">{$row['domains_used']}/{$row['domains']}</font></td>
      <td class="maintable"><font size="-1">{$row['diskspace_used']}/{$row['diskspace']}</font></td>
      <td class="maintable"><font size="-1">{$row['traffic_used']}/{$row['traffic']}</font></td>
      <td class="maintable"><font size="-1">{$row['mysqls_used']}/{$row['mysqls']}</font></td>
      <td class="maintable"><font size="-1">{$row['emails_used']}/{$row['emails']}</font></td>
      <td class="maintable"><font size="-1">{$row['email_forwarders_used']}/{$row['email_forwarders']}</font></td>
      <td class="maintable"><font size="-1">{$row['ftps_used']}/{$row['ftps']}</font></td>
      <td class="maintable"><font size="-1">{$row['deactivated']}</font></td>
      <td class="maintable"><a href="$filename?s=$s&page=$page&action=delete&id={$row['adminid']}">{$lng['panel']['delete']}</a></td>
      <td class="maintable"><a href="$filename?s=$s&page=$page&action=edit&id={$row['adminid']}">{$lng['panel']['edit']}</a></td>
     </tr>