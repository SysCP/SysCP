$header
    <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
     <tr>
      <td colspan="3" class="title">{$lng['menue']['mysql']['databases']}</td>
     </tr>
     <tr>
      <td class="maintable">{$lng['mysql']['databasename']}</td><td class="maintable" colspan="2">&nbsp;</td>
     </tr>
     <if ($userinfo['mysqls_used'] < $userinfo['mysqls'] || $userinfo['mysqls'] == '-1') && 15 < $mysqls_count ><tr>
      <td class="maintable" colspan="3"><a href="$filename?page=mysqls&action=add&s=$s">{$lng['mysql']['database_create']}</a></td>
     </tr></if>
     $mysqls
     <if ($userinfo['mysqls_used'] < $userinfo['mysqls'] || $userinfo['mysqls'] == '-1') ><tr>
      <td class="maintable" colspan="3"><a href="$filename?page=mysqls&action=add&s=$s">{$lng['mysql']['database_create']}</a></td>
     </tr></if>
    </table>
$footer
