	<table cellpadding="5" cellspacing="0" border="0" align="center" class="maintable">
		<tr>
			<td class="maintitle" colspan="7">
				<img src="{$imagedir}title.gif" alt="" />&nbsp;{l10n get=SysCP.modulesmanager.modulesmanager}
			</td>
		</tr>
		<tr>
			<td class="field_display_border_left">
				{l10n get=SysCP.globallang.name}
			</td>
			<td class="field_display">
				{l10n get=SysCP.modulesmanager.description}
			</td>
			<td class="field_display">
				{l10n get=SysCP.modulesmanager.vendor}
			</td>
			<td class="field_display">
				{l10n get=SysCP.modulesmanager.version}
			</td>
			<td class="field_display">
				&nbsp;
			</td>
            <td class="field_display">
                &nbsp;
            </td>
		</tr>
		{foreach from=$modules item=vendor key=vendor_name}
			{foreach from=$vendor item=module key=module_name}
			<tr>
				<td class="field_name_border_left">
					{$module.name}
				</td>
				<td class="field_name">
					{$module.description}
				</td>
				<td class="field_name">
					{$module.vendor}
				</td>
				<td class="field_name">
					{$module.version}
				</td>
                <td class="field_name">
                    <a href="{url vendorname=$module.vendor modulename=$module.name module=modulesmanager action=details}">
                        {l10n get=SysCP.modulesmanager.details}
                    </a>
                </td>
				<td class="field_name">
				{if $module.enabled == "true"}
					<a href="{url vendorname=$module.vendor modulename=$module.name module=modulesmanager action=disable}">
							{l10n get=SysCP.modulesmanager.disable}
					</a>
				{elseif $module.enabled == "core"}
				    {l10n get=SysCP.modulesmanager.core}
				{else}
					<a href="{url vendorname=$module.vendor modulename=$module.name module=modulesmanager action=enable}">
							{l10n get=SysCP.modulesmanager.enable}
					</a>
				{/if}
				</td>
			</tr>
			{/foreach}
		{/foreach}
	</table>
