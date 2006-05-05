     <tr>
      <td class="maintable" align="right"><font size="-1">{$row['id']}</font></td>
      <td class="maintable"><font size="-1">{$row['domain']}</font></td>
      <td class="maintable"><font size="-1">{$row['ipandport']}</font></td>
      <td class="maintable"><font size="-1">{$row['name']} {$row['firstname']} ({$row['loginname']})</font></td>
      <td class="maintable"><if (!$standardsubdomain && !$aliasdomain)><a href="{$config->get('env.filename')}?s={$config->get('env.s')}&page={$config->get('env.page')}&action=delete&id={$row['id']}">{$lng['panel']['delete']}</a></if></td>
      <td class="maintable"><a href="{$config->get('env.filename')}?s={$config->get('env.s')}&page={$config->get('env.page')}&action=edit&id={$row['id']}">{$lng['panel']['edit']}</a></td>
     </tr>
