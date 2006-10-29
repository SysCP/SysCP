    <form method="post" action="{url module=index action=changeLanguage}">
     <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
      <tr>
       <td colspan="2" class="title">{l10n get=SysCP.index.changelanguage}</td>
      </tr>
      <tr>
       <td class="maintable" align="right" nowrap>{l10n get=SysCP.index.language}:</td>
       <td class="maintable"><select name="def_language">
       {html_options options=$lang_list selected=$User.def_language}</select></td>
      </tr>
      <tr>
       <td class="maintable" colspan="2" align="right"><input type="hidden" name="send" value="send"><input type="submit" value="{l10n get=SysCP.index.changelanguage}"></td>
      </tr>
     </table>
    </form>
