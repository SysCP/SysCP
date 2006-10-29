   <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
    <tr>
     <td colspan="20" class="title">{l10n get=SysCP.admins.admins}</td>
    </tr>
    <tr>
     <td colspan="20" class="maintable"><a href="{url module=admins action=add}">{l10n get=SysCP.admins.admin_add}</a></td>
    </tr>
    {if 0 < $pages}<tr>
     <td colspan="20" class="maintable">{$paging}</td>
    </tr>{/if}
    <tr>
     <td class="maintable">ID</td>
     <td class="maintable">{l10n get=SysCP.globallang.name}</td>
     <td class="maintable">{l10n get=SysCP.admins.customers}<br >{l10n get=SysCP.admins.domains}</td>
     <td class="maintable">{l10n get=SysCP.admins.space}<br />{l10n get=SysCP.admins.traffic}</td>
     <td class="maintable">{l10n get=SysCP.admins.mysql}<br />{l10n get=SysCP.admins.ftp}</td>
     <td class="maintable">{l10n get=SysCP.admins.emails}<br />{l10n get=SysCP.admins.subdomains}</td>
     <td class="maintable">{l10n get=SysCP.admins.email_accounts}<br />{l10n get=SysCP.admins.email_forwarders}</td>
     <td class="maintable">{l10n get=SysCP.admins.active}</td>
     <td class="maintable">&nbsp;</td>
    </tr>

    {foreach from=$admin_list item=row}
     <tr>
      <td class="maintable">{$row.loginname}</td>
      <td class="maintable">{$row.name}</td>
      <td class="maintable">{$row.customers_used}/{$row.customers}<br />{$row.domains_used}/{$row.domains}</td>
      <td class="maintable">{$row.diskspace_used}/{$row.diskspace} (MB)<br />{$row.traffic_used}/{$row.traffic} (GB)</td>
      <td class="maintable">{$row.mysqls_used}/{$row.mysqls}<br />{$row.ftps_used}/{$row.ftps}</td>
      <td class="maintable">{$row.emails_used}/{$row.emails}<br />{$row.subdomains_used}/{$row.subdomains}</td>
      <td class="maintable">{$row.email_accounts_used}/{$row.email_accounts}<br />{$row.email_forwarders_used}/{$row.email_forwarders}</td>
      <td class="maintable">{$row.deactivated}</td>
      <td class="maintable"><a href="{url action=delete id=$row.adminid module=admins}">{l10n get=SysCP.globallang.delete}</a><br />
      <a href="{url action=edit id=$row.adminid module=admins}">{l10n get=SysCP.globallang.edit}</a></td>
     </tr>
     {/foreach}

     {if 0 < $pages}<tr>
     <td colspan="20" class="maintable">{$paging}</td>
    </tr>{/if}
    <tr>
     <td colspan="20" class="maintable"><a href="{url module=admins action=add}">{l10n get=SysCP.admins.admin_add}</a></td>
    </tr>
   </table>