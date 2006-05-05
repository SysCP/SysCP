$header
    <form method="post" action="{$config->get('env.filename')}">
     <input type="hidden" name="s" value="{$config->get('env.s')}">
     <input type="hidden" name="page" value="{$config->get('env.page')}">
     <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
      <tr>
       <td colspan="2" class="title">{$lng['menue']['main']['changepassword']}</td>
      </tr>
      <tr>
       <td class="maintable" align="right" nowrap>{$lng['changepassword']['old_password']}:</td>
       <td class="maintable"><input type="password" name="old_password" maxlength="50"></td>
      </tr>
      <tr>
       <td class="maintable" align="right" nowrap>{$lng['changepassword']['new_password']}:</td>
       <td class="maintable"><input type="password" name="new_password" maxlength="50"></td>
      </tr>
      <tr>
       <td class="maintable" align="right" nowrap>{$lng['changepassword']['new_password_confirm']}:</td>
       <td class="maintable"><input type="password" name="new_password_confirm" maxlength="50"></td>
      </tr>
      <tr>
       <td class="maintable" colspan="2" align="right"><input type="hidden" name="send" value="send"><input type="submit" value="{$lng['menue']['main']['changepassword']}"></td>
      </tr>
     </table>
    </form>
$footer
