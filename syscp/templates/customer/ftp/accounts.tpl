$header
    <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
     <tr>
      <td colspan="4" class="title">{$lng['menue']['ftp']['accounts']}</td>
     </tr>
     <tr>
      <td class="maintable">{$lng['login']['username']}</td><td class="maintable">{$lng['panel']['path']}</td><td class="maintable" colspan="2">&nbsp;</td>
     </tr>
     <if ($userinfo['ftps_used'] < $userinfo['ftps'] || $userinfo['ftps'] == '-1') && 0 < $ftps_count ><tr>
      <td class="maintable" colspan="4"><a href="$filename?page=accounts&action=add&s=$s">{$lng['ftp']['account_add']}</a></td>
     </tr></if>
     $accounts
     <if ($userinfo['ftps_used'] < $userinfo['ftps'] || $userinfo['ftps'] == '-1') ><tr>
      <td class="maintable" colspan="4"><a href="$filename?page=accounts&action=add&s=$s">{$lng['ftp']['account_add']}</a></td>
     </tr></if>
    </table>
$footer
