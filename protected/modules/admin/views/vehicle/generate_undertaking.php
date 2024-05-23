<style>
    body{ font-family: 'Arial'; font-size: 12px; line-height: 20px;}
    p{ font-size: 13px;}
    .tb_cfont p{font-size: 16px;}
    .main-div{ width:720px; margin: auto; font-size: 12px!important;}
    @media (max-width: 767px) {
        .main-div{ width:320px; margin: auto; font-size: 12px!important;}
    }
</style>
<div class="main-div">
<?php 
$address	 = Config::getGozoAddress(Config::Corporate_address, true);
?>
    <p>&nbsp;</p>
    <h1><b>UNDERTAKING & SELF DECLARATION</b></h1>
    <p>&nbsp;</p>
    <table width="100%" class="tb_cfont">
        <tr>
            <td width="60%" valign="top">
                <p>To,</p>
                <p>Gozo Technologies Private Limited<br>
				   <?= $address; ?></p>
            </td>
            <td width="40%" valign="top">
                <p>Date: <?= date("d/m/Y", strtotime(DATE('Y-m-d'))); ?></p>
            </td>
        </tr>
    </table>
    <p>&nbsp;</p>
	
    <p>I, <?= $data['vhc_owner']; ?>,
		<?php
		/*($data['vnd_firm_type'] == 1) ? 'Owner' :
				(($data['vnd_firm_type'] == 2) ? 'Partner' :
						(($data['vnd_firm_type'] == 3 || 4) ? 'Director' : '_____________'));*/
          //($data['ctt_user_type'] == 1 ? )'Individual':'Business'; 
	 
	    if($data['vnd_company'] != "")
		{
		?>
        of <?= $data['vnd_company']; ?> hereby undertake and affirm as under – </p>
	   <?php
		}
	   ?>
    <ul>
        <li>I am authorized to operate motor vehicle bearing the following details –<br>
            Registration Number <?= $data['vhc_number']; ?>; </li>
        <li>The vehicle is owned by <?= ($data['vhc_owner'] != '') ? $data['vhc_owner'] : '______________________________'; ?>,
            <br> hereinafter referred to as "Vehicle Owner". </li>
        <li>And I am authorized by the Vehicle owner to operate this vehicle until<br>
            Day: <?= ($data['vvhc_vhc_owner_auth_valid_date'] != '') ? date('d', strtotime($data['vvhc_vhc_owner_auth_valid_date'])) : '____'; ?>
            Month: <?= ($data['vvhc_vhc_owner_auth_valid_date'] != '') ? date('m', strtotime($data['vvhc_vhc_owner_auth_valid_date'])) : '____'; ?>
            Year: <?= ($data['vvhc_vhc_owner_auth_valid_date'] != '') ? date('Y', strtotime($data['vvhc_vhc_owner_auth_valid_date'])) : '________'; ?>
        </li>
        <li>The driver(s) driving this vehicle are verified by me to have commercial vehicle license and<br> are issued
            a genuine Police Verification certificate.</a></li>
    </ul>
    <p>&nbsp;</p>

	<?php
	
	if ($data['vvhc_digital_flag'] == 1)
	{
		?>
		<p>Signed by <?= $data['vnd_name']; ?>, <?php  if($data['vnd_company'] != ""){?><br>authorized signatory for and on behalf
		of <?= $data['vnd_company']; }?></p>
		<?php
		if ($data['vvhc_digital_sign'] != NULL || $data['vvhc_lou_s3_data'] != NULL)
		{
			//$digitalImg = 'https://' . $data['host'] . $data['vvhc_digital_sign'];
		$base		 = Yii::app()->getBaseUrl(true);
		$digitalImg	 = $base . $data['vvhc_digital_sign'];
		$path = ($docPath != '') ? ($docPath) : ($digitalImg);
	?>

			<table>
				<tr>
					<td valign="top"><span style="font-size:12px; font-weight: bold;">Signature :</span></td>
					<td valign="top" align="left"><img src="<?= $path; ?>" width="220px;" height="50px;"></td>
				</tr>
			</table>
			<?php
		}
	}
	else
	{
		?>
		<p><strong>Signature: </strong>__ __ __ __ __ __ __ __ __&nbsp; __ __ __ __ _</p>
		<?php
	}
	?>
    <p>Date: <?= date("d/m/Y", strtotime(DATE('Y-m-d'))); ?></p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
</div>