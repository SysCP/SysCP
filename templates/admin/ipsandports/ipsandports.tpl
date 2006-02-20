$header
    <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
    <tr>
     <td colspan="4" class="title">{$lng['admin']['ipsandports']['ipsandports']}</td>
    </tr>
    <tr>
     <td class="maintable" colspan="4" nowrap>
      <form method="post" action="$filename">
      <input type="hidden" name="s" value="$s">
      <input type="hidden" name="page" value="$page">
      <input type="hidden" name="action" value="$action">
      {$lng['admin']['ipsandports']['default']}:
      <select name="defaultipandport">$ipsandports_default</select>
      <input type="hidden" name="send" value="send"><input type="submit" value="{$lng['panel']['save']}">
      </form>
     </td>
    </tr>
    <tr>
     <td class="maintable">ID</td>
     <td class="maintable">{$lng['admin']['ipsandports']['ipandport']}</td>
     <td class="maintable" colspan="2">&nbsp;</td>
    </tr>
    $ipsandports
    <tr>
     <td class="maintable" colspan="4"><a href="$filename?page=$page&action=add&s=$s">{$lng['admin']['ipsandports']['add']}</a></td>
    </tr>
   </table>
$footer