$header
     <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
      <tr>
       <td colspan="2" class="title">{$lng['emails']['emails_edit']}</td>
      </tr>
      <tr>
       <td class="maintable">{$lng['emails']['emailaddress']}:</td>
       <td class="maintable" nowrap>{$result['email']}</td>
      </tr>
      <tr>
       <td class="maintable">{$lng['emails']['account']}:</td>
       <td class="maintable">
        <if $result['popaccountid'] != 0>{$lng['panel']['yes']} [<a href="$filename?page=accounts&action=changepw&id={$result['id']}&s=$s">{$lng['menue']['main']['changepassword']}</a>] [<a href="$filename?page=accounts&action=delete&id={$result['id']}&s=$s">{$lng['emails']['account_delete']}</a>]</if>
        <if $result['popaccountid'] == 0>{$lng['panel']['no']} [<a href="$filename?page=accounts&action=add&id={$result['id']}&s=$s">{$lng['emails']['account_add']}</a>]</if>
       </td>
      </tr>
      <tr>
       <td class="maintable">{$lng['emails']['forwarders']} ({$forwarders_count}):</td>
       <td class="maintable">$forwarders<a href="$filename?page=forwarders&action=add&id={$result['id']}&s=$s">{$lng['emails']['forwarder_add']}</a></td>
      </tr>
     </table>
$footer
