    <form method="post" action="{url module=email action=add}">
     <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
      <tr>
       <td colspan="2" class="title">{l10n get=SysCP.email.add}</td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=SysCP.email.emailaddress}:</td>
       <td class="maintable" nowrap>
         <input type="text" name="email_part" value="" size="15"> @ <select name="domain">
         {html_options options=$domainList}
         </select></td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=SysCP.email.iscatchall}</td>
       <td class="maintable" nowrap>
       {html_radios name="iscatchall" options=$isCatchall selected=$isCatchallSel}
       </td>
      </tr>
      <tr>
       <td class="maintable" colspan="2" align="right">
       <input type="hidden" name="send" value="send">
       <input type="submit" value="{l10n get=SysCP.email.add}"></td>
      </tr>
     </table>
    </form>
