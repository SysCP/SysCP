    <form method="post" action="{url module=extras action=addHtaccess}">
     <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
      <tr>
       <td colspan="2" class="title">{l10n get=extras.pathoptions_add}</td>
      </tr>
      <tr>
       <td class="maintable"><b>{l10n get=panel.path}:</b></td>
       <td class="maintable" nowrap>{$pathSelect}<td>
      </tr>
      <tr>
       <td class="maintable"><b>{l10n get=extras.directory_browsing}:</b></td>
       <td class="maintable">
       {html_radios name=options_indexes options=$options_indexes selected=$options_indexes_sel}</td>
      </tr>
      <tr>
       <td class="maintable"><b>{l10n get=extras.errordocument404path}:</b><br />{l10n get=panel.emptyfordefault}</td>
       <td class="maintable"><input type="text" name="error404path" value="" maxlength="50"></td>
      </tr>
      <tr>
       <td class="maintable"><b>{l10n get=extras.errordocument403path}:</b><br />{l10n get=panel.emptyfordefault}</td>
       <td class="maintable"><input type="text" name="error403path" value=""  maxlength="50"></td>
      </tr>
      <tr>
       <td class="maintable"><b>{l10n get=extras.errordocument500path}:</b><br />{l10n get=panel.emptyfordefault}</td>
       <td class="maintable"><input type="text" name="error500path" value=""  maxlength="50"></td>
      </tr>
      <tr>
       <td class="maintable" colspan="2" align="right"><input type="hidden" name="send" value="send"><input type="submit" value="{l10n get=extras.pathoptions_add}"></td>
      </tr>
     </table>
    </form>
