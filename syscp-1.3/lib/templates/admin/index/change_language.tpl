$header
    <form method="post" action="{$config->get('env.filename')}">
     <input type="hidden" name="s" value="{$config->get('env.s')}">
     <input type="hidden" name="page" value="{$config->get('env.page')}">
     <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
      <tr>
       <td colspan="2" class="title">{$lng['menue']['main']['changelanguage']}</td>
      </tr>
      <tr>
       <td class="maintable" align="right" nowrap>{$lng['login']['language']}:</td>
       <td class="maintable"><select name="def_language">$language_options</select></td>
      </tr>
      <tr>
       <td class="maintable" colspan="2" align="right"><input type="hidden" name="send" value="send"><input type="submit" value="{$lng['menue']['main']['changelanguage']}"></td>
      </tr>
     </table>
    </form>
$footer
