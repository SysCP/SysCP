$header
    <form method="post" action="$filename">
     <input type="hidden" name="s" value="$s">
     <input type="hidden" name="page" value="$page">
     <input type="hidden" name="action" value="$action">
     <input type="hidden" name="id" value="$id">
     <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
      <tr>
       <td colspan="2" class="title">{$lng['extras']['pathoptions_edit']}</td>
      </tr>
      <tr>
       <td class="maintable">{$lng['panel']['path']}:</td>
       <td class="maintable" nowrap>{$result['path']}</td>
      </tr>
      <tr>
       <td class="maintable">{$lng['extras']['directory_browsing']}:</td>
       <td class="maintable">$options_indexes</td>
      </tr>
      <tr>
       <td class="maintable" colspan="2" align="right"><input type="hidden" name="send" value="send"><input type="submit" value="{$lng['extras']['pathoptions_edit']}"></td>
      </tr>
     </table>
    </form>
$footer