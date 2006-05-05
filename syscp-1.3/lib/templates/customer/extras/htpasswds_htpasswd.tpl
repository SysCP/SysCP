     <tr>
      <td class="maintable">{$row['username']}</td>
      <td class="maintable">{$row['path']}</td>
      <td class="maintable"><a href="{$config->get('env.filename')}?page=htpasswds&action=edit&id={$row['id']}&s={$config->get('env.s')}">{$lng['menue']['main']['changepassword']}</a></td>
      <td class="maintable"><a href="{$config->get('env.filename')}?page=htpasswds&action=delete&id={$row['id']}&s={$config->get('env.s')}">{$lng['panel']['delete']}</a></td>
     </tr>

