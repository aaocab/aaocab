<?php
$verifiedIcon	 = '<span class="label label-info">Verified</span>';
$primaryIcon	 = '<span class="label label-primary">Primary</span>';
?>
<div class="panel panel-primary panel-border compact">
    <div class="panel-heading" style="min-height:0">Existing Users (tick to link): </div>
    <div class="panel-body">
		<?php
		$i=0;
		foreach ($userModels as $key => $value)
		{
			if($i>=25)
			{
				break;
			}
			$i++;
			$usersArr[$key]['id']	 = $value['ctt_ref_code'];
			$code					 = ($value["phn_phone_country_code"] == '') ? $value['usr_country_code'] : $value["phn_phone_country_code"];
			$number					 = ($value["phn_phone_no"] == '') ? $value['usr_mobile'] : $value["phn_phone_no"];
			$phoneNo				 = $code . $number;
			$emailAddress			 = ($value["eml_email_address"] == '') ? $value['usr_email'] : $value["eml_email_address"];

			$emlVerify	 = ($value['eml_is_verified'] == 1) ? $verifiedIcon : "";
			$phnVerify	 = ($value['phn_is_verified'] == 1) ? $verifiedIcon : "";

			$phnPrimary	 = ($value['phn_is_primary'] == 1) ? $primaryIcon : "";
			$emlPrimary	 = ($value['eml_is_primary'] == 1) ? $primaryIcon : "";
			?><div class="row" style="padding: 0px 14px;">
	            <div class="col-sm-5" style="border-right:1px solid #ccc;border-bottom:1px solid #ccc">
	                <div class="p5" style="font-size: 1.1em">
	                    <a href="#" class="ml5" onclick="admBooking.showUserDet(<?php echo $value['cr_is_consumer']?>)"><?php echo $value["eml_email_address"]?></a>&nbsp;<?=$emlVerify .' '. $emlPrimary ?>
	                </div>
	            </div>
	            <div class="col-sm-4" style="border-right:1px solid #ccc;border-bottom:1px solid #ccc">
	                <div class="p5" style="font-size: 1.1em">
	                    <a href="#" class="ml5" onclick="admBooking.showUserDet(<?php echo $value['cr_is_consumer']?>)"><?php echo $phoneNo?></a>&nbsp;<?=$phnVerify .' '. $phnPrimary ?>
	                </div>
	            </div>
	            <div class="col-sm-2" style="border-right:1px solid #ccc;border-bottom:1px solid #ccc">
	                <div class="p5" style="font-size: 1.1em">
	                    <a href="#" class="ml5" onclick="admBooking.showUserView(<?php echo $value['cr_is_consumer']?>)"><?php echo  $value['ctt_name']?></a>
	                </div>
	            </div>
	            <div class="col-sm-1" style="border-bottom:1px solid #ccc">
	                <div class="p5" style="font-size: 1.1em">
	                    <span id="spnLinkUser<?php echo $key;?>" class="linkuserbtn bg-warning m5" code="<?php echo $code;?>" phone="<?php echo $number;?>" email="<?php echo $emailAddress;?>" fname="<?php echo $value['ctt_first_name']?>" lname="<?php echo $value['ctt_last_name']?>" onclick="admBooking.linkUser(this, <?php echo $value['ctt_ref_code']?>)">
	                        <i class="fa fa-check"></i>
	                    </span>
	                </div>
	            </div>
	        </div>

			<?php

		}?>
		</div></div>
		
		