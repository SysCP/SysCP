{l10n get=SysCP.modulesmanager.modulesneedtobeenabled}:
<ul>
{foreach from=$failedEnable item=moduleEnable key=moduleEnable_key}
<li>{$moduleEnable}</li>
{/foreach}
</ul>
{l10n get=SysCP.modulesmanager.moduleversionsneeded}:
<ul>
{foreach from=$failedVersion item=moduleVersion key=moduleVersion_key}
<li>{$moduleVersion.2}{$moduleVersion.0}-{$moduleVersion.3} ({l10n get=SysCP.modulesmanager.installed}: {$moduleVersion.1})</li>
{/foreach}
</ul>