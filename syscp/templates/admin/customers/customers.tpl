$header
   <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
    <tr>
     <td colspan="20" class="title">{$lng['admin']['customers']}</td>
    </tr>
    <if ($userinfo['customers_used'] < $userinfo['customers'] || $userinfo['customers'] == '-1') && 15 < $userinfo['customers_used']><tr>
     <td colspan="20" class="maintable"><a href="$filename?page=$page&action=add&s=$s">{$lng['admin']['customer_add']}</a></td>
    </tr></if>
    <tr>
     <td class="maintable">ID&nbsp;&nbsp;<a href="admin_customers.php?page=customers&sortby=loginname&sortorder=desc&s=$s"><img src="images/order_desc.gif" border="0"/></a><a href="admin_customers.php?page=customers&sortby=loginname&sortorder=asc&s=$s"><img src="images/order_asc.gif" border="0"/></a></td>
     <if $userinfo['customers_see_all']><td class="maintable">{$lng['admin']['admin']}&nbsp;&nbsp;<a href="admin_customers.php?page=customers&sortby=adminid&sortorder=desc&s=$s"><img src="images/order_desc.gif" border="0"/></a><a href="admin_customers.php?page=customers&sortby=adminid&sortorder=asc&s=$s"><img src="images/order_asc.gif" border="0"/></a></td></if>
     <td class="maintable">{$lng['customer']['name']}</td>
     <td class="maintable">Space<br />Traffic</td>
     <td class="maintable">MySQL<br />FTP</td>
     <td class="maintable">eMails<br />Subdomains</td>
     <td class="maintable">Accounts<br />Forwarders</td>
     <td class="maintable">Active&nbsp;&nbsp;<a href="admin_customers.php?page=customers&sortby=deactivated&sortorder=desc&s=$s"><img src="images/order_desc.gif" border="0"/></a><a href="admin_customers.php?page=customers&sortby=deactivated&sortorder=asc&s=$s"><img src="images/order_asc.gif" border="0"/></a></td>
     <td class="maintable">&nbsp;</td>
    </tr>
    $customers
    <if $userinfo['customers_used'] < $userinfo['customers'] || $userinfo['customers'] == '-1'><tr>
     <td colspan="20" class="maintable"><a href="$filename?page=$page&action=add&s=$s">{$lng['admin']['customer_add']}</a></td>
    </tr></if>
   </table>
$footer
