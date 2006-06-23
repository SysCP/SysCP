     <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
      <tr>
       <td colspan="2" class="title">{l10n get=emails.emails_edit}</td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=emails.emailaddress}:</td>
       <td class="maintable" nowrap>{$result.email_full}</td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=emails.account}:</td>
       <td class="maintable">
       {if $result.popaccountid != 0}
         {l10n get=panel.yes}
         [<a href="{url module=email action=changePassword id=$result.id}">
           {l10n get=menue.main.changepassword}
         </a>]
         [<a href="{url module=email action=deleteAccount id=$result.id}">
           {l10n get=emails.account_delete}
         </a>]
       {else}
         {l10n get=panel.no}
         [<a href="{url module=email action=addAccount id=$result.id}">
           {l10n get=emails.account_add}
         </a>]
       {/if}
       </td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=emails.catchall}:</td>
       <td class="maintable">
       {if $result.iscatchall != 0}
         {l10n get=panel.yes}
       {else}
         {l10n get=panel.no}
       {/if}
       [<a href="{url module=email action=togglecatchall id=$result.id'}">
         {l10n get=panel.toggle}
       </a>]
       </td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=emails.forwarders}:</td>
       <td class="maintable">
       {foreach from=$result.destination item=destination key=destinationKey}
         {$destination} [<a href="{url module=email action=deleteForwarder id=$result.id forwarderid=$destinationKey}">{l10n get=panel.delete}</a>]<br/>
       {/foreach}
       <a href="{url module=email action=addForwarder id=$result.id}">
         {l10n get=emails.forwarder_add}
       </a>
       </td>
      </tr>
     </table>
