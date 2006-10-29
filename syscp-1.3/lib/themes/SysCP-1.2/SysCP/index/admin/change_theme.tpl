    <form method="post" action="{url module=index action=changeTheme}">
     <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
      <tr>
       <td colspan="2" class="title">{l10n get=SysCP.index.changetheme}</td>
      </tr>
      <tr>
       <td class="maintable" align="right" nowrap>{l10n get=SysCP.index.theme}:</td>
       <td class="maintable"><select name="new_theme">
       {html_options options=$theme_list selected=$theme_sel}</select></td>
      </tr>
      <tr>
       <td class="maintable" colspan="2" align="right"><input type="hidden" name="send" value="send"><input type="submit" value="{l10n get=SysCP.index.changetheme}"></td>
      </tr>
     </table>
    </form>
