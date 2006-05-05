$header
    <form method="post" action="{$config->get('env.filename')}">
     <input type="hidden" name="s" value="{$config->get('env.s')}">
     <input type="hidden" name="page" value="{$config->get('env.page')}">
     <input type="hidden" name="action" value="{$config->get('env.action')}">
     <input type="hidden" name="id" value="{$config->get('env.id')}">
     <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
      <tr>
       <td colspan="2" class="title">{$lng['emails']['account_add']}</td>
      </tr>
      <tr>
       <td class="maintable">{$lng['emails']['emailaddress']}:</td>
       <td class="maintable" nowrap>{$result['email_full']}</td>
      </tr>
      <tr>
       <td class="maintable">{$lng['login']['password']}:</td>
       <td class="maintable"><input type="password" name="password" maxlength="50"></td>
      </tr>
      <tr>
       <td class="maintable" colspan=2 align="right"><input type="hidden" name="send" value="send"><input type="submit" value="{$lng['emails']['account_add']}"></td>
      </tr>
     </table>
    </form>
$footer
