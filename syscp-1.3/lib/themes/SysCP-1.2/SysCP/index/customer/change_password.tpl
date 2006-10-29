    <form method="post" action="{url module=index action=changePassword}">
     <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
      <tr>
       <td colspan="2" class="title">{l10n get=SysCP.globallang.changepassword}</td>
      </tr>
      <tr>
       <td class="maintable" align="right" nowrap>{l10n get=SysCP.index.old_password}:</td>
       <td class="maintable"><input type="password" name="old_password" maxlength="50"></td>
      </tr>
      <tr>
       <td class="maintable" align="right" nowrap>{l10n get=SysCP.index.new_password}:</td>
       <td class="maintable"><input type="password" name="new_password" maxlength="50"></td>
      </tr>
      <tr>
       <td class="maintable" align="right" nowrap>{l10n get=SysCP.index.new_password_confirm}:</td>
       <td class="maintable"><input type="password" name="new_password_confirm" maxlength="50"></td>
      </tr>
      <tr>
       <td class="maintable" colspan="2" align="left"><input type="checkbox" name="change_main_ftp" value="true">{l10n get=SysCP.index.change_ftp_pw}</td>
      </tr>
      <tr>
       <td class="maintable" colspan="2" align="right"><input type="hidden" name="send" value="send"><input type="submit" value="{l10n get=SysCP.globallang.changepassword}"></td>
      </tr>
     </table>
    </form>
