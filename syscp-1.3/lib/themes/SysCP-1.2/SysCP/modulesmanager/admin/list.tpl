	<table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
		<tr>
			<td class="title" colspan="7">
				{l10n get=SysCP.modulesmanager.modulesmanager}
			</td>
		</tr>
		<tr>
			<td class="maintable">
				{l10n get=SysCP.globallang.name}
			</td>
			<td class="maintable">
				{l10n get=SysCP.modulesmanager.description}
			</td>
			<td class="maintable">
				{l10n get=SysCP.modulesmanager.vendor}
			</td>
			<td class="maintable">
				{l10n get=SysCP.modulesmanager.version}
			</td>
			<td class="maintable">
				&nbsp;
			</td>
            <td class="maintable">
                &nbsp;
            </td>
		</tr>
		{foreach from=$modules item=vendor key=vendor_name}
			{foreach from=$vendor item=module key=module_name}
			<tr>
				<td class="maintable">
					{$module.name}
				</td>
				<td class="maintable">
					{$module.description}
				</td>
				<td class="maintable">
					{$module.vendor}
				</td>
				<td class="maintable">
					{$module.version}
				</td>
                <td class="maintable">
                    <a href="{url vendorname=$module.vendor modulename=$module.name module=modulesmanager action=details}">
                        {l10n get=SysCP.modulesmanager.details}
                    </a>
                </td>
				<td class="maintable">
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
