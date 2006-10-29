	<table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
		<tr>
			<td class="title" colspan="{$lang_num}">
				{l10n get=SysCP.lngeditor.lngeditor}
			</td>
		</tr>
		<tr>
			<td class="maintable">
				{l10n get=SysCP.globallang.name}
			</td>
            {foreach from=$languages item=language key=language_name}
			<td class="maintable">
                {$language}
			</td>
            {/foreach}
		</tr>
		{foreach from=$modules item=vendor key=vendor_name}
			{foreach from=$vendor item=module key=module_name}
			<tr>
				<td class="maintable">
					{$module.vendor}/{$module.name}
				</td>
				{foreach from=$languages item=language key=language_name}
				<td class="maintable">
					<a href="{url vendorname=$module.vendor modulename=$module.name lng=$language module=lngeditor action=edit}">
							{l10n get=SysCP.globallang.edit}
					</a>
				</td>
				{/foreach}
			</tr>
			{/foreach}
		{/foreach}
	</table>
