$header
    <form method="post" action="$filename">
     <input type="hidden" name="s" value="$s">
     <input type="hidden" name="page" value="$page">
     <input type="hidden" name="action" value="$action">
     <input type="hidden" name="id" value="$id">
     <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
      <tr>
       <td colspan="2" class="title">{$lng['emails']['forwarder_add']}</td>
      </tr>
      <tr>
       <td class="maintable">{$lng['emails']['from']}:</td>
       <td class="maintable" nowrap>{$result['email']}</td>
      </tr>
      <tr>
       <td class="maintable">{$lng['emails']['to']}:</td>
       <td class="maintable"><input type="text" name="destination" size="30"></td>
      </tr>
      <tr>
       <td class="maintable" colspan="2" align="right"><input type="hidden" name="send" value="send"><input type="submit" value="{$lng['emails']['forwarder_add']}"></td>
      </tr>
     </table>
    </form>
$footer 
