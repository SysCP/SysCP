$header
   <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
    <tr>
     <td colspan="20" class="title">{$lng['admin']['customers']}</td>
    </tr>
    <if ($userinfo['customers_used'] < $userinfo['customers'] || $userinfo['customers'] == '-1') && 15 < $userinfo['customers_used']><tr>
     <td colspan="20" class="maintable"><a href="$filename?page=$page&action=add&s=$s">{$lng['admin']['customer_add']}</a></td>
    </tr></if>
    <tr>
     <td class="maintable">ID</td>
     <td class="maintable">{$lng['customer']['name']}</td>
     <td class="maintable">Space<br />Traffic</td>
     <td class="maintable">MySQL<br />FTP</td>
     <td class="maintable">eMails<br />Subdomains</td>
     <td class="maintable">Accounts<br />Forwarders</td>
     <td class="maintable">Active</td>
     <td class="maintable">&nbsp;</td>
    </tr>
    $customers
    <if $userinfo['customers_used'] < $userinfo['customers'] || $userinfo['customers'] == '-1'><tr>
     <td colspan="20" class="maintable"><a href="$filename?page=$page&action=add&s=$s">{$lng['admin']['customer_add']}</a></td>
    </tr></if>
   </table>
$footer
