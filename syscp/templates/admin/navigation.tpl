    <table width="141" border="0" cellspacing="1" cellpadding="0">
      <tr>
        <td width="3"></td>
        <td width="123">&raquo; <a href="admin_index.php?page=overview&s=$s">{$lng['admin']['overview']}</a></td>
      </tr>
      <tr>
        <td width="3"></td>
        <td width="123">&raquo; <a href="admin_index.php?page=change_password&s=$s">{$lng['menue']['main']['changepassword']}</a></td>
      </tr>
      <if $userinfo['customers'] != 0><tr>
        <td width="3"></td>
        <td width="123">&raquo; <a href="admin_customers.php?page=customers&s=$s">{$lng['admin']['customers']}</a></td>
      </tr></if>
      <if $userinfo['domains'] != 0><tr>
        <td width="3"></td>
        <td width="123">&raquo; <a href="admin_domains.php?page=domains&s=$s">{$lng['admin']['domains']}</a></td>
      </tr></if>
      <if $userinfo['change_serversettings'] == 1><tr>
        <td width="3"></td>
        <td width="123">&raquo; <a href="admin_admins.php?page=admins&s=$s">{$lng['admin']['admins']}</a></td>
      </tr>
      <tr>
        <td width="3"></td>
        <td width="123">&raquo; <a href="admin_settings.php?page=settings&s=$s">{$lng['admin']['serversettings']}</a></td>
      </tr></if>
      <tr>
        <td width="3"></td>
        <td width="123">&raquo; <a href="admin_index.php?action=logout&s=$s">{$lng['login']['logout']}</a></td>
      </tr>
    </table>
