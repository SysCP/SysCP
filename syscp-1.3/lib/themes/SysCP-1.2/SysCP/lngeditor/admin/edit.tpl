    <form method="post" action="{url module=lngeditor vendorname=$vendorname modulename=$modulename translateto=$translateto action=edit}">
        <input type="hidden" name="id" value="{$Config->get('env.id')}">
        <table cellpadding="5" cellspacing="1" border="0" align="center" class="maintable">
            <tr>
                <td colspan="2" class="title">
                    {l10n get=SysCP.lngeditor.lng_edit}
                </td>
            </tr>
            <tr>
                <td class="maintable">{$lngFrom}</td>
                <td class="maintable">{$lngTo}</td>
            </tr>
            {foreach from=$english item=english_item key=english_key}
            {if is_array($english_item)}
                {foreach from=$english_item item=english_subitem key=english_subkey}
                <tr>
                    <td class="maintable">{$english_subitem}</td>
                    <td class="maintable">
                        <input type="text" name="{$vendorname}-{$modulename}-{$english_key}-{$english_subkey}" value="{$translate[$english_key][$english_subkey]}" length="200" />
                </tr>
                {/foreach}
            {else}
            <tr>
                <td class="maintable">{$english_item}</td>
                <td class="maintable">
                    <input type="text" name="{$vendorname}-{$modulename}-{$english_key}" value="{$translate[$english_key]}" length="200" />
            </tr>
            {/if}
            {/foreach}
            <tr>
                <td class="maintable" colspan="2">
                    <input type="hidden" name="send" value="send" />
                    <input type="submit" value="{l10n get=SysCP.globallang.save}" />
                </td>
            </tr>
        </table>
    </form>