$header
   <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
    <tr>
     <td colspan="20" class="title">{$lng['admin']['templates']['templates']}</td>
    </tr>
    <if $add><tr>
     <td colspan="20" class="maintable"><a href="$filename?page=$page&action=add&s=$s">{$lng['admin']['templates']['template_add']}</a></td>
    </tr></if>
    <tr>
     <td class="maintable">{$lng['login']['language']}</td>
     <td class="maintable">{$lng['admin']['templates']['action']}</td>
     <td class="maintable" colspan="2">&nbsp;</td>
    </tr>
    $templates
    <if $add><tr>
     <td colspan="20" class="maintable"><a href="$filename?page=$page&action=add&s=$s">{$lng['admin']['templates']['template_add']}</a></td>
    </tr></if>
   </table>
$footer
