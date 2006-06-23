     <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
      <tr>
       <td class="title">{l10n get=admin.configfiles.serverconfiguration}</td>
      </tr>
      {foreach from=$configfiles item=dist_details key=dist_name}
  <tr>
  <td class="maintable">
   <table cellpadding="0" cellspacing="0" border="0" align="left" class="maintable">
    <tr>
     <td class="maintable">&raquo;&nbsp;{$dist_details.label}</td>
    </tr>
    <tr>
     <td class="maintable">
     {foreach from=$dist_details.daemons key=daemon_name item=daemon}
&nbsp;&nbsp;&nbsp;&raquo;&nbsp;
<a href="{url distribution=$dist_name daemon=$daemon_name module=configfiles action=list}">{$daemon.label}</a><br />
{/foreach}
     </td>
    </tr>
   </table>
  </td>
 </tr>
 {/foreach}

 </table>
