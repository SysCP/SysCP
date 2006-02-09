$header
    <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
     <tr>
      <td colspan="4" class="title">{$lng['domains']['domainsettings']}</td>
     <tr>
      <td class="maintable">{$lng['domains']['domainname']}</td><td class="maintable">{$lng['panel']['path']}</td><td class="maintable" colspan="2">&nbsp;</td>
     </tr>
     </tr>
     <if ($userinfo['subdomains_used'] < $userinfo['subdomains'] || $userinfo['subdomains'] == '-1') && 15 < $domains_count && $parentdomains_count != 0 ><tr>
      <td colspan="4" class="maintable"><a href="$filename?page=domains&action=add&s=$s">{$lng['domains']['subdomain_add']}</a></td>
     </tr></if>
     <if 0 < $pages><tr>
      <td colspan="20" class="paging">{$paging}</td>
     </tr></if>
     $domains
     <if 0 < $pages><tr>
      <td colspan="20" class="paging">{$paging}</td>
     </tr></if>
     <if ($userinfo['subdomains_used'] < $userinfo['subdomains'] || $userinfo['subdomains'] == '-1') && $parentdomains_count != 0 ><tr>
      <td colspan="4" class="maintable"><a href="$filename?page=domains&action=add&s=$s">{$lng['domains']['subdomain_add']}</a></td>
     </tr></if>
    </table>
$footer
