    <table width="141" border="0" cellspacing="1" cellpadding="0">
      <tr>
        <td width="3"></td>
        <td width="123">&raquo; <a href="customer_index.php?s=$s">{$lng['menue']['main']['main']}</a></td>
      </tr>
      <tr>
        <td width="3"></td>
        <td>&nbsp;&nbsp;&nbsp;&raquo; <a href="customer_index.php?page=change_password&s=$s">{$lng['menue']['main']['changepassword']}</a></td>
      </tr>
      <tr>
        <td width="3"></td>
        <td>&nbsp;&nbsp;&nbsp;&raquo; <a href="customer_index.php?action=logout&s=$s">{$lng['login']['logout']}</a></td>
      </tr>
      <tr>
        <td width="3"></td>
        <td>&raquo; <a href="customer_email.php?s=$s">{$lng['menue']['email']['email']}</a></td>
      </tr>
      <tr>
        <td width="3"></td>
        <td>&nbsp;&nbsp;&nbsp;&raquo; <a href="customer_email.php?page=pop&s=$s">{$lng['menue']['email']['pop']}</a></td>
      </tr>
      <tr>
        <td width="3"></td>
        <td>&nbsp;&nbsp;&nbsp;&raquo; <a href="customer_email.php?page=forwarders&s=$s">{$lng['menue']['email']['forwarders']}</a></td>
      <if $settings['panel']['webmail_url'] != ''><tr>
        <td width="3"></td>
        <td>&nbsp;&nbsp;&nbsp;&raquo; <a href="{$settings['panel']['webmail_url']}" target="_blank">{$lng['menue']['email']['webmail']}</a></td>
      </tr></if>
      </tr>
      <tr>
        <td width="3"></td>
        <td>&raquo; <a href="customer_mysql.php?s=$s">{$lng['menue']['mysql']['mysql']}</a></td>
      </tr>
      <tr>
        <td width="3"></td>
        <td>&nbsp;&nbsp;&nbsp;&raquo; <a href="customer_mysql.php?page=mysqls&s=$s">{$lng['menue']['mysql']['databases']}</a></td>
      </tr>
      <if $settings['panel']['phpmyadmin_url'] != ''><tr>
        <td width="3"></td>
        <td>&nbsp;&nbsp;&nbsp;&raquo; <a href="{$settings['panel']['phpmyadmin_url']}" target="_blank">{$lng['menue']['mysql']['phpmyadmin']}</a></td>
      </tr></if>
      <tr>
        <td width="3"></td>
        <td>&raquo; <a href="customer_domains.php?s=$s">{$lng['menue']['domains']['domains']}</a></td>
      </tr>
      <tr>
        <td width="3"></td>
        <td>&nbsp;&nbsp;&nbsp;&raquo; <a href="customer_domains.php?page=domains&s=$s">{$lng['menue']['domains']['settings']}</a></td>
      </tr>
      <tr>
        <td width="3"></td>
        <td>&raquo; <a href="customer_ftp.php?s=$s">{$lng['menue']['ftp']['ftp']}</a></td>
      </tr>
      <tr>
        <td width="3"></td>
        <td>&nbsp;&nbsp;&nbsp;&raquo; <a href="customer_ftp.php?page=accounts&s=$s">{$lng['menue']['ftp']['accounts']}</a></td>
      </tr>
      <if $settings['panel']['webftp_url'] != ''><tr>
        <td width="3"></td>
        <td>&nbsp;&nbsp;&nbsp;&raquo; <a href="{$settings['panel']['webftp_url']}" target="_blank">{$lng['menue']['ftp']['webftp']}</a></td>
      </tr></if>
      <tr>
        <td width="3"></td>
        <td>&raquo; <a href="customer_extras.php?s=$s">{$lng['menue']['extras']['extras']}</a></td>
      </tr>
      <tr>
        <td width="3"></td>
        <td>&nbsp;&nbsp;&nbsp;&raquo; <a href="customer_extras.php?page=htpasswds&s=$s">{$lng['menue']['extras']['directoryprotection']}</a></td>
      </tr>
      <tr>
        <td width="3"></td>
        <td>&nbsp;&nbsp;&nbsp;&raquo; <a href="customer_extras.php?page=htaccess&s=$s">{$lng['menue']['extras']['pathoptions']}</a></td>
      </tr>
    </table>
