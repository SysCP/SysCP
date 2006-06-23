     <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
      <tr>
       <td class="title">{$Configfiles.$distribution.daemons.$daemon.label}</td>
      </tr>
      {if $commands != ''}<tr>
       <td class="maintable">
       {l10n get=admin.configfiles.commands}<br />
       <textarea rows="6" cols="70" readonly="readonly">
       {$commands}
       </textarea>
       </td>
      </tr>{/if}
      {if $files != ''}<tr>
       <td class="maintable">{l10n get=admin.configfiles.files}<br />
       {$files}
       {foreach from=$files item=file}
<p>
  <b>{$file.filename}:</b><br />
  <textarea rows="{if $file.numbrows <= 8 }{$file.numbrows}{else}8{/if}" cols="70" readonly="readonly">
  {$file.content}</textarea>
</p>
{/foreach}
       </td>
      </tr>{/if}
      {if $restart != ''}<tr>
       <td class="maintable">
       {l10n get=admin.configfiles.restart}<br />
       <textarea rows="3" cols="70" readonly="readonly">{$restart}
       </textarea>
       </td>
      </tr>{/if}
     </table>
