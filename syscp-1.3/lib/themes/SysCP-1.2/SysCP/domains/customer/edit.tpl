    <form method="post" action="{url module=domains action=edit}">
     <input type="hidden" name="id" value="{$Config->get('env.id')}">
     <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
      <tr>
       <td colspan="2" class="title">{l10n get=SysCP.domains.subdomain_edit}</td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=SysCP.domains.domainname}:</td>
       <td class="maintable" nowrap>{$result.domain}</td>
      </tr>
      {if $alias_check == '0'}<tr>
       <td class="maintable">{l10n get=SysCP.domains.aliasdomain}:</td>
       <td class="maintable" nowrap><select name="alias">
       {html_options options=$domains selected=$result.aliasdomain}</select></td>
      </tr>{/if}
      <tr>
       <td class="maintable">{l10n get=SysCP.globallang.path}:</td>
       <td class="maintable">{$documentrootPrefix}{$pathSelect}</td>
      </tr>
      {if $result.parentdomainid == '0' && $User.subdomains != '0' }<tr>
       <td class="maintable">{l10n get=SysCP.domains.wildcarddomain}</td>
       <td class="maintable">{html_radios name=iswildcarddomain options=$iswildcarddomain selected=$result.iswildcarddomain}</td>
      </tr>{/if}
      {if $result.subcanemaildomain == '1' && $result.parentdomainid != '0'}<tr>
       <td class="maintable" nowrap>{l10n get=SysCP.domains.emaildomain}:</td>
       <td class="maintable" nowrap>{html_radios name=isemaildomain options=$isemaildomain selected=$result.isemaildomain}</td>
      </tr>{/if}
      <tr>
       <td class="maintable" colspan="2" align="right"><input type="hidden" name="send" value="send"><input type="submit" value="{l10n get=SysCP.globallang.save}"></td>
      </tr>
     </table>
    </form>
