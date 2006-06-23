    <form method="post" action="{url module=mysql action=edit}">
     <input type="hidden" name="id" value="{$Config->get('env.id')}">
     <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
      <tr>
       <td colspan="2" class="title">{l10n get=menue.main.changepassword}</td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=mysql.databasename}:</td>
       <td class="maintable" nowrap>{$result.databasename}</td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=mysql.databasedescription}:</td>
       <td class="maintable">
       <input type="text" name="description" maxlength="100" value="{$result.description}"></td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=changepassword.new_password_ifnotempty}:</td>
       <td class="maintable"><input type="password" name="password" maxlength="50"></td>
      </tr>
      <tr>
       <td class="maintable" colspan="2" align="right">
       <input type="hidden" name="send" value="send"><input type="submit" value="{l10n get=panel.save}"></td>
      </tr>
     </table>
    </form>
