$header
    <form method="post" action="$filename">
     <input type="hidden" name="s" value="$s">
     <input type="hidden" name="page" value="$page">
     <input type="hidden" name="action" value="$action">
     <input type="hidden" name="subjectid" value="$subjectid">
     <input type="hidden" name="mailbodyid" value="$mailbodyid">
     <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
      <tr>
       <td colspan="20" class="title">{$lng['admin']['templates']['template_add']}</td>
      </tr>
      <tr>
       <td class="maintable">{$lng['login']['language']}:</td>
       <td class="maintable" nowrap><b>{$result['language']}</b></td>
      </tr>
      <tr>
       <td class="maintable">{$lng['admin']['templates']['action']}:</td>
       <td class="maintable" nowrap><b>$template</b></td>
      </tr>
      <tr>
       <td class="maintable">{$lng['admin']['templates']['subject']}: *</td>
       <td class="maintable" nowrap><input type="text" name="subject" value="$subject" maxlength="255" size="100"></td>
      </tr>
      <tr>
       <td class="maintable">{$lng['admin']['templates']['mailbody']}: *</td>
       <td class="maintable" nowrap><textarea type="text" name="mailbody" rows="20" cols="75">$mailbody</textarea></td>
      </tr>
      <tr>
       <td class="maintable" colspan=2 align="right"><input type="hidden" name="send" value="send"><input type="submit" value="{$lng['panel']['save']}"></td>
      </tr>
     </table>
    </form>
$footer