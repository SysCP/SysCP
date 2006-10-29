    <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
     <tr>
      <td colspan="4" class="title">{l10n get=SysCP.mysql.databases}</td>
     </tr>
     <tr>
      <td class="maintable">{l10n get=SysCP.mysql.db_name}</td>
      <td class="maintable">{l10n get=SysCP.mysql.db_desc}</td>
      <td class="maintable" colspan="2">&nbsp;</td>
     </tr>
     {if ($User.mysqls_used < $User.mysqls || $User.mysqls == '-1') && 15 < $mysqls_count}
     <tr>
      <td class="maintable" colspan="4">
        <a href="{url module=mysql action=add}">{l10n get=SysCP.mysql.db_create}</a></td>
     </tr>{/if}
     {foreach from=$mysqlList item=row}
     <tr>
      <td class="maintable">{$row.databasename}</td>
	  <td class="maintable">{$row.description}</td>
      <td class="maintable"><a href="{url module=mysql action=edit id=$row.id}">{l10n get=SysCP.globallang.edit}</a></td>
      <td class="maintable"><a href="{url module=mysql action=delete id=$row.id}">{l10n get=SysCP.globallang.delete}</a></td>
     </tr>
     {/foreach}
     {if ($User.mysqls_used < $User.mysqls || $User.mysqls == '-1')}
     <tr>
      <td class="maintable" colspan="4">
        <a href="{url module=mysql action=add}">{l10n get=SysCP.mysql.db_create}</a></td>
     </tr>{/if}
    </table>
