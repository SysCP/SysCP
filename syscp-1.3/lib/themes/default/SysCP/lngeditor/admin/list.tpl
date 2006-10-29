	<table cellpadding="5" cellspacing="0" border="0" align="center" class="maintable">
		<tr>
			<td class="maintitle" colspan="{$lang_num}">
				<img src="{$imagedir}title.gif" alt="" />&nbsp;{l10n get=SysCP.lngeditor.lngeditor}
			</td>
		</tr>
		<tr>
			<td class="field_display_border_left">
				{l10n get=SysCP.globallang.name}
			</td>
            {foreach from=$languages item=language key=language_name}
			<td class="field_display">
                {$language}
			</td>
            {/foreach}
		</tr>
		{foreach from=$modules item=vendor key=vendor_name}
			{foreach from=$vendor item=module key=module_name}
			<tr>
				<td class="field_name_border_left">
					{$module.vendor}/{$module.name}
				</td>
				{foreach from=$languages item=language key=language_name}
				<td class="field_name">
					<a href="{url vendorname=$module.vendor modulename=$module.name translateto=$language module=lngeditor action=edit}">
							{l10n get=SysCP.globallang.edit}
					</a>
				</td>
				{/foreach}
			</tr>
			{/foreach}
		{/foreach}
	</table>
