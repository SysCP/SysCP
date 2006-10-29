<table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
  <tr>
    <td colspan="20" class="title">{l10n get=SysCP.domains.domains}</td>
  </tr>
  {if ($User.domains_used < $User.domains || $User.domains == '-1') && 15 < $User.domains_used}
  <tr>
     <td class="maintable" colspan="20">
     <a href="{url module=domains action=add}">{l10n get=SysCP.domains.add}</a></td>
    </tr>{/if}
    {if 0 < $pages}<tr>
     <td colspan="20" class="maintable">{$paging}</td>
    </tr>{/if}
    <tr>
     <td class="maintable">{l10n get=SysCP.globallang.id}</td>
     <td class="maintable">{l10n get=SysCP.domains.domain}</td>
     <td class="maintable">{l10n get=SysCP.domains.ipport}</td>
     <td class="maintable">{l10n get=SysCP.domains.customer}</td>
     <td class="maintable" colspan="2">&nbsp;</td>
    </tr>
    {foreach from=$domain_list item=row}
     <tr>
      <td class="maintable" align="right"><font size="-1">{$row.id}</font></td>
      <td class="maintable"><font size="-1">{$row.domain}</font></td>
      <td class="maintable"><font size="-1">{$row.ipandport}</font></td>
      <td class="maintable"><font size="-1">{$row.name} {$row.firstname} ({$row.loginname})</font></td>
      <td class="maintable">
      	{if !$row.standardsubdomain && !$row.aliasdomain}
      	<a href="{url module=domains action=delete id=$row.id}">{l10n get=SysCP.globallang.delete}</a>
      	{/if}
      </td>
      <td class="maintable">
      	<a href="{url module=domains action=edit id=$row.id}">{l10n get=SysCP.globallang.edit}</a>
      </td>
     </tr>
    {/foreach}
    {if 0 < $pages}<tr>
     <td colspan="20" class="maintable">{$paging}</td>
    </tr>{/if}
    {if $User.domains_used < $User.domains || $User.domains == '-1'}<tr>
     <td class="maintable" colspan="20">
     <a href="{url module=domains action=add}">{l10n get=SysCP.domains.add}</a></td>
    </tr>{/if}
   </table>
