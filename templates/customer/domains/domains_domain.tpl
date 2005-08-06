     <tr>
      <td class="maintable">{$row['domain']}</td>
      <td class="maintable"><if $row['aliasdomain'] == ''>{$row['documentroot']}</if><if $row['aliasdomain'] != ''>{$lng['domains']['aliasdomain']} {$row['aliasdomain']}</if></td>
      <td class="maintable"><if $row['caneditdomain'] == '1'><a href="$filename?page=domains&action=edit&id={$row['id']}&s=$s">{$lng['panel']['edit']}</a></if></td>
      <td class="maintable"><if $row['parentdomainid'] != '0' && !$aliasdomain><a href="$filename?page=domains&action=delete&id={$row['id']}&s=$s">{$lng['panel']['delete']}</a></if></td>
     </tr>