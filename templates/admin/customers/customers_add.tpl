$header
    <form method="post" action="$filename">
     <input type="hidden" name="s" value="$s">
     <input type="hidden" name="page" value="$page">
     <input type="hidden" name="action" value="$action">
     <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
      <tr>
       <td colspan="20" class="title">{$lng['admin']['customer_add']}</td>
      </tr>
      <tr>
       <td class="maintable">{$lng['customer']['login']}: *</td>
       <td class="maintable" nowrap><input type="text" name="loginname" value=""></td>
      </tr>
      <tr>
       <td class="maintable">{$lng['customer']['name']}: *</td>
       <td class="maintable" nowrap><input type="text" name="name" value=""></td>
      </tr>
      <tr>
       <td class="maintable">{$lng['customer']['surname']}: *</td>
       <td class="maintable" nowrap><input type="text" name="surname" value=""></td>
      </tr>
      <tr>
       <td class="maintable">{$lng['customer']['company']}:</td>
       <td class="maintable" nowrap><input type="text" name="company" value=""></td>
      </tr>
      <tr>
       <td class="maintable">{$lng['customer']['street']}:</td>
       <td class="maintable" nowrap><input type="text" name="street" value=""></td>
      </tr>
      <tr>
       <td class="maintable">{$lng['customer']['zipcode']}:</td>
       <td class="maintable" nowrap><input type="text" name="zipcode" value=""></td>
      </tr>
      <tr>
       <td class="maintable">{$lng['customer']['city']}:</td>
       <td class="maintable" nowrap><input type="text" name="city" value=""></td>
      </tr>
      <tr>
       <td class="maintable">{$lng['customer']['phone']}:</td>
       <td class="maintable" nowrap><input type="text" name="phone" value=""></td>
      </tr>
      <tr>
       <td class="maintable">{$lng['customer']['fax']}:</td>
       <td class="maintable" nowrap><input type="text" name="fax" value=""></td>
      </tr>
      <tr>
       <td class="maintable">{$lng['customer']['email']}: *</td>
       <td class="maintable" nowrap><input type="text" name="email" value=""></td>
      </tr>
      <tr>
       <td class="maintable">{$lng['customer']['customernumber']}:</td>
       <td class="maintable" nowrap><input type="text" name="customernumber" value=""></td>
      </tr>
      <tr>
       <td class="maintable">{$lng['customer']['diskspace']}: *</td>
       <td class="maintable" nowrap><input type="text" name="diskspace" value="0" maxlength="6"></td>
      </tr>
      <tr>
       <td class="maintable">{$lng['customer']['traffic']}: *</td>
       <td class="maintable" nowrap><input type="text" name="traffic" value="0" maxlength="3"></td>
      </tr>
      <tr>
       <td class="maintable">{$lng['customer']['subdomains']}: *</td>
       <td class="maintable" nowrap><input type="text" name="subdomains" value="0" maxlength="9"></td>
      </tr>
      <tr>
       <td class="maintable">{$lng['customer']['emails']}: *</td>
       <td class="maintable" nowrap><input type="text" name="emails" value="0" maxlength="9"></td>
      </tr>
      <tr>
       <td class="maintable">{$lng['customer']['forwarders']}: *</td>
       <td class="maintable" nowrap><input type="text" name="email_forwarders" value="0" maxlength="9"></td>
      </tr>
      <tr>
       <td class="maintable">{$lng['customer']['ftps']}: *</td>
       <td class="maintable" nowrap><input type="text" name="ftps" value="0" maxlength="9"></td>
      </tr>
      <tr>
       <td class="maintable">{$lng['customer']['mysqls']}: *</td>
       <td class="maintable" nowrap><input type="text" name="mysqls" value="0" maxlength="9"></td>
      </tr>
      <tr>
       <td class="maintable">{$lng['admin']['stdsubdomain_add']}?</td>
       <td class="maintable" nowrap>$createstdsubdomain</td>
      </tr>
      <tr>
       <td class="maintable" nowrap>{$lng['login']['password']}:</td>
       <td class="maintable" nowrap><input type="password" name="password" value=""></td>
      </tr>
      <tr>
       <td class="maintable">{$lng['admin']['sendpassword']}?</td>
       <td class="maintable" nowrap>$sendpassword</td>
      </tr>
      <tr>
       <td class="maintable" colspan="2" align="right"><input type="hidden" name="send" value="send"><input type="submit" value="{$lng['panel']['save']}"></td>
      </tr>
     </table>
    </form>
$footer