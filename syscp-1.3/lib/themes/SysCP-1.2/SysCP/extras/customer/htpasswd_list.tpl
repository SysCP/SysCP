    <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
     <tr>
      <td colspan="4" class="title">{l10n get=SysCP.extras.directoryprotection}</td>
     </tr>
     <tr>
      <td class="maintable">{l10n get=SysCP.globallang.username}</td>
      <td class="maintable">{l10n get=SysCP.globallang.path}</td>
      <td class="maintable" colspan="2">&nbsp;</td>
     </tr>
     {foreach from=$htpasswds item=row}
     <tr>
      <td class="maintable">{$row.username}</td>
      <td class="maintable">{$row.path}</td>
      <td class="maintable"><a href="{url module=extras action=editHtpasswds id=$row.id}">{l10n get=SysCP.globallang.changepassword}</a></td>
      <td class="maintable"><a href="{url module=extras action=deleteHtpasswds id=$row.id}">{l10n get=SysCP.globallang.delete}</a></td>
     </tr>
     {/foreach}
     <tr>
      <td class="maintable" colspan="4">
      <a href="{url module=extras action=addHtpasswds}">{l10n get=SysCP.extras.directoryprotection_add}</a></td>
     </tr>
    </table>
