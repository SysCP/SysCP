$header
     <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
      <tr>
       <td colspan="2" class="title">{$lng['emails']['emails_edit']}</td>
      </tr>
      <tr>
       <td class="maintable">{$lng['emails']['emailaddress']}:</td>
       <td class="maintable" nowrap>{$result['email_full']}</td>
      </tr>
      <tr>
       <td class="maintable">{$lng['emails']['account']}:</td>
       <td class="maintable">
        <if $result['popaccountid'] != 0>{$lng['panel']['yes']} [<a href="{$config->get('env.filename')}?page=accounts&action=changepw&id={$result['id']}&s={$config->get('env.s')}">{$lng['menue']['main']['changepassword']}</a>] [<a href="{$config->get('env.filename')}?page=accounts&action=delete&id={$result['id']}&s={$config->get('env.s')}">{$lng['emails']['account_delete']}</a>]</if>
        <if $result['popaccountid'] == 0>{$lng['panel']['no']} [<a href="{$config->get('env.filename')}?page=accounts&action=add&id={$result['id']}&s={$config->get('env.s')}">{$lng['emails']['account_add']}</a>]</if>
       </td>
      </tr>
      <tr>
       <td class="maintable">{$lng['emails']['catchall']}:</td>
       <td class="maintable"><if $result['iscatchall'] != 0>{$lng['panel']['yes']}</if><if $result['iscatchall'] == 0>{$lng['panel']['no']}</if> [<a href="{$config->get('env.filename')}?page={$config->get('env.page')}&action=togglecatchall&id={$result['id']}&s={$config->get('env.s')}">{$lng['panel']['toggle']}</a>]</td>
      </tr>
      <tr>
       <td class="maintable">{$lng['emails']['forwarders']} ({$forwarders_count}):</td>
       <td class="maintable">$forwarders<a href="{$config->get('env.filename')}?page=forwarders&action=add&id={$result['id']}&s={$config->get('env.s')}">{$lng['emails']['forwarder_add']}</a></td>
      </tr>
     </table>
$footer
