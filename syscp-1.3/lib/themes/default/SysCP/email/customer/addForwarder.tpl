    <form method="post" action="{url module=email action=addForwarder}">
     <input type="hidden" name="id" value="{$Config->get('env.id')}">
     <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
      <tr>
       <td colspan="2" class="title">{l10n get=emails.forwarder_add}</td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=emails.from}:</td>
       <td class="maintable" nowrap>{$result.email_full}</td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=emails.to}:</td>
       <td class="maintable"><input type="text" name="destination" size="30"></td>
      </tr>
      <tr>
       <td class="maintable" colspan="2" align="right">
       <input type="hidden" name="send" value="send">
       <input type="submit" value="{l10n get=emails.forwarder_add}"></td>
      </tr>
     </table>
    </form>
