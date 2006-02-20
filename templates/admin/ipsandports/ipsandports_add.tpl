$header
    <form method="post" action="$filename">
     <input type="hidden" name="s" value="$s">
     <input type="hidden" name="page" value="$page">
     <input type="hidden" name="action" value="$action">
     <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
      <tr>
       <td colspan="2" class="title">{$lng['admin']['ipsandports']['add']}</td>
      </tr>
      <tr>
       <td class="maintable">{$lng['admin']['ipsandports']['ip']}:</td>
       <td class="maintable" nowrap><input type="text" name="ip" value="" size="15" /></td>
      </tr>
      <tr>
       <td class="maintable">{$lng['admin']['ipsandports']['port']}:</td>
       <td class="maintable" nowrap><input type="text" name="port" value="" size="5" /></td>
      </tr>
      <tr>
       <td class="maintable" colspan="2" align="right"><input type="hidden" name="send" value="send"><input type="submit" value="{$lng['panel']['save']}"></td>
      </tr>
     </table>
    </form>
$footer