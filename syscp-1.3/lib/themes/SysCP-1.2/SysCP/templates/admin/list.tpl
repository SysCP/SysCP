   <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
    <tr>
     <td colspan="20" class="title">{l10n get=SysCP.templates.templates}</td>
    </tr>
    {if $add}<tr>
     <td colspan="20" class="maintable">
     <a href="{url module=templates action=add}">{l10n get=SysCP.templates.template_add}</a></td>
    </tr>{/if}
    <tr>
     <td class="maintable">{l10n get=SysCP.templates.language}</td>
     <td class="maintable">{l10n get=SysCP.templates.action}</td>
     <td class="maintable" colspan="2">&nbsp;</td>
    </tr>
    {foreach from=$templates item=row}
    <tr>
      <td class="maintable">{$row.language}</td>
      <td class="maintable">{$row.template}</td>
      <td class="maintable"><a href="{url module=templates action=delete subjectid=$row.subjectid mailbodyid=$row.mailbodyid}">{l10n get=SysCP.globallang.delete}</a></td>
      <td class="maintable"><a href="{url module=templates action=edit   subjectid=$row.subjectid mailbodyid=$row.mailbodyid}">{l10n get=SysCP.globallang.edit}</a></td>
     </tr>
     {/foreach}
     {if $add}<tr>
     <td colspan="20" class="maintable">
     <a href="{url module=templates action=add}">{l10n get=SysCP.templates.template_add}</a></td>
    </tr>{/if}
   </table>
