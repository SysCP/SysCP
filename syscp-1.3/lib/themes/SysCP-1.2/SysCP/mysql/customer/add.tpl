    <form method="post" action="{url module=mysql action=add}">
     <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
      <tr>
       <td colspan="2" class="title">{l10n get=SysCP.mysql.db_create}</td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=SysCP.mysql.db_desc}:</td>
       <td class="maintable"><input type="text" name="description" maxlength="100"></td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=SysCP.globallang.password}:</td>
       <td class="maintable"><input type="password" name="password" maxlength="50"></td>
      </tr>
      <tr>
       <td class="maintable" colspan="2" align="right">
       <input type="hidden" name="send" value="send">
       <input type="submit" value="{l10n get=SysCP.mysql.db_create}"></td>
      </tr>
     </table>
    </form>
