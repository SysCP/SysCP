$header
    <form method="post" action="$filename">
     <input type="hidden" name="s" value="$s">
     <input type="hidden" name="page" value="$page">
     <input type="hidden" name="action" value="$action">
     <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
      <tr>
       <td colspan="2" class="title">{$lng['admin']['domain_add']}</td>
      </tr>
      <tr>
       <td class="maintable">{$lng['admin']['customer']}:</td>
       <td class="maintable" nowrap><select name="customerid">$customers</select></td>
      </tr>
      <tr>
       <td class="maintable">Domain:</td>
       <td class="maintable" nowrap><input type="text" name="domain" value=""></td>
      </tr>
      <if $settings['system']['documentrootstyle'] == 'customer' && $userinfo['change_serversettings'] == '1'><tr>
       <td class="maintable" nowrap>DocumentRoot:<font size="-2"><br />({$lng['panel']['emptyfordefault']})</td>
       <td class="maintable" nowrap><input type="text" name="documentroot" value=""></td>
      </tr></if>
      <if $userinfo['change_serversettings'] == '1'><tr>
       <td class="maintable" nowrap>Zonefile:<font size="-2"><br />({$lng['panel']['emptyfordefault']})</td>
       <td class="maintable" nowrap><input type="text" name="zonefile" value=""></td>
      </tr>
      <tr>
       <td class="maintable" nowrap>OpenBasedir:</td>
       <td class="maintable" nowrap>$openbasedir</td>
      </tr>
      <tr>
       <td class="maintable" nowrap>Safemode:</td>
       <td class="maintable" nowrap>$safemode</td>
      </tr>
      <tr>
       <td class="maintable" nowrap>{$lng['admin']['ownvhostsettings']}:</td>
       <td class="maintable" nowrap><textarea rows="4" cols="30" name="specialsettings"></textarea></td>
      </tr></if>
      <tr>
       <td class="maintable" colspan="2" align="right"><input type="hidden" name="send" value="send"><input type="submit" value="{$lng['panel']['save']}"></td>
      </tr>
     </table>
    </form>
$footer
