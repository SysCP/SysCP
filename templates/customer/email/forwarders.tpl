$header
    <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
     <tr>
      <td colspan="3" class="title">{$lng['menue']['email']['forwarders']}</td>
     </tr>
     <tr>
      <td class="maintable">{$lng['emails']['from']}</td><td class="maintable">{$lng['emails']['to']}</td><td class="maintable">&nbsp;</td>
     </tr>
     <if ($userinfo['email_forwarders_used'] < $userinfo['email_forwarders'] || $userinfo['email_forwarders'] == '-1') && 15 < $forwarders_count && $emaildomains_count !=0 ><tr>
      <td class="maintable" colspan="3"><a href="$filename?page=forwarders&action=add&s=$s">{$lng['emails']['forwarders_add']}</a></td>
     </tr></if>
     $accounts
     <if ($userinfo['email_forwarders_used'] < $userinfo['email_forwarders'] || $userinfo['email_forwarders'] == '-1') && $emaildomains_count !=0 ><tr>
      <td class="maintable" colspan="3"><a href="$filename?page=forwarders&action=add&s=$s">{$lng['emails']['forwarders_add']}</a></td>
     </tr></if>
    </table>
$footer
