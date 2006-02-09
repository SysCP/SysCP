$header
    <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
     <tr>
      <td colspan="8" class="title">{$lng['menue']['extras']['pathoptions']}</td>
     </tr>
     <tr>
      <td class="maintable">{$lng['panel']['path']}</td>
      <td class="maintable">{$lng['extras']['view_directory']}</td>
      <td class="maintable">{$lng['extras']['error404path']}</td>
      <td class="maintable">{$lng['extras']['error403path']}</td>
      <td class="maintable">{$lng['extras']['error500path']}</td>
      <td class="maintable" colspan="2">&nbsp;</td>
     </tr>
     $htaccess
     <tr>
      <td class="maintable" colspan="8"><a href="$filename?page=htaccess&action=add&s=$s">{$lng['extras']['pathoptions_add']}</a></td>
     </tr>
    </table>
$footer
