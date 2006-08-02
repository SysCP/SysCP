     <tr>
      <td class="maintable" align="right"><font size="-1">{$row['id']}</font></td>
      <td class="maintable"><font size="-1">{$row['domain']}</font></td>
      <td class="maintable"><font size="-1">{$row['ipandport']}</font></td>
      <td class="maintable"><font size="-1"><if $row['name'] != '' && $row['firstname'] != ''>{$row['name']}, {$row['firstname']}</if><if $row['name'] != '' && $row['firstname'] != '' && $row['company'] != ''> | </if><if $row['company'] != ''>{$row['company']}</if> ({$row['loginname']})</font></td>
      <td class="maintable"><a href="$filename?s=$s&page=$page&action=edit&id={$row['id']}">{$lng['panel']['edit']}</a></td>
      <td class="maintable"><if !(isset($row['standardsubdomain']) && $row['standardsubdomain'] == $row['id']) && !(isset($row['domainaliasid']) && $row['domainaliasid'] != 0)><a href="$filename?s=$s&page=$page&action=delete&id={$row['id']}">{$lng['panel']['delete']}</a></if><if isset($row['domainaliasid']) && $row['domainaliasid'] != 0>{$lng['domains']['hasaliasdomains']}</if><if (isset($row['standardsubdomain']) && $row['standardsubdomain'] == $row['id'])>{$lng['admin']['stdsubdomain']}</if></td>
     </tr>
