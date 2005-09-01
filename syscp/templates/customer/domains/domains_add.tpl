$header
    <form method="post" action="$filename">
     <input type="hidden" name="s" value="$s">
     <input type="hidden" name="page" value="$page">
     <input type="hidden" name="action" value="$action">
     <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
      <tr>
       <td colspan="2" class="title">{$lng['domains']['subdomain_add']}</td>
      </tr>
      <tr>
       <td class="maintable">{$lng['domains']['domainname']}:</td>
       <td class="maintable" nowrap><input type="text" name="subdomain" value="" size="15" maxlength="50"> <b>.</b> <select name="domain">$domains</select></td>
      </tr>
      <tr>
       <td class="maintable">{$lng['domains']['aliasdomain']}:</td>
       <td class="maintable" nowrap><select name="alias">$aliasdomains</select></td>
      </tr>
      <tr>
       <td class="maintable">{$lng['panel']['path']}:</td>
       <td class="maintable">{$pathSelect}</td>
      </tr>
      <tr>
       <td class="maintable" colspan=2 align="right"><input type="hidden" name="send" value="send"><input type="submit" value="{$lng['domains']['subdomain_add']}"></td>
      </tr>
     </table>
    </form>
$footer
