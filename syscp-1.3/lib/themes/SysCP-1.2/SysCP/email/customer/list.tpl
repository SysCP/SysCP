<table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
  <tr>
    <td colspan="6" class="title">{l10n get=SysCP.email.emails}</td>
  </tr>
  <tr>
    <td class="maintable">{l10n get=SysCP.email.emailaddress}</td>
    <td class="maintable">{l10n get=SysCP.email.forwarders}</td>
    <td class="maintable">{l10n get=SysCP.email.account}</td>
    <td class="maintable">{l10n get=SysCP.email.catchall}</td>
    <td class="maintable" colspan="2">&nbsp;</td>
  </tr>
  {if ($User.emails_used < $User.emails || $User.emails == '-1') && 15 < $emailCount && $emaildomainCount !=0}
    <tr>
      <td class="maintable" colspan="6">
        <a href="{url module=email action=add}">
          {l10n get=SysCP.email.add}
        </a>
      </td>
    </tr>
  {/if}
  {foreach from=$emailTree item=domainList key=domain}
    <tr>
  	  <td class="maintable" colspan="6">&nbsp;&nbsp;&nbsp;&nbsp;<b>{$domain}</b></td>
    </tr>
    {foreach from=$domainList item=item}
      <tr>
        <td class="maintable">{$item.email_full}</td>
        <td class="maintable">{$item.destination}</td>
        <td class="maintable">
          {if $item.popaccountid != 0}
            {l10n get=SysCP.globallang.yes}
          {else}
            {l10n get=SysCP.globallang.no}
          {/if}
        </td>
        <td class="maintable">
          {if $item.iscatchall != 0}
            {l10n get=SysCP.globallang.yes}
          {else}
            {l10n get=SysCP.globallang.no}
          {/if}
        </td>
        <td class="maintable">
          <a href="{url module=email action=edit id=$item.id}">
            {l10n get=SysCP.globallang.edit}
          </a>
        </td>
        <td class="maintable">
          <a href="{url module=email action=delete id=$item.id}">
            {l10n get=SysCP.globallang.delete}
          </a>
        </td>
      </tr>
    {/foreach}
  {/foreach}
  {if ($User.emails_used < $User.emails || $User.emails == '-1') && $emaildomainCount != 0}
    <tr>
      <td class="maintable" colspan="6">
        <a href="{url module=email action=add}">
          {l10n get=SysCP.email.add}
        </a>
      </td>
    </tr>
  {/if}
</table>
