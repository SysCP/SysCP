$header
    <form method="post" action="$filename">
     <input type="hidden" name="s" value="$s">
     <input type="hidden" name="page" value="$page">
     <input type="hidden" name="action" value="$action">
     <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
      <tr>
       <td colspan="2" class="title">{$lng['extras']['directoryprotection_add']}</td>
      </tr>
      <tr>
       <td class="maintable">{$lng['panel']['path']}:</td>
       <td class="maintable" nowrap>{$pathSelect}</td>
      </tr>
      <tr>
       <td class="maintable">{$lng['login']['username']}:</td>
       <td class="maintable"><input type="text" name="username" size="30"></td>
      </tr>
      <tr>
       <td class="maintable">{$lng['login']['password']}:</td>
       <td class="maintable"><input type="password" name="password" size="30"></td>
      </tr>
      <tr>
       <td class="maintable" colspan="2" align="right"><input type="hidden" name="send" value="send"><input type="submit" value="{$lng['extras']['directoryprotection_add']}"></td>
      </tr>
     </table>
    </form>
$footer
