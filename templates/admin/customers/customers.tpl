$header
   <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
    <tr>
     <td colspan="20" class="title">{$lng['admin']['customers']}</td>
    </tr>
    <tr>
     <td colspan="20" class="maintable"><a href="$filename?page=$page&action=add&s=$s">{$lng['admin']['customer_add']}</a></td>
    </tr>
    <tr>
     <td class="maintable">ID</td>
     <td class="maintable">{$lng['customer']['name']}</td>
     <td class="maintable" nowrap>Space (MB)</td>
     <td class="maintable" nowrap>Traf (GB)</td>
     <td class="maintable">MySQL</td>
     <td class="maintable">POP</td>
     <td class="maintable">FWD</td>
     <td class="maintable">FTP</td>
     <td class="maintable">Active</td>
     <td class="maintable" colspan="2">&nbsp;</td>
    </tr>
    $customers
    <tr>
     <td class="maintable" colspan="20"><a href="$filename?page=$page&action=add&s=$s">{$lng['admin']['customer_add']}</a></td>
    </tr>
   </table>
$footer
