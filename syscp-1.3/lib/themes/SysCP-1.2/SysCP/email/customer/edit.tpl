     <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
      <tr>
       <td colspan="2" class="title">{l10n get=SysCP.email.edit}</td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=SysCP.email.emailaddress}:</td>
       <td class="maintable" nowrap>{$result.email_full}</td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=SysCP.email.account}:</td>
       <td class="maintable">
       {if $result.popaccountid != 0}
         {l10n get=SysCP.globallang.yes}
         [<a href="{url module=email action=changePassword id=$result.id}">
           {l10n get=SysCP.globallang.changepassword}
         </a>]
         [<a href="{url module=email action=deleteAccount id=$result.id}">
           {l10n get=SysCP.email.account_delete}
         </a>]
       {else}
         {l10n get=SysCP.globallang.no}
         [<a href="{url module=email action=addAccount id=$result.id}">
           {l10n get=SysCP.email.account_add}
         </a>]
       {/if}
       </td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=SysCP.email.catchall}:</td>
       <td class="maintable">
       {if $result.iscatchall != 0}
         {l10n get=SysCP.globallang.yes}
       {else}
         {l10n get=SysCP.globallang.no}
       {/if}
       [<a href="{url module=email action=togglecatchall id=$result.id'}">
         {l10n get=SysCP.globallang.toggle}
       </a>]
       </td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=SysCP.email.forwarders}:</td>
       <td class="maintable">
       {foreach from=$result.destination item=destination key=destinationKey}
         {$destination} [<a href="{url module=email action=deleteForwarder id=$result.id forwarderid=$destinationKey}">{l10n get=SysCP.globallang.delete}</a>]<br/>
       {/foreach}
       <a href="{url module=email action=addForwarder id=$result.id}">
         {l10n get=SysCP.email.forwarder_add}
       </a>
       </td>
      </tr>
     </table>
