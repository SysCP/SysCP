     <tr>
      <td class="maintable">{$row['path']}</td>
      <td class="maintable">{$row['options_indexes']}</td>
      <td class="maintable">{$row['error404path']}</td>
      <td class="maintable">{$row['error403path']}</td>
      <td class="maintable">{$row['error500path']}</td>
      <td class="maintable">{$row['error401path']}</td>
      <td class="maintable"><a href="$filename?page=htaccess&action=edit&id={$row['id']}&s=$s">{$lng['panel']['edit']}</a></td>
      <td class="maintable"><a href="$filename?page=htaccess&action=delete&id={$row['id']}&s=$s">{$lng['panel']['delete']}</a></td>
     </tr>

