    <form method="post" action="{url module=ipsandports action=add}">
     <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
      <tr>
       <td colspan="2" class="title">{l10n get=admin.ipsandports.add}</td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=admin.ipsandports.ip}:</td>
       <td class="maintable" nowrap><input type="text" name="ip" value="" size="15" /></td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=admin.ipsandports.port}:</td>
       <td class="maintable" nowrap><input type="text" name="port" value="" size="5" /></td>
      </tr>
      <tr>
       <td class="maintable" colspan="2" align="right"><input type="hidden" name="send" value="send"><input type="submit" value="{l10n get=panel.save}"></td>
      </tr>
     </table>
    </form>
