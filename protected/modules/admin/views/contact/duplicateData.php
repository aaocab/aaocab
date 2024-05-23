<h2 style="text-align: center;">Duplicate Contact Details</h2>
<p style="text-align: center;">This cards shows the duplicate contact details. Copy the details to primary if required.</p>
<?php
foreach ($model as $value)
{
?>	<div class="col-md-4 border-01">
		<table class="table table-bordered">
			<tr>
				<td>First Name : </td>
				<td><?= $value["ctt_first_name"] ?></td>
			</tr>
			<tr>
				<td>Last Name : </td>
				<td><?= $value["ctt_last_name"] ?></td>
			</tr>
			<tr>
				<td>Business Name : </td>
				<td><?= $value["ctt_business_name"] ?></td>
			</tr>
			<tr>
				<td>Address : </td>
				<td><?= $value["ctt_address"] ?></td>
			</tr>
			<tr>
				<td>Bank Name : </td>
				<td><?= $value["ctt_bank_name"] ?></td>
			</tr>
			<tr>
				<td>Bank Branch : </td>
				<td><?= $value["ctt_bank_branch"] ?></td>
			</tr>
			<tr>
				<td>Account no : </td>
				<td><?= $value["ctt_bank_account_no"] ?></td>
			</tr>
			<tr>
				<td>IFSC Code: </td>
				<td><?= $value["ctt_bank_ifsc"] ?></td>
			</tr>
			<tr>
				<td>Benficiary Name: </td>
				<td><?= $value["ctt_beneficiary_name"] ?></td>
			</tr>
			<tr>
				<td>License No: </td>
				<td><?= $value["ctt_license_no"] ?></td>
			</tr>
			<tr>
				<td>Voter No: </td>
				<td><?= $value["ctt_voter_no"] ?></td>
			</tr>
			<tr>
				<td>Pan No: </td>
				<td><?= $value["ctt_pan_no"] ?></td>
			</tr>
			<tr>
				<td>Aadhaar No: </td>
				<td><?= $value["ctt_aadhaar_no"] ?></td>
			</tr>
		</table>
	</div>
<? }
?>
	
