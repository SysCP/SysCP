$header
    <form method="post" action="{$config->get('env.filename')}">
     <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
      <tr>
       <td colspan="2" class="title">SysCP - login</td>
      </tr>
      <tr>
       <td class="maintable"><font size="-1">{$lng['login']['username']}:</font></td>
       <td class="maintable"><input type="text" name="loginname" value="" maxlength="50"></td>
      </tr>
      <tr>
       <td class="maintable"><font size="-1">{$lng['login']['password']}:</font></td>
       <td class="maintable"><input type="password" name="password" maxlength="50"></td>
      </tr>
      <tr>
       <td class="maintable"><font size="-1">{$lng['login']['language']}:</font></td>
       <td class="maintable"><select name="language">$language_options</select></td>
      </tr>
      <tr>
       <td class="maintable" colspan="2" align="right"><input type="hidden" name="send" value="send"><input type="submit" value="{$lng['login']['login']}"></td>
      </tr>
     </table>
    </form>
$footer