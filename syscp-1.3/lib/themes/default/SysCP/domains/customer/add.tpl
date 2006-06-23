<form method="post" action="{url module=domains action=add}">
     <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
      <tr>
       <td colspan="2" class="title">{l10n get=domains.subdomain_add}</td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=domains.domainname}:</td>
       <td class="maintable" nowrap>
       <input type="text" name="subdomain" value="" size="15" maxlength="50"> <b>.</b>
       <select name="domain">
       {html_options options=$domains}
       </select></td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=domains.aliasdomain}:</td>
       <td class="maintable" nowrap><select name="alias">
       {html_options options=$aliasdomains}</select></td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=panel.path}:</td>
       <td class="maintable">{$documentrootPrefix}{$pathSelect}</td>
      </tr>
      <tr>
       <td class="maintable" colspan=2 align="right"><input type="hidden" name="send" value="send">
       <input type="submit" value="{l10n get=domains.subdomain_add}"></td>
      </tr>
     </table>
    </form>
