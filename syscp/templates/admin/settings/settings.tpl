$header
    <form method="post" action="$filename">
     <input type="hidden" name="s" value="$s">
     <input type="hidden" name="page" value="$page">
     <input type="hidden" name="action" value="$action">
     <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
      <tr>
       <td colspan="20" class="title">{$lng['admin']['serversettings']} {$lng['panel']['edit']}</td>
      </tr>
      <tr>
       <td class="maintable" align="right"><b>{$lng['serversettings']['session_timeout']['title']}:</b><br />{$lng['serversettings']['session_timeout']['description']}</td>
       <td class="maintable" nowrap><input type="text" name="session_sessiontimeout" value="{$settings['session']['sessiontimeout']}"></td>
      </tr>
      <tr>
       <td class="maintable" align="right"><b>{$lng['serversettings']['maxloginattempts']['title']}:</b><br />{$lng['serversettings']['maxloginattempts']['description']}</td>
       <td class="maintable" nowrap><input type="text" name="login_maxloginattempts" value="{$settings['login']['maxloginattempts']}"></td>
      </tr>
      <tr>
       <td class="maintable" align="right"><b>{$lng['serversettings']['deactivatetime']['title']}:</b><br />{$lng['serversettings']['deactivatetime']['description']}</td>
       <td class="maintable" nowrap><input type="text" name="login_deactivatetime" value="{$settings['login']['deactivatetime']}"></td>
      </tr>
      <tr>
       <td class="maintable" align="right"><b>{$lng['serversettings']['accountprefix']['title']}:</b><br />{$lng['serversettings']['accountprefix']['description']}</td>
       <td class="maintable" nowrap><input type="text" name="customer_accountprefix" value="{$settings['customer']['accountprefix']}"></td>
      </tr>
      <tr>
       <td class="maintable" align="right"><b>{$lng['serversettings']['mysqlprefix']['title']}:</b><br />{$lng['serversettings']['mysqlprefix']['description']} ({$settings['customer']['accountprefix']}X{$settings['customer']['mysqlprefix']}Y)</td>
       <td class="maintable" nowrap><input type="text" name="customer_mysqlprefix" value="{$settings['customer']['mysqlprefix']}"></td>
      </tr>
      <tr>
       <td class="maintable" align="right"><b>{$lng['serversettings']['ftpprefix']['title']}:</b><br />{$lng['serversettings']['ftpprefix']['description']} ({$settings['customer']['accountprefix']}X{$settings['customer']['ftpprefix']}Y)</td>
       <td class="maintable" nowrap><input type="text" name="customer_ftpprefix" value="{$settings['customer']['ftpprefix']}"></td>
      </tr>
      <tr>
       <td class="maintable" align="right"><b>{$lng['serversettings']['documentroot_prefix']['title']}:</b><br />{$lng['serversettings']['documentroot_prefix']['description']}</td>
       <td class="maintable" nowrap><input type="text" name="system_documentroot_prefix" value="{$settings['system']['documentroot_prefix']}"></td>
      </tr>
      <tr>
       <td class="maintable" align="right"><b>{$lng['serversettings']['logfiles_directory']['title']}:</b><br />{$lng['serversettings']['logfiles_directory']['description']}</td>
       <td class="maintable" nowrap><input type="text" name="system_logfiles_directory" value="{$settings['system']['logfiles_directory']}"></td>
      </tr>
      <tr>
       <td class="maintable" align="right"><b>{$lng['serversettings']['ipaddress']['title']}:</b><br />{$lng['serversettings']['ipaddress']['description']}</td>
       <td class="maintable" nowrap><select name="system_ipaddress">$system_ipaddress</select></td>
      </tr>
      <tr>
       <td class="maintable" align="right"><b>{$lng['serversettings']['defaultip']['title']}:</b><br />{$lng['serversettings']['defaultip']['description']}</td>
       <td class="maintable" nowrap><select name="system_defaultip">$system_defaultip</select></td>
      </tr>
      <tr>
       <td class="maintable" align="right"><b>{$lng['serversettings']['hostname']['title']}:</b><br />{$lng['serversettings']['hostname']['description']}</td>
       <td class="maintable" nowrap><input type="text" name="system_hostname" value="{$settings['system']['hostname']}"></td>
      </tr>
      <tr>
       <td class="maintable" align="right"><b>{$lng['serversettings']['apacheconf_directory']['title']}:</b><br />{$lng['serversettings']['apacheconf_directory']['description']}</td>
       <td class="maintable" nowrap><input type="text" name="system_apacheconf_directory" value="{$settings['system']['apacheconf_directory']}"></td>
      </tr>
      <tr>
       <td class="maintable" align="right"><b>{$lng['serversettings']['apacheconf_filename']['title']}:</b><br />{$lng['serversettings']['apacheconf_filename']['description']}</td>
       <td class="maintable" nowrap><input type="text" name="system_apacheconf_filename" value="{$settings['system']['apacheconf_filename']}"></td>
      </tr>
      <tr>
       <td class="maintable" align="right"><b>{$lng['serversettings']['apachereload_command']['title']}:</b><br />{$lng['serversettings']['apachereload_command']['description']}</td>
       <td class="maintable" nowrap><input type="text" name="system_apachereload_command" value="{$settings['system']['apachereload_command']}"></td>
      </tr>
      <tr>
       <td class="maintable" align="right"><b>{$lng['serversettings']['bindconf_directory']['title']}:</b><br />{$lng['serversettings']['bindconf_directory']['description']}</td>
       <td class="maintable" nowrap><input type="text" name="system_bindconf_directory" value="{$settings['system']['bindconf_directory']}"></td>
      </tr>
      <tr>
       <td class="maintable" align="right"><b>{$lng['serversettings']['bindreload_command']['title']}:</b><br />{$lng['serversettings']['bindreload_command']['description']}</td>
       <td class="maintable" nowrap><input type="text" name="system_bindreload_command" value="{$settings['system']['bindreload_command']}"></td>
      </tr>
      <tr>
       <td class="maintable" align="right"><b>{$lng['serversettings']['binddefaultzone']['title']}:</b><br />{$lng['serversettings']['binddefaultzone']['description']}</td>
       <td class="maintable" nowrap><input type="text" name="system_binddefaultzone" value="{$settings['system']['binddefaultzone']}"></td>
      </tr>
      <tr>
       <td class="maintable" align="right"><b>{$lng['serversettings']['vmail_uid']['title']}:</b><br />{$lng['serversettings']['vmail_uid']['description']}</td>
       <td class="maintable" nowrap><input type="text" name="system_vmail_uid" value="{$settings['system']['vmail_uid']}" maxlength="5"></td>
      </tr>
      <tr>
       <td class="maintable" align="right"><b>{$lng['serversettings']['vmail_gid']['title']}:</b><br />{$lng['serversettings']['vmail_gid']['description']}</td>
       <td class="maintable" nowrap><input type="text" name="system_vmail_gid" value="{$settings['system']['vmail_gid']}" maxlength="5"></td>
      </tr>
      <tr>
       <td class="maintable" align="right"><b>{$lng['serversettings']['vmail_homedir']['title']}:</b><br />{$lng['serversettings']['vmail_homedir']['description']}</td>
       <td class="maintable" nowrap><input type="text" name="system_vmail_homedir" value="{$settings['system']['vmail_homedir']}"></td>
      </tr>
      <tr>
       <td class="maintable" align="right"><b>{$lng['serversettings']['adminmail']['title']}:</b><br />{$lng['serversettings']['adminmail']['description']}</td>
       <td class="maintable" nowrap><input type="text" name="panel_adminmail" value="{$settings['panel']['adminmail']}"></td>
      </tr>
      <tr>
       <td class="maintable" align="right"><b>{$lng['serversettings']['paging']['title']}:</b><br />{$lng['serversettings']['paging']['description']}</td>
       <td class="maintable" nowrap><input type="text" name="panel_paging" value="{$settings['panel']['paging']}"></td>
      </tr>
      <tr>
       <td class="maintable" align="right"><b>{$lng['login']['language']}:</b><br />{$lng['serversettings']['language']['description']}</td>
       <td class="maintable" nowrap><select name="panel_standardlanguage">$languages</select></td>
      </tr>
      <tr>
       <td class="maintable" align="right"><b>{$lng['serversettings']['pathedit']['title']}:</b><br />{$lng['serversettings']['pathedit']['description']}</td>
       <td class="maintable" nowrap><select name="panel_pathedit">$pathedit</select></td>
      </tr>
      <tr>
       <td class="maintable" align="right"><b>{$lng['serversettings']['phpmyadmin_url']['title']}:</b><br />{$lng['serversettings']['phpmyadmin_url']['description']}</td>
       <td class="maintable" nowrap><input type="text" name="panel_phpmyadmin_url" value="{$settings['panel']['phpmyadmin_url']}"></td>
      </tr>
      <tr>
       <td class="maintable" align="right"><b>{$lng['serversettings']['webmail_url']['title']}:</b><br />{$lng['serversettings']['webmail_url']['description']}</td>
       <td class="maintable" nowrap><input type="text" name="panel_webmail_url" value="{$settings['panel']['webmail_url']}"></td>
      </tr>
      <tr>
       <td class="maintable" align="right"><b>{$lng['serversettings']['webftp_url']['title']}:</b><br />{$lng['serversettings']['webftp_url']['description']}</td>
       <td class="maintable" nowrap><input type="text" name="panel_webftp_url" value="{$settings['panel']['webftp_url']}"></td>
      </tr>
      <tr>
       <td class="maintable" colspan=2 align="right"><input type="hidden" name="send" value="send"><input type="submit" value="{$lng['panel']['save']}"></td>
      </tr>
     </table>
    </form>
$footer
