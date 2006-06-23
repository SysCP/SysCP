    <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
    <tr>
     <td colspan="4" class="title">{l10n get=admin.ipsandports.ipsandports}</td>
    </tr>
    <tr>
     <td class="maintable" colspan="4" nowrap>
      <form method="post" action="{url module=ipsandports action=list}">
      {l10n get=admin.ipsandports.default}:
      <select name="defaultipandport">
      {html_options options=$ipsandports_default selected=$ipsandports_default_id}
      </select>
      <input type="hidden" name="send" value="send"><input type="submit" value="{l10n get=panel.save}">
      </form>
     </td>
    </tr>
    <tr>
     <td class="maintable">ID</td>
     <td class="maintable">{l10n get=admin.ipsandports.ipandport}</td>
     <td class="maintable" colspan="2">&nbsp;</td>
    </tr>
    {foreach from=$ipsandports item=row}
     <tr>
      <td class="maintable" align="right"><font size="-1">{$row.id}</font></td>
      <td class="maintable"><font size="-1">{$row.ip}:{$row.port}</font></td>
      <td class="maintable">
      <a href="{url module=ipsandports action=delete id=$row.id}">{l10n get=panel.delete}</a></td>
      <td class="maintable">
      <a href="{url module=ipsandports action=edit id=$row.id}">{l10n get=panel.edit}</a></td>
     </tr>
     {/foreach}
    <tr>
     <td class="maintable" colspan="4">
     <a href="{url module=ipsandports action=add}">{l10n get=admin.ipsandports.add}</a></td>
    </tr>
   </table>
