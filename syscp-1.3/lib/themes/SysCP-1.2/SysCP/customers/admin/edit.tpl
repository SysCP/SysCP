    <form method="post" action="{url module=customers action=edit}">
     <input type="hidden" name="id" value="{$Config->get('env.id')}">
     <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
      <tr>
       <td colspan="20" class="title">{l10n get=SysCP.customers.customer_edit}</td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=SysCP.globallang.username}:</td>
       <td class="maintable" nowrap>{$result.loginname}</td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=SysCP.customers.documentroot}:</td>
       <td class="maintable" nowrap>{$result.homedir}</td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=SysCP.globallang.name}: *</td>
       <td class="maintable" nowrap><input type="text" name="name" value="{$result.name}"></td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=SysCP.customers.firstname}: *</td>
       <td class="maintable" nowrap><input type="text" name="firstname" value="{$result.firstname}"></td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=SysCP.customers.company}:</td>
       <td class="maintable" nowrap><input type="text" name="company" value="{$result.company}"></td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=SysCP.customers.street}:</td>
       <td class="maintable" nowrap><input type="text" name="street" value="{$result.street}"></td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=SysCP.customers.zipcode}:</td>
       <td class="maintable" nowrap><input type="text" name="zipcode" value="{$result.zipcode}"></td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=SysCP.customers.city}:</td>
       <td class="maintable" nowrap><input type="text" name="city" value="{$result.city}"></td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=SysCP.customers.phone}:</td>
       <td class="maintable" nowrap><input type="text" name="phone" value="{$result.phone}"></td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=SysCP.customers.fax}:</td>
       <td class="maintable" nowrap><input type="text" name="fax" value="{$result.fax}"></td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=SysCP.customers.email}: *</td>
       <td class="maintable" nowrap><input type="text" name="email" value="{$result.email}"></td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=SysCP.customers.number}:</td>
       <td class="maintable" nowrap><input type="text" name="customernumber" value="{$result.customernumber}"></td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=SysCP.customers.language}:</td>
       <td class="maintable" nowrap><select name="def_language">
       {html_options options=$lang_list selected=$result.def_language}</select></td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=SysCP.customers.diskspace}: *</td>
       <td class="maintable" nowrap><input type="text" name="diskspace" value="{$result.diskspace}" maxlength="6"></td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=SysCP.customers.traffic}: *</td>
       <td class="maintable" nowrap><input type="text" name="traffic" value="{$result.traffic}" maxlength="3"></td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=SysCP.customers.subdomains}: *</td>
       <td class="maintable" nowrap><input type="text" name="subdomains" value="{$result.subdomains}" maxlength="9"></td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=SysCP.customers.emails}: *</td>
       <td class="maintable" nowrap><input type="text" name="emails" value="{$result.emails}" maxlength="9"></td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=SysCP.customers.email_accounts}: *</td>
       <td class="maintable" nowrap><input type="text" name="email_accounts" value="{$result.email_accounts}" maxlength="9"></td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=SysCP.customers.email_forwarders}: *</td>
       <td class="maintable" nowrap><input type="text" name="email_forwarders" value="{$result.email_forwarders}" maxlength="9"></td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=SysCP.customers.ftp}: *</td>
       <td class="maintable" nowrap><input type="text" name="ftps" value="{$result.ftps}" maxlength="9"></td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=SysCP.customers.mysql}: *</td>
       <td class="maintable" nowrap><input type="text" name="mysqls" value="{$result.mysqls}" maxlength="9"></td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=SysCP.customers.stdsubdomain_add}?<br />({$result.loginname}.{$Config->get('system.hostname')})</td>
       <td class="maintable" nowrap>
       {html_radios name="createstdsubdomain" options=$createstdsubdomain selected=$createstdsubdomain_sel}
       </td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=SysCP.customers.deactivate_user}?</td>
       <td class="maintable" nowrap>
       {html_radios name="deactivated" options=$deactivated selected=$result.deactivated}
       </td>
      </tr>
      <tr>
       <td class="maintable" nowrap>{l10n get=SysCP.globallang.password} ({l10n get=SysCP.globallang.emptyfornochanges}):</td>
       <td class="maintable" nowrap><input type="password" name="newpassword" value=""></td>
      </tr>
      <tr>
       <td class="maintable" colspan=2 align="right"><input type="hidden" name="send" value="send"><input type="submit" value="{l10n get=SysCP.globallang.save}"></td>
      </tr>
     </table>
    </form>
