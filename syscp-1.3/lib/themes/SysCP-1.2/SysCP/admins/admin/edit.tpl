    <form method="post" action="{url module=admins action=edit}">
     <input type="hidden" name="id" value="{$Config->get('env.id')}">
     <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
      <tr>
       <td colspan="20" class="title">{l10n get=SysCP.admins.admin_edit}</td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=SysCP.globallang.username}:</td>
       <td class="maintable" nowrap>{$result.loginname}</td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=SysCP.globallang.name}: *</td>
       <td class="maintable" nowrap><input type="text" name="name" value="{$result.name}"></td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=SysCP.admins.email}: *</td>
       <td class="maintable" nowrap><input type="text" name="email" value="{$result.email}"></td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=SysCP.admins.language}:</td>
       <td class="maintable" nowrap><select name="def_language">
       {html_options options=$lang_list selected=$result.def_language}</select></td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=SysCP.admins.change_server_settings}</td>
       <td class="maintable" nowrap>
       {html_radios name="change_serversettings" options=$change_serversettings selected=$result.change_serversettings}
       </td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=SysCP.admins.customers}: *</td>
       <td class="maintable" nowrap><input type="text" name="customers" value="{$result.customers}" maxlength="9"></td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=SysCP.admins.see_all_customers}</td>
       <td class="maintable" nowrap>
       {html_radios name="customers_see_all" options=$customers_see_all selected=$result.customers_see_all}
       </td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=SysCP.admins.domains}: *</td>
       <td class="maintable" nowrap><input type="text" name="domains" value="{$result.domains}" maxlength="9"></td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=SysCP.admins.see_all_domains}</td>
       <td class="maintable" nowrap>
       {html_radios name="domains_see_all" options=$domains_see_all selected=$result.domains_see_all}
       </td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=SysCP.admins.diskspace}: *</td>
       <td class="maintable" nowrap><input type="text" name="diskspace" value="{$result.diskspace}" maxlength="6"></td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=SysCP.admins.traffic}: *</td>
       <td class="maintable" nowrap><input type="text" name="traffic" value="{$result.traffic}" maxlength="3"></td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=SysCP.admins.subdomains}: *</td>
       <td class="maintable" nowrap><input type="text" name="subdomains" value="{$result.subdomains}" maxlength="9"></td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=SysCP.admins.emails}: *</td>
       <td class="maintable" nowrap><input type="text" name="emails" value="{$result.emails}" maxlength="9"></td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=SysCP.admins.email_accounts}: *</td>
       <td class="maintable" nowrap><input type="text" name="email_accounts" value="{$result.email_accounts}" maxlength="9"></td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=SysCP.admins.email_forwarders}: *</td>
       <td class="maintable" nowrap><input type="text" name="email_forwarders" value="{$result.email_forwarders}" maxlength="9"></td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=SysCP.admins.ftp}: *</td>
       <td class="maintable" nowrap><input type="text" name="ftps" value="{$result.ftps}" maxlength="9"></td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=SysCP.admins.mysql}: *</td>
       <td class="maintable" nowrap><input type="text" name="mysqls" value="{$result.mysqls}" maxlength="9"></td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=SysCP.admins.deactivate_user}?</td>
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
