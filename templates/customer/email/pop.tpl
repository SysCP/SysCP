$header
    <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
     <tr>
      <td colspan="3" class="title">{$lng['menue']['email']['pop']}</td>
     </tr>
     <tr>
      <td class="maintable">{$lng['emails']['emailaddress']}</td><td class="maintable" colspan="2">&nbsp;</td>
     </tr>
     <if ($userinfo['emails_used'] < $userinfo['emails'] || $userinfo['emails'] == '-1') && 15 < $emails_count && $emaildomains_count !=0 ><tr>
      <td class="maintable" colspan="3"><a href="$filename?page=pop&action=add&s=$s">{$lng['emails']['pop3_add']}</a></td>
     </tr></if>
     $accounts
     <if ($userinfo['emails_used'] < $userinfo['emails'] || $userinfo['emails'] == '-1') && $emaildomains_count !=0 ><tr>
      <td class="maintable" colspan="3"><a href="$filename?page=pop&action=add&s=$s">{$lng['emails']['pop3_add']}</a></td>
     </tr></if>
    </table>
$footer
