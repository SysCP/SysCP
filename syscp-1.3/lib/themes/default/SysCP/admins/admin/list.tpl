   <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
    <tr>
     <td colspan="20" class="title">{l10n get=admin.admins}</td>
    </tr>
    <tr>
     <td colspan="20" class="maintable"><a href="{url module=admins action=add}">{l10n get=admin.admin_add}</a></td>
    </tr>
    {if 0 < $pages}<tr>
     <td colspan="20" class="maintable">{$paging}</td>
    </tr>{/if}
    <tr>
     <td class="maintable">ID</td>
     <td class="maintable">{l10n get=customer.name}</td>
     <td class="maintable">{l10n get=admin.customers}<br >{l10n get=admin.domains}</td>
     <td class="maintable">Space<br />Traffic</td>
     <td class="maintable">MySQL<br />FTP</td>
     <td class="maintable">eMails<br />Subdomains</td>
     <td class="maintable">Accounts<br />Forwarders</td>
     <td class="maintable">Active</td>
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
      <td class="maintable"><a href="{url action=delete id=$row.adminid module=admins}">{l10n get=panel.delete}</a><br />
      <a href="{url action=edit id=$row.adminid module=admins}">{l10n get=panel.edit}</a></td>
     </tr>
     {/foreach}

     {if 0 < $pages}<tr>
     <td colspan="20" class="maintable">{$paging}</td>
    </tr>{/if}
    <tr>
     <td colspan="20" class="maintable"><a href="{url module=admins action=add}">{l10n get=admin.admin_add}</a></td>
    </tr>
   </table>