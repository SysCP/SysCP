     <tr>
      <td class="maintable"><font size="-1">{$row['loginname']}</font></td>
      <td class="maintable"><font size="-1">{$row['name']} {$row['surname']}</font></td>
      <td class="maintable"><font size="-1"<if $row['diskspace'] < $row['diskspace_used'] && $row['diskspace'] != 'UL'> color="red"</if>>{$row['diskspace_used']}/{$row['diskspace']}</font></td>
      <td class="maintable"><font size="-1"<if $row['traffic'] < $row['traffic_used'] && $row['traffic'] != 'UL'> color="red"</if>>{$row['traffic_used']}/{$row['traffic']}</font></td>
      <td class="maintable"><font size="-1">{$row['mysqls_used']}/{$row['mysqls']}</font></td>
      <td class="maintable"><font size="-1">{$row['emails_used']}/{$row['emails']}</font></td>
      <td class="maintable"><font size="-1">{$row['email_forwarders_used']}/{$row['email_forwarders']}</font></td>
      <td class="maintable"><font size="-1">{$row['ftps_used']}/{$row['ftps']}</font></td>
      <td class="maintable"><font size="-1">{$row['deactivated']}</font></td>
      <td class="maintable"><a href="$filename?s=$s&page=$page&action=delete&id={$row['customerid']}">{$lng['panel']['delete']}</a></td>
      <td class="maintable"><a href="$filename?s=$s&page=$page&action=edit&id={$row['customerid']}">{$lng['panel']['edit']}</a></td>
     </tr>