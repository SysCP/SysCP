$header
    <form method="post" action="$filename">
     <input type="hidden" name="s" value="$s">
     <input type="hidden" name="page" value="$page">
     <input type="hidden" name="action" value="$action">
     <input type="hidden" name="id" value="$id">
     <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
      <tr>
       <td colspan="2" class="title">{$lng['domains']['subdomain_edit']}</td>
      </tr>
      <tr>
       <td class="maintable">{$lng['domains']['domainname']}:</td>
       <td class="maintable" nowrap>{$result['domain']}</td>
      </tr>
      <tr>
       <td class="maintable">{$lng['panel']['path']}:</td>
       <td class="maintable"><input type="text" name="path" value="{$result['documentroot']}" maxlength="50"></td>
      </tr>
      <if $result['isemaildomain'] == '1'><tr>
       <td class="maintable">{$lng['domains']['wildcarddomain']}</td>
       <td class="maintable">$iswildcarddomain</td>
      </tr></if>
      <tr>
       <td class="maintable" colspan="2" align="right"><input type="hidden" name="send" value="send"><input type="submit" value="{$lng['panel']['save']}"></td>
      </tr>
     </table>
    </form>
$footer
