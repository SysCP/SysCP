$header
    <form method="post" action="{$config->get('env.filename')}">
     <input type="hidden" name="s" value="{$config->get('env.s')}">
     <input type="hidden" name="page" value="{$config->get('env.page')}">
     <input type="hidden" name="action" value="{$config->get('env.action')}">
     <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
      <tr>
       <td colspan="20" class="title">{$lng['admin']['templates']['template_add']}</td>
      </tr>
      <tr>
       <td class="maintable">{$lng['login']['language']}:</td>
       <td class="maintable" nowrap><select name="language">$language_options</select></td>
      </tr>
      <tr>
       <td class="maintable" colspan="2" align="right"><input type="hidden" name="prepare" value="prepare"><input type="submit" value="{$lng['panel']['next']}"></td>
      </tr>
     </table>
    </form>
$footer