$header
    <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
     <tr>
      <td colspan="4" class="title">{$lng['menue']['ftp']['accounts']}</td>
     </tr>
     <tr>
      <td class="maintable">{$lng['login']['username']}</td><td class="maintable">{$lng['panel']['path']}</td><td class="maintable" colspan="2">&nbsp;</td>
     </tr>
     <if ($userinfo['ftps_used'] < $userinfo['ftps'] || $userinfo['ftps'] == '-1') && 15 < $ftps_count ><tr>
      <td class="maintable" colspan="4"><a href="{$config->get('env.filename')}?page=accounts&action=add&s={$config->get('env.s')}">{$lng['ftp']['account_add']}</a></td>
     </tr></if>
     <if 0 < $pages><tr>
      <td colspan="20" class="paging">{$paging}</td>
     </tr></if>
      $accounts
     <if 0 < $pages><tr>
      <td colspan="20" class="paging">{$paging}</td>
     </tr></if>
     <if ($userinfo['ftps_used'] < $userinfo['ftps'] || $userinfo['ftps'] == '-1') ><tr>
      <td class="maintable" colspan="4"><a href="{$config->get('env.filename')}?page=accounts&action=add&s={$config->get('env.s')}">{$lng['ftp']['account_add']}</a></td>
     </tr></if>
    </table>
$footer
