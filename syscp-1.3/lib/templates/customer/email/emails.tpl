$header
    <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
     <tr>
      <td colspan="6" class="title">{$lng['menue']['email']['emails']}</td>
     </tr>
     <tr>
      <td class="maintable">{$lng['emails']['emailaddress']}</td><td class="maintable">{$lng['emails']['forwarders']}</td><td class="maintable">{$lng['emails']['account']}</td><td class="maintable">{$lng['emails']['catchall']}</td><td class="maintable" colspan="2">&nbsp;</td>
     </tr>
     <if ($userinfo['emails_used'] < $userinfo['emails'] || $userinfo['emails'] == '-1') && 15 < $emails_count && $emaildomains_count !=0 ><tr>
      <td class="maintable" colspan="6"><a href="{$config->get('env.filename')}?page={$config->get('env.page')}&action=add&s={$config->get('env.s')}">{$lng['emails']['emails_add']}</a></td>
     </tr></if>
     $accounts
     <if ($userinfo['emails_used'] < $userinfo['emails'] || $userinfo['emails'] == '-1') && $emaildomains_count !=0 ><tr>
      <td class="maintable" colspan="6"><a href="{$config->get('env.filename')}?page={$config->get('env.page')}&action=add&s={$config->get('env.s')}">{$lng['emails']['emails_add']}</a></td>
     </tr></if>
    </table>
$footer
