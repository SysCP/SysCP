    <form method="post" action="{url module=lngeditor vendorname=$vendorname modulename=$modulename translateto=$translateto action=edit}">
        <input type="hidden" name="id" value="{$Config->get('env.id')}" />
        <table cellpadding="5" cellspacing="4" border="0" align="center" class="maintable">
            <tr>
                <td colspan="2" class="maintitle">
                    <img src="{$imagedir}title.gif" alt="" />&nbsp;{l10n get=SysCP.lngeditor.lng_edit}
                </td>
            </tr>
            <tr>
                <td class="main_field_name_50width">{$lngFrom}</td>
                <td class="main_field_display_40width">{$lngTo}</td>
            </tr>
            {foreach from=$english item=english_item key=english_key}
            {if is_array($english_item)}
                {foreach from=$english_item item=english_subitem key=english_subkey}
                <tr>
                    <td class="main_field_name_50width">{$english_subitem}</td>
                    <td class="main_field_display_40width">
                        <input type="text" name="{$vendorname}-{$modulename}-{$english_key}-{$english_subkey}" value="{$translate[$english_key][$english_subkey]}" style="width:100%" />
                </tr>
                {/foreach}
            {else}
            <tr>
                <td class="main_field_name_50width">{$english_item}</td>
                <td class="main_field_display_40width">
                    <input type="text" name="{$vendorname}-{$modulename}-{$english_key}" value="{$translate[$english_key]}" style="width:100%" />
            </tr>
            {/if}
            {/foreach}
            <tr>
                <td class="main_field_confirm" colspan="2">
                    <input type="hidden" name="send" value="send" />
                    <input class="bottom" type="submit" value="{l10n get=SysCP.globallang.save}" />
                </td>
            </tr>
        </table>
    </form>