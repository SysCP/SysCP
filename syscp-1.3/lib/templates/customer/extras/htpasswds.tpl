$header
    <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
     <tr>
      <td colspan="4" class="title">{$lng['menue']['extras']['directoryprotection']}</td>
     </tr>
     <tr>
      <td class="maintable">{$lng['login']['username']}</td><td class="maintable">{$lng['panel']['path']}</td><td class="maintable" colspan="2">&nbsp;</td>
     </tr>
     $htpasswds
     <tr>
      <td class="maintable" colspan="4"><a href="{$config->get('env.filename')}?page=htpasswds&action=add&s={$config->get('env.s')}">{$lng['extras']['directoryprotection_add']}</a></td>
     </tr>
    </table>
$footer
