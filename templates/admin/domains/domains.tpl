$header
    <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
    <tr>
     <td colspan="20" class="title">{$lng['admin']['domains']}</td>
    </tr>
    <if ($userinfo['domains_used'] < $userinfo['domains'] || $userinfo['domains'] == '-1') && 15 < $userinfo['domains_used']><tr>
     <td class="maintable" colspan="20"><a href="$filename?page=$page&action=add&s=$s">{$lng['admin']['domain_add']}</a></td>
    </tr></if>
    <if 0 < $pages><tr>
     <td colspan="20" class="maintable">{$paging}</td>
    </tr></if>
    <tr>
     <td class="maintable">ID</td>
     <td class="maintable">Domain</td>
     <td class="maintable">{$lng['admin']['ipsandports']['ip']}</td>
     <td class="maintable">{$lng['admin']['customer']}</td>
     <td class="maintable" colspan="2">&nbsp;</td>
    </tr>
    $domains
    <if 0 < $pages><tr>
     <td colspan="20" class="maintable">{$paging}</td>
    </tr></if>
    <if $userinfo['domains_used'] < $userinfo['domains'] || $userinfo['domains'] == '-1'><tr>
     <td class="maintable" colspan="20"><a href="$filename?page=$page&action=add&s=$s">{$lng['admin']['domain_add']}</a></td>
    </tr></if>
   </table>
$footer
