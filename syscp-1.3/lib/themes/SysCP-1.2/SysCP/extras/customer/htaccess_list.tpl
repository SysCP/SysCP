    <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
     <tr>
      <td colspan="8" class="title">{l10n get=SysCP.extras.pathoptions}</td>
     </tr>
     <tr>
      <td class="maintable">{l10n get=SysCP.globallang.path}</td>
      <td class="maintable">{l10n get=SysCP.extras.view_directory}</td>
      <td class="maintable">{l10n get=SysCP.extras.error404path}</td>
      <td class="maintable">{l10n get=SysCP.extras.error403path}</td>
      <td class="maintable">{l10n get=SysCP.extras.error500path}</td>
      <td class="maintable" colspan="2">&nbsp;</td>
     </tr>
     {foreach from=$htaccess item=row}
     <tr>
      <td class="maintable">{$row.path}</td>
      <td class="maintable">{$row.options_indexes}</td>
      <td class="maintable">{$row.error404path}</td>
      <td class="maintable">{$row.error403path}</td>
      <td class="maintable">{$row.error500path}</td>
      <td class="maintable"><a href="{url module=extras action=editHtaccess id=$row.id}">{l10n get=SysCP.globallang.edit}</a></td>
      <td class="maintable"><a href="{url module=extras action=deleteHtaccess id=$row.id}">{l10n get=SysCP.globallang.delete}</a></td>
     </tr>
     {/foreach}

     <tr>
      <td class="maintable" colspan="8">
      <a href="{url module=extras action=addHtaccess}">{l10n get=SysCP.extras.pathoptions_add}</a></td>
     </tr>
    </table>
