$header
    <form method="post" action="$filename">
     <input type="hidden" name="s" value="$s">
     <input type="hidden" name="page" value="$page">
     <input type="hidden" name="action" value="$action">
     <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
      <tr>
       <td colspan="2" class="title">{$lng['emails']['emails_add']}</td>
      </tr>
      <tr>
       <td class="maintable">{$lng['emails']['emailaddress']}:</td>
       <td class="maintable" nowrap><input type="text" name="email_part" value="" size="15"> @ <select name="domain">$domains</select></td>
      </tr>
      <tr>
       <td class="maintable">{$lng['emails']['iscatchall']}</td>
       <td class="maintable" nowrap>$iscatchall</td>
      </tr>
      <tr>
       <td class="maintable" colspan="2" align="right"><input type="hidden" name="send" value="send"><input type="submit" value="{$lng['emails']['emails_add']}"></td>
      </tr>
     </table>
    </form>
$footer
