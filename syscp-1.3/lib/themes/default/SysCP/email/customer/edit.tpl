	<table cellpadding="5" cellspacing="0" border="0" align="center" class="maintable_60">
		<tr>
			<td colspan="2" class="maintitle">
				<img src="{$imagedir}title.gif" alt="" />&nbsp;{l10n get=SysCP.email.edit}
			</td>
		</tr>
		<tr>
			<td class="field_display_border_left">{l10n get=SysCP.email.emailaddress}:</td>
			<td class="field_name" nowrap="nowrap">{$result.email_full}</td>
		</tr>
		<tr>
			<td class="field_display_border_left">{l10n get=SysCP.email.account}:</td>
			<td class="field_name" nowrap="nowrap">
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
			<td class="field_display_border_left">{l10n get=SysCP.email.catchall}:</td>
			<td class="field_name">
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
			<td class="field_display_border_left">{l10n get=SysCP.email.forwarders}:</td>
			<td class="field_name">
				{foreach from=$result.destination item=destination key=destinationKey}
				{$destination} [<a href="{url module=email action=deleteForwarder id=$result.id forwarderid=$destinationKey}">{l10n get=SysCP.globallang.delete}</a>]<br/>
				{/foreach}
				<a href="{url module=email action=addForwarder id=$result.id}">
					{l10n get=SysCP.email.forwarder_add}
				</a>
			</td>
		</tr>
	</table>
