$header
   <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
    <tr>
     <td colspan="20" class="title">{$lng['admin']['admins']}</td>
    </tr>
    <tr>
     <td colspan="20" class="maintable"><a href="$filename?page=$page&action=add&s=$s">{$lng['admin']['admin_add']}</a></td>
    </tr>
    <tr>
     <td class="maintable">ID</td>
     <td class="maintable">{$lng['customer']['name']}</td>
     <td class="maintable">{$lng['admin']['customers']}<br >{$lng['admin']['domains']}</td>
     <td class="maintable">Space<br />Traffic</td>
     <td class="maintable">MySQL<br />FTP</td>
     <td class="maintable">eMails<br />Subdomains</td>
     <td class="maintable">Accounts<br />Forwarders</td>
     <td class="maintable">Active</td>
     <td class="maintable">&nbsp;</td>
    </tr>
    $admins
    <tr>
     <td colspan="20" class="maintable"><a href="$filename?page=$page&action=add&s=$s">{$lng['admin']['admin_add']}</a></td>
    </tr>
   </table>
$footer
