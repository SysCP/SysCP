$header
     <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
      <tr>
       <td class="title">{$configfiles[$distribution]['daemons'][$daemon]['label']}</td>
      </tr>
      <if $commands != ''><tr>
       <td class="maintable">{$lng['admin']['configfiles']['commands']}<br /><p align="center"><textarea rows="6" cols="70" readonly="readonly">$commands</textarea></p></td>
      </tr></if>
      <if $files != ''><tr>
       <td class="maintable">{$lng['admin']['configfiles']['files']}<br />{$files}</td>
      </tr></if>
      <if $restart != ''><tr>
       <td class="maintable">{$lng['admin']['configfiles']['restart']}<br /><p align="center"><textarea rows="1" cols="70" readonly="readonly">$restart</textarea></p></td>
      </tr></if>
     </table>
$footer 
