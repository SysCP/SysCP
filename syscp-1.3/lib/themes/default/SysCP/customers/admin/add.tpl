    <form method="post" action="{url module=customers action=add}">
     <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
      <tr>
       <td colspan="20" class="title">{l10n get=admin.customer_add}</td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=login.username}: *</td>
       <td class="maintable" nowrap><input type="text" name="loginname" value="" maxlength="10"></td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=customer.name}: *</td>
       <td class="maintable" nowrap><input type="text" name="name" value=""></td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=customer.firstname}: *</td>
       <td class="maintable" nowrap><input type="text" name="firstname" value=""></td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=customer.company}:</td>
       <td class="maintable" nowrap><input type="text" name="company" value=""></td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=customer.street}:</td>
       <td class="maintable" nowrap><input type="text" name="street" value=""></td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=customer.zipcode}:</td>
       <td class="maintable" nowrap><input type="text" name="zipcode" value=""></td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=customer.city}:</td>
       <td class="maintable" nowrap><input type="text" name="city" value=""></td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=customer.phone}:</td>
       <td class="maintable" nowrap><input type="text" name="phone" value=""></td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=customer.fax}:</td>
       <td class="maintable" nowrap><input type="text" name="fax" value=""></td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=customer.email}: *</td>
       <td class="maintable" nowrap><input type="text" name="email" value=""></td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=customer.customernumber}:</td>
       <td class="maintable" nowrap><input type="text" name="customernumber" value=""></td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=login.language}:</td>
       <td class="maintable" nowrap>
       	<select name="def_language">
       	{html_options options=$languages selected=$User.def_language}</select></td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=customer.diskspace}: *</td>
       <td class="maintable" nowrap><input type="text" name="diskspace" value="0" maxlength="6"></td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=customer.traffic}: *</td>
       <td class="maintable" nowrap><input type="text" name="traffic" value="0" maxlength="3"></td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=customer.subdomains}: *</td>
       <td class="maintable" nowrap><input type="text" name="subdomains" value="0" maxlength="9"></td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=customer.emails}: *</td>
       <td class="maintable" nowrap><input type="text" name="emails" value="0" maxlength="9"></td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=customer.accounts}: *</td>
       <td class="maintable" nowrap><input type="text" name="email_accounts" value="0" maxlength="9"></td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=customer.forwarders}: *</td>
       <td class="maintable" nowrap><input type="text" name="email_forwarders" value="0" maxlength="9"></td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=customer.ftps}: *</td>
       <td class="maintable" nowrap><input type="text" name="ftps" value="0" maxlength="9"></td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=customer.mysqls}: *</td>
       <td class="maintable" nowrap><input type="text" name="mysqls" value="0" maxlength="9"></td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=admin.stdsubdomain_add}?</td>
       <td class="maintable" nowrap>
       {html_radios name="createstdsubdomain" options=$createstdsubdomain selected=$createstdsubdomain_sel}
       </td>
      </tr>
      <tr>
       <td class="maintable" nowrap>{l10n get=login.password}:</td>
       <td class="maintable" nowrap><input type="password" name="password" value=""></td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=admin.sendpassword}?</td>
       <td class="maintable" nowrap>
         {html_radios name="sendpassword" options=$sendpassword selected=$sendpassword_sel}
		</td>
      </tr>
      <tr>
       <td class="maintable" colspan="2" align="right"><input type="hidden" name="send" value="send"><input type="submit" value="{l10n get=panel.save}"></td>
      </tr>
     </table>
    </form>