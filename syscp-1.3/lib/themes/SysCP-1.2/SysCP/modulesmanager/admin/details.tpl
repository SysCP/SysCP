    <table cellpadding="5" cellspacing="4" border="0" align="center" class="maintable">
        <tr>
            <td colspan="2" class="title">
                {l10n get=SysCP.modulesmanager.modulesmanager} &raquo; {$module.vendor}/{$module.name}
            </td>
        </tr>
        <tr>
            <td class="maintable">{l10n get=SysCP.globallang.name}</td>
            <td class="maintable" nowrap="nowrap">{$module.name}</td>
        </tr>
        <tr>
            <td class="maintable">{l10n get=SysCP.modulesmanager.version}</td>
            <td class="maintable" nowrap="nowrap">{$module.version}</td>
        </tr>
        {if $module.description != ""}
        <tr>
            <td class="maintable">{l10n get=SysCP.modulesmanager.description}</td>
            <td class="maintable" nowrap="nowrap">{$module.description}</td>
        </tr>
        {/if}
        <tr>
            <td class="maintable">{l10n get=SysCP.modulesmanager.vendor}</td>
            <td class="maintable" nowrap="nowrap">{$module.vendor}</td>
        </tr>
        {if $module.site != ""}
        <tr>
            <td class="maintable">{l10n get=SysCP.modulesmanager.site}</td>
            <td class="maintable" nowrap="nowrap"><a href="{$module.site}">{$module.site}</a></td>
        </tr>
        {/if}
        {if $module.author != ""}
        <tr>
            <td class="maintable">{l10n get=SysCP.modulesmanager.author}</td>
            <td class="maintable" nowrap="nowrap">{$module.author}</td>
        </tr>
        {/if}
        {if $module.deps != ""}
        <tr>
            <td class="maintable">{l10n get=SysCP.modulesmanager.requiredmodules}</td>
            <td class="maintable" nowrap="nowrap">{$module.deps}</td>
        </tr>
        {/if}
        <tr>
            <td class="maintable">{l10n get=SysCP.modulesmanager.status}:</td>
            {if $module.enabled == "core"}
                <td class="maintable" nowrap="nowrap">{l10n get=SysCP.modulesmanager.core}</td>
            {elseif $module.enabled == "true"}
                <td class="maintable" nowrap="nowrap">{l10n get=SysCP.modulesmanager.enabled} (<a href="{url vendorname=$module.vendor modulename=$module.name module=modulesmanager action=disable}">{l10n get=SysCP.modulesmanager.disable}</a>)</td>
            {else}
                <td class="maintable" nowrap="nowrap">{l10n get=SysCP.modulesmanager.disabled} (<a href="{url vendorname=$module.vendor modulename=$module.name module=modulesmanager action=enable}">{l10n get=SysCP.modulesmanager.enable}</a>)</td>
            {/if}
        </tr>
    </table>