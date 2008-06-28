$header
	<script language="Javascript">
		function xmlhttpPost() {
			var xmlHttpReq = false;
			var self = this;
			if (window.XMLHttpRequest) {
				self.xmlHttpReq = new XMLHttpRequest();
			}
			else if (window.ActiveXObject) {
				self.xmlHttpReq = new ActiveXObject("Microsoft.XMLHTTP");
			}
		
			self.xmlHttpReq.open('get', '{$filename}?s={$s}&action={$action}&mode={$mode}&begin='+document.forms['myform'].begin.value+'&count='+document.forms['myform'].count.value, true);
			self.xmlHttpReq.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			self.xmlHttpReq.onreadystatechange = function() {
				if (self.xmlHttpReq.readyState == 4) {
					var response=self.xmlHttpReq.responseText;
					var result=new Array();
					if(response!='ready'&&response.indexOf('|') != -1)
					{
						result=response.split('|');
						document.getElementById("progress").innerHTML=result[0];
						document.getElementById("debug").innerHTML+=result[1];
						document.forms['myform'].begin.value=parseInt(document.forms['myform'].begin.value)+parseInt(document.forms['myform'].count.value);
						xmlhttpPost();
					}
					else
					{
						document.getElementById("progress").innerHTML='100%';
					}
				}
			}
			self.xmlHttpReq.send(null);
		}
	</script>
	<form name="myform">
		<table cellpadding="5" cellspacing="4" border="0" align="center" class="maintable_60">
			<tr>
				<td class="maintitle" colspan="2"><b><img src="images/title.gif" alt="" />&nbsp;{$lng['billing']['cacheinvoicefees']}</b></td>
			</tr>
			<tr>
				<td class="main_field_name">Begin (0 for first user):</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="begin" value="0" /></td>
			</tr>
			<tr>
				<td class="main_field_name">Count (0 not allowed):</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="count" value="1" /></td>
			</tr>
			<tr>
				<td class="main_field_name">Progress: <span id="progress">0/0 (0%)</span></td>
				<td class="main_field_confirm"><input value="Go" type="button" onclick='JavaScript:xmlhttpPost()'></td>
			</tr>
			<tr>
				<td class="main_field_name" colspan="2">Debug:<br /><span id="debug"></span></td>
			</tr>
		</table>
	</form>
	<br />
	<br />
$footer