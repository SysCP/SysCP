    <form method="post" action="{url module=ftp action=add}">
     <input type="hidden" name="action" value="{$Config->get('env.action')}">
     <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
      <tr>
       <td colspan="2" class="title">{l10n get=ftp.account_add}</td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=panel.path}:</td>
       <td class="maintable" nowrap>{$pathSelect}</td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=login.password}:</td>
       <td class="maintable"><input type="password" name="password" size="30"></td>
      </tr>
      <tr>
       <td class="maintable" colspan="2" align="right">
       <input type="hidden" name="send" value="send">
       <input type="submit" value="{l10n get=ftp.account_add}"></td>
      </tr>
     </table>
    </form>
