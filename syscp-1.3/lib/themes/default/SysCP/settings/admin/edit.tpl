<form method="post" action="{url module=settings action=edit}">
     <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
      <tr>
       <td colspan="20" class="title">{l10n get=admin.serversettings} {l10n get=panel.edit}</td>
      </tr>
      <tr>
       <td class="maintable" align="right"><b>{l10n get=serversettings.session_timeout.title}:</b><br />{l10n get=serversettings.session_timeout.description}</td>
       <td class="maintable" nowrap><input type="text" name="session_sessiontimeout" value="{$Config->get('session.sessiontimeout')}"></td>
      </tr>
      <tr>
       <td class="maintable" align="right"><b>{l10n get=serversettings.maxloginattempts.title}:</b><br />{l10n get=serversettings.maxloginattempts.description}</td>
       <td class="maintable" nowrap><input type="text" name="login_maxloginattempts" value="{$Config->get('login.maxloginattempts')}"></td>
      </tr>
      <tr>
       <td class="maintable" align="right"><b>{l10n get=serversettings.deactivatetime.title}:</b><br />{l10n get=serversettings.deactivatetime.description}</td>
       <td class="maintable" nowrap><input type="text" name="login_deactivatetime" value="{$Config->get('login.deactivatetime')}"></td>
      </tr>
      <tr>
       <td class="maintable" align="right"><b>{l10n get=serversettings.accountprefix.title}:</b><br />{l10n get=serversettings.accountprefix.description}</td>
       <td class="maintable" nowrap><input type="text" name="customer_accountprefix" value="{$Config->get('customer.accountprefix')}"></td>
      </tr>
      <tr>
       <td class="maintable" align="right"><b>{l10n get=serversettings.mysqlprefix.title}:</b><br />{l10n get=serversettings.mysqlprefix.description} ({$Config->get('customer.accountprefix')}X{$Config->get('customer.mysqlprefix')}Y)</td>
       <td class="maintable" nowrap><input type="text" name="customer_mysqlprefix" value="{$Config->get('customer.mysqlprefix')}"></td>
      </tr>
      <tr>
       <td class="maintable" align="right"><b>{l10n get=serversettings.ftpprefix.title}:</b><br />{l10n get=serversettings.ftpprefix.description} ({$Config->get('customer.accountprefix')}X{$Config->get('customer.ftpprefix')}Y)</td>
       <td class="maintable" nowrap><input type="text" name="customer_ftpprefix" value="{$Config->get('customer.ftpprefix')}"></td>
      </tr>
      <tr>
       <td class="maintable" align="right"><b>{l10n get=serversettings.documentroot_prefix.title}:</b><br />{l10n get=serversettings.documentroot_prefix.description}</td>
       <td class="maintable" nowrap><input type="text" name="system_documentroot_prefix" value="{$Config->get('system.documentroot_prefix')}"></td>
      </tr>
      <tr>
       <td class="maintable" align="right">
         <b>{l10n get=serversettings.user_homedir.title}:</b><br />
         {l10n get=serversettings.user_homedir.description}
       </td>
       <td class="maintable" nowrap>
         <input type="text" name="system_user_homedir" value="{$Config->get('system.user_homedir')}">
       </td>
      </tr>
      <tr>
       <td class="maintable" align="right"><b>{l10n get=serversettings.apache_access_log.title}:</b><br />{l10n get=serversettings.apache_access_log.description}</td>
       <td class="maintable" nowrap><input type="text" name="system_apache_access_log" value="{$Config->get('system.apache_access_log')}"></td>
      </tr>
      <tr>
       <td class="maintable" align="right"><b>{l10n get=serversettings.apache_error_log.title}:</b><br />{l10n get=serversettings.apache_error_log.description}</td>
       <td class="maintable" nowrap><input type="text" name="system_apache_error_log" value="{$Config->get('system.apache_error_log')}"></td>
      </tr>
      <tr>
       <td class="maintable" align="right"><b>{l10n get=serversettings.ipaddress.title}:</b><br />{l10n get=serversettings.ipaddress.description}</td>
       <td class="maintable" nowrap><select name="system_ipaddress">
       {html_options options=$system_ipaddress selected=$Config->get('system.ipaddress')}</select></td>
      </tr>
      <tr>
       <td class="maintable" align="right"><b>{l10n get=serversettings.hostname.title}:</b><br />{l10n get=serversettings.hostname.description}</td>
       <td class="maintable" nowrap><input type="text" name="system_hostname" value="{$Config->get('system.hostname')}"></td>
      </tr>
      <tr>
       <td class="maintable" align="right"><b>{l10n get=serversettings.apacheconf_directory.title}:</b><br />{l10n get=serversettings.apacheconf_directory.description}</td>
       <td class="maintable" nowrap><input type="text" name="system_apacheconf_directory" value="{$Config->get('system.apacheconf_directory')}"></td>
      </tr>
      <tr>
       <td class="maintable" align="right"><b>{l10n get=serversettings.apacheconf_filename.title}:</b><br />{l10n get=serversettings.apacheconf_filename.description}</td>
       <td class="maintable" nowrap><input type="text" name="system_apacheconf_filename" value="{$Config->get('system.apacheconf_filename')}"></td>
      </tr>
      <tr>
       <td class="maintable" align="right"><b>{l10n get=serversettings.apachereload_command.title}:</b><br />{l10n get=serversettings.apachereload_command.description}</td>
       <td class="maintable" nowrap><input type="text" name="system_apachereload_command" value="{$Config->get('system.apachereload_command')}"></td>
      </tr>
      <tr>
       <td class="maintable" align="right"><b>{l10n get=serversettings.bindconf_directory.title}:</b><br />{l10n get=serversettings.bindconf_directory.description}</td>
       <td class="maintable" nowrap><input type="text" name="system_bindconf_directory" value="{$Config->get('system.bindconf_directory')}"></td>
      </tr>
      <tr>
       <td class="maintable" align="right"><b>{l10n get=serversettings.bindreload_command.title}:</b><br />{l10n get=serversettings.bindreload_command.description}</td>
       <td class="maintable" nowrap><input type="text" name="system_bindreload_command" value="{$Config->get('system.bindreload_command')}"></td>
      </tr>
      <tr>
       <td class="maintable" align="right"><b>{l10n get=serversettings.binddefaultzone.title}:</b><br />{l10n get=serversettings.binddefaultzone.description}</td>
       <td class="maintable" nowrap><input type="text" name="system_binddefaultzone" value="{$Config->get('system.binddefaultzone')}"></td>
      </tr>
      <tr>
       <td class="maintable" align="right"><b>{l10n get=serversettings.vmail_uid.title}:</b><br />{l10n get=serversettings.vmail_uid.description}</td>
       <td class="maintable" nowrap><input type="text" name="system_vmail_uid" value="{$Config->get('system.vmail_uid')}" maxlength="5"></td>
      </tr>
      <tr>
       <td class="maintable" align="right"><b>{l10n get=serversettings.vmail_gid.title}:</b><br />{l10n get=serversettings.vmail_gid.description}</td>
       <td class="maintable" nowrap><input type="text" name="system_vmail_gid" value="{$Config->get('system.vmail_gid')}" maxlength="5"></td>
      </tr>
      <tr>
       <td class="maintable" align="right"><b>{l10n get=serversettings.vmail_homedir.title}:</b><br />{l10n get=serversettings.vmail_homedir.description}</td>
       <td class="maintable" nowrap><input type="text" name="system_vmail_homedir" value="{$Config->get('system.vmail_homedir')}"></td>
      </tr>
      <tr>
       <td class="maintable" align="right"><b>{l10n get=serversettings.adminmail.title}:</b><br />{l10n get=serversettings.adminmail.description}</td>
       <td class="maintable" nowrap><input type="text" name="panel_adminmail" value="{$Config->get('panel.adminmail')}"></td>
      </tr>
      <tr>
       <td class="maintable" align="right"><b>{l10n get=serversettings.paging.title}:</b><br />{l10n get=serversettings.paging.description}</td>
       <td class="maintable" nowrap><input type="text" name="panel_paging" value="{$Config->get('panel.paging')}"></td>
      </tr>
      <tr>
       <td class="maintable" align="right"><b>{l10n get=login.language}:</b><br />{l10n get=serversettings.language.description}</td>
       <td class="maintable" nowrap><select name="panel_standardlanguage">
       {html_options options=$lang_list selected=$Config->get('panel.standardlanguage')}</select></td>
      </tr>
      <tr>
       <td class="maintable" align="right"><b>{l10n get=serversettings.pathedit.title}:</b><br />{l10n get=serversettings.pathedit.description}</td>
       <td class="maintable" nowrap><select name="panel_pathedit">
       {html_options options=$pathedit selected=$Config->get('panel.pathedit')}</select></td>
      </tr>
      <tr>
       <td class="maintable" align="right"><b>{l10n get=serversettings.phpmyadmin_url.title}:</b><br />{l10n get=serversettings.phpmyadmin_url.description}</td>
       <td class="maintable" nowrap><input type="text" name="panel_phpmyadmin_url" value="{$Config->get('panel.phpmyadmin_url')}"></td>
      </tr>
      <tr>
       <td class="maintable" align="right"><b>{l10n get=serversettings.webmail_url.title}:</b><br />{l10n get=serversettings.webmail_url.description}</td>
       <td class="maintable" nowrap><input type="text" name="panel_webmail_url" value="{$Config->get('panel.webmail_url')}"></td>
      </tr>
      <tr>
       <td class="maintable" align="right"><b>{l10n get=serversettings.webftp_url.title}:</b><br />{l10n get=serversettings.webftp_url.description}</td>
       <td class="maintable" nowrap><input type="text" name="panel_webftp_url" value="{$Config->get('panel.webftp_url')}"></td>
      </tr>
      <tr>
       <td class="maintable" colspan=2 align="right"><input type="hidden" name="send" value="send"><input type="submit" value="{l10n get=panel.save}"></td>
      </tr>
     </table>
    </form>
