    <form method="post" action="{url module=templates action=add}">
     <input type="hidden" name="page" value="{$Config->get('env.page')}">
     <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
      <tr>
       <td colspan="20" class="title">{l10n get=SysCP.templates.template_add}</td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=SysCP.templates.language}:</td>
       <td class="maintable" nowrap><select name="language">
       {html_options options=$lang_list selected=$User.def_language}</select></td>
      </tr>
      <tr>
       <td class="maintable" colspan="2" align="right"><input type="hidden" name="prepare" value="prepare"><input type="submit" value="{l10n get=SysCP.globallang.next}"></td>
      </tr>
     </table>
    </form>
