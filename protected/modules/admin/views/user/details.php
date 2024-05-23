<?php
$response = Contact::userMappedToItems($model->user_id, 3);
if ($response->getStatus())
{
	$contactNo	 = $response->getData()->phone['number'];
	$countryCode = $response->getData()->phone['ext'];
	$firstName	 = $response->getData()->email['firstName'];
	$lastName	 = $response->getData()->email['lastName'];
	$email		 = $response->getData()->email['email'];
}

?>
<!-- @var  Users $model-->
<div class="row">
    <div class="col-xs-6">
        <div class="panel panel-default panel-border">
            <div class="panel panel-heading">Personal Details</div>
            <div class="panel panel-body">
                <div class="row">
                    <div class="col-xs-6"><label class="pr10"><b>Name: </b></label><?php echo $firstName . " " . $lastName ?></div>
                    <div class="col-xs-5"><label class="pr10"><b>Is Corporate: </b></label><?php echo ($model->usr_corporate_id > 0) ? "Yes" : "No"; ?></div>
                </div>
                <div class="row">
                    <div class="col-xs-6"><label class="pr10"><b>Gender: </b></label><?php echo ($model->usr_gender == 1) ? "Male" : "Female"; ?></div>
                    <div class="col-xs-5"><label class="pr10"><b>Profile Picture: </b></label> <a href="<?php echo $model->usr_profile_pic_path ?>" target="_blank"><?php echo basename($model->usr_profile_pic_path); ?></a></div>
                </div>
                <div class="row">
                    <div class="col-xs-6"><label class="pr10"><b>Email: </b></label><?
						$varContentM = ($model->usr_email_verify == 1) ? $model->usr_email_verify_date : "<span class='text-danger'><i class='fa fa-close'></i></span>";
						echo $email . "<br>(verified: " . $varContentM . ")";
						?></div>
                    <div class="col-xs-5"><label class="pr10"><b>Phone: </b></label>
						<?
						$isShowPh	 = Config::model()->getAccess('CUST_PHONE_ADMIN_VISIBLE');
						if ($isShowPh == 1)
						{
							$varContent = ($model->usr_mobile_verify == 1) ? $model->usr_mobile_verify_date : "<span class='text-danger'><i class='fa fa-close'></i></span>";
							echo "(" . $countryCode . ") " . $contactNo . "<br>(verified: " . $varContent . ")";
						}
						else
						{
							echo "<span  class='label label-success'>Call Now</span>";
						}
						?></div>
                </div>
                <div class="row">
                    <div class="col-xs-6"><label class="pr10"><b>Country: </b></label><?php echo Countries::model()->getCountryName($model->usr_country) ?></div>
                    <div class="col-xs-5"><label class="pr10"><b>State: </b></label><?php echo States::model()->getNameById($model->usr_state); ?></div>
                </div>
                <div class="row">
                    <div class="col-xs-6"><label class="pr10"><b>City: </b></label><?php echo $model->usr_city ?></div>
                    <div class="col-xs-5"><label class="pr10"><b>Zip: </b></label><?php echo $model->usr_zip; ?></div>
                </div>
                <div class="row">
                    <div class="col-xs-12"><label class="pr10"><b>Address: </b></label><?php echo $model->usr_address1 . ", " . $model->usr_address2 . ", " . $model->usr_address3; ?></div>
                    <div class="col-xs-12"></div>
                </div>
                <div class="row">
                    <div class="col-xs-6"><label class="pr10"><b>IP address: </b></label><?php echo $model->usr_ip ?></div>
                    <div class="col-xs-5"><label class="pr10"><b>Created on: </b></label><?php echo $model->usr_created_at; ?></div>
                </div>
                <div class="row">
                    <div class="col-xs-6"><label class="pr10"><b>Last Login: </b></label><?php echo $model->usr_last_login ?></div>
                    <div class="col-xs-5"><label class="pr10"><b>Platform: </b></label><?
						$platforms = Booking::model()->booking_platform;
						echo $platforms[$model->usr_create_platform];
						?></div>
                </div>


            </div>
        </div>
    </div>

    <div class="col-xs-6"> 
        <div class="panel panel-default panel-border">
            <div class="panel panel-heading">Booking Details</div>
            <div class="panel panel-body">
                <div class="col-xs-11 pb10">
                    <label class="pr10"><b>Total Bookings: </b></label>
					<?
					echo $totalBookings['total'] . "<br>[Quoted: " . $totalBookings['totQuote'] . ", Unverified: " . $totalBookings['totUnverified'] . ", "
					. "New: " . $totalBookings['totNew'] . ", Assigned: " . $totalBookings['totAssinged'] . ", "
					. "OntheWay " . $totalBookings['totOntheWay'] . ", completed: " . $totalBookings['totCompleted'] . ", Cancelled: " . $totalBookings['totCancelled'] . ", Cancelled Quoted: " . $totalBookings['totCancelledQt'] . ", Others: " . $totalBookings['totOthers'] . "]";
					?>
                </div>
				<div class="col-xs-5"><label class="pr10"><b>Total Booking Amount: </b></label><?php echo $totalBookings['totAmount']; ?></div>
                <div class="col-xs-5"><label class="pr10"><b>Total Gozo Amount: </b></label><?php echo $totalBookings['totGozoAmount']; ?></div>
                <div class="col-xs-5"><label class="pr10"><b>Marked Bad Count: </b></label><?php echo $model['usr_mark_customer_count'] ?></div>
                <div class="col-xs-5"><label class="pr10"><b>Overall Rating: </b></label><?php echo $model['usr_overall_rating'] ?></div>
                <div class="col-xs-5"><label class="pr10"><b>GozoCoins: </b></label>₹<?php echo $totalAmount ?></div>
				<div class="col-xs-5"><label class="pr10"><b>Wallet Ballance: </b></label><a href="<?php echo  Yii::app()->createUrl('admin/user/showwalletdetails',['user'=>$model->user_id]) ?>">₹<?php echo $walletBalance ?></a></div>

			</div>
        </div>
    </div>
	<div class="col-xs-6 text-right">
		<?php if($email != ''){?>
			<button type="button" class="btn btn-primary" onclick="sendVerifyLinkByEmail()">Send Email For Account Recovery</button>
		<?php } 
		 if($contactNo != ''){
		?>
		<button type="button" class="btn btn-primary" onclick="sendOtpByPhone()">Send SMS For Account Recovery</button>
		 <?php } ?>
	</div>
	<div class="col-xs-6 text-right font-15">
		<span class="verifymsg" style="color: #009900;"></span>
	</div>
    <div class="col-xs-12">
		<div class="col-xs-6">
			<div class="panel panel-default panel-border">
				<div class="panel panel-body">
					<?php
					if (!empty($dataProvider))
					{
						/* @var $dataProvider TbGridView */
						$params									 = array_filter($_REQUEST);
						$dataProvider->getPagination()->params	 = $params;
						$dataProvider->getSort()->params		 = $params;
						$this->widget('booster.widgets.TbGridView', array(
							'responsiveTable'	 => true,
							'dataProvider'		 => $dataProvider,
							'pager'				 => ['maxButtonCount' => 5, 'class' => 'booster.widgets.TbPager'],
							'id'				 => 'creditListGrid',
							'template'			 => "<div class='panel-heading'>
                                        <div class='row m0'>
                                        <div class='col-xs-12 col-sm-4 pt5'>Active Gozo Coins</div>
                                        <div class='col-xs-12 col-sm-4 pr0'>{summary}</div>
                                        <div class='col-xs-12 col-sm-4 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body table-responsive'>{items}</div>",
							'itemsCssClass'		 => 'table table-striped table-bordered dataTable mb0',
							'htmlOptions'		 => array('class' => 'panel panel-primary  compact'),
							'columns'			 => array(
								array('name'	 => 'created', 'value'	 => function($data)
									{
										echo $data['created'];
									}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'), 'header'			 => 'Date'),
								array('name'	 => 'amount', 'value'	 => function($data)
									{
										if ($data['ptp_id'] == '5')
										{
											echo "-" . round($data['amount'], 1);
										}
										else if ($data['ptp_id'] == '0')
										{
											echo "+" . round($data['amount'], 1);
										}
									}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'), 'header'			 => 'Debit/Credit'),
								array('name'	 => 'ucr_type', 'value'	 => function($data)
									{
										if ($data['ptp_id'] == '0')
										{
											// 1:promo,2:refund,3:referral,4:booking,5:others   
											switch ($data['ucr_type'])
											{
												case '1':
													echo "Promo";
													break;
												case '2':
													echo "Refund";
													break;
												case '3':
													echo "Referral";
													break;
												case '4':
													echo "Booking";
													break;
												case '5':
													echo "Others(Admin)";
													break;
												case '6':
													echo "Referred";
													break;
												case '7':
													echo "booking(CREDITS PER KM RIDDEN)";
													break;
												case '8':
													echo "booking(CREDITS EQUALS COD AMOUNT)";
													break;
												case '9':
													echo "Notification";
													break;
											}
										}
										else
										{
											echo $data['ucr_type'];
										}
									}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'), 'header'			 => 'Type'),
								array('name'	 => 'ucr_maxuse_type', 'value'	 => function($data)
									{
										$maxStr = UserCredits::model()->getMaxUseTypes($data['ucr_maxuse_type']);
										if ($data['ucr_max_use'] > 0)
										{
											//  $maxStr.=" (Max use: ".$data['ucr_max_use'].")";
										}
										return $maxStr;
									}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'), 'header'			 => 'Max Use'),
								array('name' => 'description', 'value' => $data['description'], 'sortable' => false, 'htmlOptions' => array('class' => 'text-left'), 'headerHtmlOptions' => array('class' => 'col-xs-6 text-center'), 'header' => 'Description'),
								array('name'	 => 'ucr_validity', 'value'	 => function($data)
									{
										if ($data['ucr_validity'] > date("Y-m-d H:i:s"))
										{
											echo date("d/m/Y H:i:s", strtotime($data['ucr_validity']));
										}
										else if ($data['ucr_validity'] < date("Y-m-d H:i:s"))
										{
											echo '<span class="text-danger"><i class="fa fa-close"></i>Expired</span>';
										}
										else
										{
											echo 'NA';
										}
									}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'), 'header'			 => 'Valid Upto'),
						)));
					}
					?>
				</div>
			</div>
		</div>
		<div class="col-xs-6">
			<div class="panel panel-default panel-border">
				<div class="panel panel-body">
					<?php
					if (!empty($dataProvider2))
					{
						/* @var $dataProvider2 TbGridView */
						$params									 = array_filter($_REQUEST);
						$dataProvider2->getPagination()->params	 = $params;
						$dataProvider2->getSort()->params		 = $params;
						$this->widget('booster.widgets.TbGridView', array(
							'responsiveTable'	 => true,
							'dataProvider'		 => $dataProvider2,
							'pager'				 => ['maxButtonCount' => 5, 'class' => 'booster.widgets.TbPager'],
							'id'				 => 'pendingListGrid',
							'template'			 => "<div class='panel-heading'><div class='row m0'>
                <div class='col-xs-12 col-sm-4 pt5'>Pending Gozo Coins</div>
                <div class='col-xs-12 col-sm-4 pr0'>{summary}</div>
                <div class='col-xs-12 col-sm-4 pr0'>{pager}</div>
                </div></div>
                <div class='panel-body table-responsive'>{items}</div><div></div>",
							'itemsCssClass'		 => 'table table-striped table-bordered dataTable mb0',
							'htmlOptions'		 => array('class' => 'panel panel-primary  compact'),
							'columns'			 => array(
								array('name'	 => 'created', 'value'	 => function($data)
									{
										echo $data['created'];
									}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'), 'header'			 => 'Date'),
								array('name'	 => 'amount', 'value'	 => function($data)
									{
										if ($data['ptp_id'] == '5')
										{
											echo "-" . round($data['amount'], 1);
										}
										else if ($data['ptp_id'] == '0')
										{
											echo "+" . round($data['amount'], 1);
										}
									}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'), 'header'			 => 'Debit/Credit'),
								array('name'	 => 'ucr_type', 'value'	 => function($data)
									{
										if ($data['ptp_id'] == '0')
										{
											// 1:promo,2:refund,3:referral,4:booking,5:others   
											switch ($data['ucr_type'])
											{
												case '1':
													echo "Promo";
													break;
												case '2':
													echo "Refund";
													break;
												case '3':
													echo "Referral";
													break;
												case '4':
													echo "Booking";
													break;
												case '5':
													echo "Others(Admin)";
													break;
												case '6':
													echo "Referred";
													break;
												case '7':
													echo "booking(CREDITS PER KM RIDDEN)";
													break;
												case '8':
													echo "booking(CREDITS EQUALS COD AMOUNT)";
													break;
											}
										}
										else
										{
											echo $data['ucr_type'];
										}
									}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'), 'header'			 => 'Type'),
								array('name'	 => 'ucr_maxuse_type', 'value'	 => function($data)
									{
										$maxStr = UserCredits::model()->getMaxUseTypes($data['ucr_maxuse_type']);
										if ($data['ucr_max_use'] > 0)
										{
											//  $maxStr.=" (Max use: ".$data['ucr_max_use'].")";
										}
										return $maxStr;
									}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'), 'header'			 => 'Max Use'),
								array('name' => 'description', 'value' => $data['description'], 'sortable' => false, 'htmlOptions' => array('class' => 'text-left'), 'headerHtmlOptions' => array('class' => 'col-xs-6 text-center'), 'header' => 'Description'),
						)));
					}
					?>
				</div>
			</div>
		</div>
    </div>

</div>
<script>
function sendVerifyLinkByEmail()
{
	var userid = '<?= $model->user_id ?>';
	$href = $adminUrl + "/user/SendResetPasswordLink";
	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"id": userid},
		dataType:"html",
		success: function (data)
		{
			//consol.log(data);
			if (data != '') {
				var json = JSON.parse(data);
				if (json.success == false)
				{
					alert(json.error);
				}
				else{
					$('.verifymsg').html(json);
				}
			}
		}
	});
}

function sendOtpByPhone()
{
	var userid = '<?= $model->user_id ?>';
	$href = $adminUrl + "/user/ResetPasswordByPhone";
	jQuery.ajax({type: 'GET',
		url: $href,
		data: {"id": userid},
		dataType:"html",
		success: function (data)
		{
			//consol.log(data);
			if (data != '') {
				var json = JSON.parse(data);
				if (json.success == false)
				{
					alert(json.error);
				}
				else{
					$('.verifymsg').html(json);
				}
			}
		}
	});
}
</script>
