     <tr>
      <td class="maintable">{$row['domain']}</td>
      <td class="maintable">{$row['documentroot']}</td>
      <if $settings['system']['documentrootstyle'] == 'customer'><td class="maintable"><a href="$filename?page=domains&action=edit&id={$row['id']}&s=$s">{$lng['panel']['edit']}</a></td></if>
      <td class="maintable"><a href="$filename?page=domains&action=delete&id={$row['id']}&s=$s">{$lng['panel']['delete']}</a></td>
     </tr>