     <tr>
      <td class="maintable">{$row['databasename']}</td>
	  <td class="maintable">{$row['description']}</td>
      <td class="maintable"><a href="{$config->get('env.filename')}?page=mysqls&action=edit&id={$row['id']}&s={$config->get('env.s')}">{$lng['panel']['edit']}</a></td>
      <td class="maintable"><a href="{$config->get('env.filename')}?page=mysqls&action=delete&id={$row['id']}&s={$config->get('env.s')}">{$lng['panel']['delete']}</a></td>
     </tr>

