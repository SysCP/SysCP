     <tr>
      <td class="maintable">{$tLanguage}</td>
      <td class="maintable">{$template}</td>
      <td class="maintable"><a href="{$config->get('env.filename')}?s={$config->get('env.s')}&page={$config->get('env.page')}&action=delete&subjectid=$subjectid&mailbodyid=$mailbodyid">{$lng['panel']['delete']}</a></td>
      <td class="maintable"><a href="{$config->get('env.filename')}?s={$config->get('env.s')}&page={$config->get('env.page')}&action=edit&subjectid=$subjectid&mailbodyid=$mailbodyid">{$lng['panel']['edit']}</a></td>
     </tr>
