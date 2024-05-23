<style>
.fill-width {
  flex: 1;
  width: 100%;
}
/*input {
      border-top-style: hidden;
      border-right-style: hidden;
      border-left-style: hidden;
      border-bottom-style: hidden;
      }*/
</style>
	<h2>Primary Contact Details</h2>
	<p>This table shows the primary contact details. Click on the cell to update the details</p>  
	<input type="hidden" name="YII_CSRF_TOKEN" value= "<?= Yii::app()->request->csrfToken ?>">
	<table class="table table-bordered">		
<?php
    //echo $docId;die;
	foreach ($model as $value) 
	{
?>		<tr>
			<td>First Name : </td>
			<td><input class="fill-width" type="text"  id="firstName" value="<?= $value["ctt_first_name"] ?>" /></td>
		</tr>
		<tr>
			<td>Last Name : </td>
			<td><input class="fill-width" type="text"  id="lastName" value="<?= $value["ctt_last_name"] ?>" /></td>
		</tr>
		<tr>
			<td>Business Name : </td>
			<td><input class="fill-width" type="text"  id="businessName" value="<?= $value["ctt_business_name"] ?>" /></td>
		</tr>
		<tr>
			<td>Address : </td>
			<td><input class="fill-width" type="text"  id="address" value="<?= $value["ctt_address"] ?>" /></td>
		</tr>
		<tr>
			<td>Bank Name : </td>
			<td><input class="fill-width" type="text" id="bankname" value="<?= $value["ctt_bank_name"] ?>" /></td>
		</tr>
		<tr>
			<td>Bank Branch : </td>
			<td><input class="fill-width" type="text" id="bankbranch" value="<?= $value["ctt_bank_branch"] ?>" /></td>
		</tr>
		<tr>
			<td>Account no : </td>
			<td><input class="fill-width" type="text" id="bankaccount" value="<?= $value["ctt_bank_account_no"] ?>" /></td>
		</tr>
		<tr>
			<td>IFSC Code: </td>
			<td><input class="fill-width" type="text" id="bankifsc" value="<?= $value["ctt_bank_ifsc"] ?>" /></td>
		</tr>
		<tr>
			<td>Benficiary Name: </td>
			<td><input class="fill-width" type="text" id ="benificiaryname" value="<?= $value["ctt_beneficiary_name"] ?>" /></td>
		</tr>
		<tr>
			<td>License No: </td>
			<td><input class="fill-width" type="text" id="license" value="<?= $value["ctt_license_no"] ?>" /></td>
		</tr>
		<tr>
			<td>Voter No: </td>
			<td><input class="fill-width" type="text" id="voter" value="<?= $value["ctt_voter_no"] ?>" /></td>
		</tr>
		<tr>
			<td>Pan No: </td>
			<td><input class="fill-width" type="text" id="panno" value="<?= $value["ctt_pan_no"] ?>" /></td>
		</tr>
		<tr>
			<td>Aadhaar No: </td>
			<td><input class="fill-width" type="text" id="aadhaar" value="<?= $value["ctt_aadhaar_no"] ?>" /></td>
		</tr>
		<tr colspan="2">
			<button type="submit" class="btn btn-success" onclick="updateContact(<?= $value["ctt_id"] ?>)">Update</button>&nbsp;&nbsp;
			<button type="submit" class="btn btn-success" onclick="markResolve(<?= $value["ctt_id"] ?>)">Mark as resolved</button>
		</tr>
<?php	
	}
?>	
	</table>
	<script>
		function updateContact(cttid)
		{
			let contact = {};
			contact.firstName = $('#firstName').val();
			contact.lastName = $('#lastName').val();
			contact.businessName = $('#businessName').val();
			contact.address = $('#address').val();
			contact.bankname = $('#bankname').val();
			contact.bankbranch = $('#bankbranch').val();
			contact.bankaccount = $('#bankaccount').val();
			contact.bankifsc = $('#bankifsc').val();
			contact.benificiaryname = $('#benificiaryname').val();
			contact.license = $('#license').val();
			contact.voter = $('#voter').val();
			contact.panno = $('#panno').val();
			contact.aadhaar = $('#aadhaar').val();
			contact.id = cttid;      
			$.ajax(
			{
			   "type":"POST",
			   "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/contact/ApproveDoc')) ?>",
			   "data": {contact:contact,'YII_CSRF_TOKEN':"<?= Yii::app()->request->csrfToken ?>"},
			   "dataType": 'json',
			   "success":function(response)
	            {
				    if(response)
					{
						alert('contact updated successfully');
						window.location.reload();
					}
					else
					{
						alert("Failed to update contact details");
					}
			    }
			});
		 }
		 
		function markResolve(cttid)
		{
			var r = confirm("Are you sure to makr it as resolve?\nPress OK or Cancel to confirm.");
			if (r == true) 
			{
				$.ajax(
				{
				   "type":"GET",
				   "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/contact/markResolve')) ?>",
				   "data": {primaryCttId:cttid,'YII_CSRF_TOKEN':"<?= Yii::app()->request->csrfToken ?>"},
				   "dataType": 'json',
				   "success":function(response)
					{
						if(response.success)
						{
							alert(response.message);
							window.history.back();
						}
						else
						{
							alert("Failed to mark as resolved");
						}
					}
				});
			}
			else
			{
				return false;
			}
		 }
	</script>
	

