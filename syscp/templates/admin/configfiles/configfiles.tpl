$header
     <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
      <tr>
       <td class="title">{$configfiles[$distribution]['daemons'][$daemon]['label']}</td>
      </tr>
      <if $commands != ''><tr>
       <td class="maintable">{$lng['admin']['configfiles']['commands']}<br /><br />
        <fieldset style=" margin-left:20px; margin-right:20px; ">
         <pre style=" width:500px; overflow: auto;"><div>$commands</div></pre>
        </fieldset><br /></td>
      </tr></if>
      <if $files != ''><tr>
       <td class="maintable">{$lng['admin']['configfiles']['files']}<br /><br />{$files}<br /></td>
      </tr></if>
      <if $restart != ''><tr>
       <td class="maintable">{$lng['admin']['configfiles']['restart']}<br /><br />
        <fieldset style=" margin-left:20px; margin-right:20px; ">
         <pre style=" width:500px; overflow: auto;"><div>$restart</div></pre>
        </fieldset><br /></td>
      </tr></if>
     </table>
$footer 
