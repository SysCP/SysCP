    <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
     <tr>
      <td colspan="4" class="title">{l10n get=SysCP.ftp.accounts}</td>
     </tr>
     <tr>
      <td class="maintable">{l10n get=SysCP.globallang.username}</td>
      <td class="maintable">{l10n get=SysCP.globallang.path}</td>
      <td class="maintable" colspan="2">&nbsp;</td>
     </tr>
     {if ($User.ftps_used < $User.ftps || $User.ftps == '-1') }<tr>
      <td class="maintable" colspan="4">
        <a href="{url module=ftp action=add}">{l10n get=SysCP.ftp.add}</a></td>
     </tr>{/if}
     {if 0 < $pages}<tr>
      <td colspan="20" class="paging">{$paging}</td>
     </tr>{/if}
     {foreach from=$accounts item=row}
      <tr>
      <td class="maintable">{$row.username}</td>
      <td class="maintable">{$row.documentroot}</td>
      <td class="maintable"><a href="{url module=ftp action=edit id=$row.id}">{l10n get=SysCP.globallang.changepassword}</a></td>
      <td class="maintable"><a href="{url module=ftp action=delete id=$row.id}">{l10n get=SysCP.globallang.delete}</a></td>
     </tr>
     {/foreach}
     {if 0 < $pages}<tr>
      <td colspan="20" class="paging">{$paging}</td>
     </tr>{/if}
     {if ($User.ftps_used < $User.ftps || $User.ftps == '-1') }<tr>
      <td class="maintable" colspan="4"><a href="{url module=ftp action=add}">{l10n get=SysCP.ftp.add}</a></td>
     </tr>{/if}
    </table>
