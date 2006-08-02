     <tr>
      <td class="maintable">{$row['domain']}</td>
      <td class="maintable"><if $row['aliasdomain'] == ''>{$row['documentroot']}</if><if isset($row['aliasdomainid']) && $row['aliasdomainid'] != 0>{$lng['domains']['aliasdomain']} {$row['aliasdomain']}</if></td>
      <td class="maintable"><if $row['caneditdomain'] == '1'><a href="$filename?page=domains&action=edit&id={$row['id']}&s=$s">{$lng['panel']['edit']}</a></if></td>
      <td class="maintable"><if $row['parentdomainid'] != '0' && !(isset($row['domainaliasid']) && $row['domainaliasid'] != 0)><a href="$filename?page=domains&action=delete&id={$row['id']}&s=$s">{$lng['panel']['delete']}</a></if><if $row['parentdomainid'] == '0' && !(isset($row['domainaliasid']) && $row['domainaliasid'] != 0)>{$lng['domains']['istopleveldomain']}</if><if isset($row['domainaliasid']) && $row['domainaliasid'] != 0>{$lng['domains']['hasaliasdomains']}</if></td>
     </tr>