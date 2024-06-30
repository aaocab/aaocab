<style type="text/css">
    @media (min-width: 992px){
        .modal-lg {
            width: 95%!important;
        }
    }
    @media (min-width: 768px){
        .modal-lg {
            width: 100%;
        }
    }
    .bordered {
        border: 1px solid #ddd;
        min-height: 45px;
        line-height: 1.2em;
        margin-bottom: 10px;
        margin-left: 10px;
        margin-right: 10px;
        padding-bottom: 10px;
    }
</style>

<?php
$statusArr							 = [0 => 'Deleted', 1 => 'Profile Active', 2 => 'Deactive', 3 => 'Pending approval', 4 => 'Ready for approval'];
$params['Booking']['bcb_vendor_id']	 = $model->vnd_id;
$ynList								 = [1 => 'Yes', 0 => 'No'];
$ynList1							 = [1 => 'Yes', 0 => 'No'];
$accType							 = $model->accType;
$firm_type							 = $model->firm_type;
$vndId								 = $model->vnd_id;
$ownerName							 = Contact::model()->getNameById($model->vnd_id);
$altContact							 = ContactPhone::model()->getAlternateContactById($vndId);
/*  @var $model Vendors */

$vhcTot		 = Vendors::model()->getCountVehicle($model->vnd_id);
$drvTot		 = Vendors::model()->getCountDriver($model->vnd_id);

?>
<div class="row">
    <div class="col-xs-12 text-center h4  mt0">   
		<?php
		$vndContact	 = Contact::model()->findByPk($model->vnd_contact_id);
		$statusArr	 = [0 => 'Deleted', 1 => 'Profile Active', 2 => 'Deactive', 3 => 'Pending approval', 4 => 'Ready for approval'];
		$vndName	 = $vndContact->ctt_first_name . $vndContact->ctt_last_name;
		$batch		 = "";
		$state		 = "";
		$status		 = $statusArr[$data['vnd_active']];
		if ($data['vnp_is_attached'] == 1)
		{
			$state = "Attached";
		}
		if ($data['vnd_is_freeze'] == 1)
		{
			$state = "Frozen";
		}
		if ($data['vnd_is_freeze'] == 2)
		{
			$state = "Adminstrative Frozen";
		}

		if ($model->vnd_rel_tier > 0)
		{
			$batch = '<img src="/images/icon/plan-gold.png"  style="cursor:pointer ;" title="Value">';
		}
		if ($model->vnd_rel_tier == 0)
		{
			$batch = '<img src="/images/icon/plan-silver.png"  style="cursor:pointer ;" title="Value">';
		}
		echo $vndName . "   [" . $model->vnd_code . "</h4>]" . $batch . "[" . $state . "]  [Role Status:" . $status . "]";
		?>

		<?php
		?>

    </div>
</div>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="col-xs-12">
                <div class="row">
                    <div class="col-xs-12 text-center"> 

						<a class="btn btn-success btn-sm dropdown-toggle" target="blank" href="/aaohome/contact/view?ctt_id=<?= $model->vnd_contact_id ?>&type=view" target="_blank">View Contact</a>
						<a class="btn btn-danger font11x" href="<?php echo Yii::app()->createUrl("admin/vendor/vendoraccount", array('vnd_id' => $model->vnd_id)) ?>" target="_blank">Account Info</a>
						<a class="btn btn-primary font11x" href ="<?php echo Yii::app()->createUrl("admin/document/view", array('ctt_id' => $model->vnd_contact_id, 'viewType' => 'vendor')) ?>" target="_blank" >Document Summary</a>
						<a class="btn btn-success font11x" onclick="viewLog();">View Log</a>

						<a class="btn btn-warning font11x" href="<?php echo Yii::app()->createUrl("admin/vendor/agreementShowdoc", array('ctt_id' => $model->vnd_contact_id, 'vnd_id' => $model->vnd_id)) ?>" target="_blank">Approve Agreement</a>
						<a class="btn btn-info font11x" href ="<?php echo Yii::app()->createUrl("admin/driver/list", array('vnd' => $model->vnd_id)) ?>" target="_blank">View Driver</a>
						<a class="btn btn-danger font11x" href="<?php echo Yii::app()->createUrl("admin/vehicle/list", array('vnd' => $model->vnd_id, 'approve' => 0)) ?>" target="_blank" >View Car</a>
						<a class="btn btn-primary font11x" href="<?php echo Yii::app()->createUrl("admin/vendor/strength", array('vnd_id' => $model->vnd_id)) ?>" target="_blank">Profile Strength</a>

						<!--onclick="viewBiddingLog();"-->

						<a class="btn btn-success font11x" href="<?php echo Yii::app()->createUrl("admin/vendor/bidlog", array('vndid' => $model->vnd_id)) ?>" target="_blank">Bidding Log</a>
						<a class="btn btn-danger font11x" href="<?php echo Yii::app()->createUrl("admin/vendor/penalty", array('vnd_id' => $model->vnd_id)) ?>" target="_blank">Penalty</a>
						<a class="btn btn-warning font11x" onclick="reduceLvl();">Reduce Level</a>
						<a class="btn btn-primary font11x"  href="<?php echo Yii::app()->createUrl("admin/booking/list", $params) ?>" target="_blank">Booking History</a>
						<a class="btn btn-success font11x" onclick="updateVendorDetails();">Manually Update Statistical Data</a>
					</div>

                </div>
                <div class="row bordered mt10">
                    <div class="col-xs-12 col-sm-6 col-md-4 pt10">
                        <div class="col-xs-12 col-sm-5 "><b>Owner Name: </b></div>
                        <div class="col-xs-12 col-sm-7"><?= $ownerName->ownername; ?></div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4 pt10">
                        <div class="col-xs-12 col-sm-5 "><b>Company Name: </b></div>
                        <div class="col-xs-12 col-sm-7"><?= $data['business_name']; ?></div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4 pt10">
                        <div class="col-xs-12 col-sm-5 "><b>City: </b></div>
                        <div class="col-xs-12 col-sm-7"><?= $data['vnd_city_name']; ?></div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4 pt10">
                        <div class="col-xs-12 col-sm-5 "><b>Contact: </b></div>
                        <div class="col-xs-12 col-sm-7"><?= $data['vnd_phone']; ?></div>
                    </div>
					<?
					//					if ($model->vnd_alt_contact_number != '')
					//					{
					?>
					<div class="col-xs-12 col-sm-6 col-md-4 pt10">
						<div class="col-xs-12 col-sm-5 "><b>Alt Contact: </b></div>
						<div class="col-xs-12 col-sm-7"><?= $altContact->altPhoneNo; ?></div>
					</div>
					<? // } ?>
					<?
					if ($data['eml_email_address'] != '')
					{
					?>
					<div class="col-xs-12 col-sm-6 col-md-4 pt10">
						<div class="col-xs-12 col-sm-5 "><b>Email: </b></div>
						<div class="col-xs-12 col-sm-7"><?= $data['vnd_email']; ?></div>
					</div>
					<? } ?>
                    <div class="col-xs-12 col-sm-6 col-md-4 pt10">
                        <div class="col-xs-12 col-sm-5 "><b>Address: </b></div>
                        <div class="col-xs-12 col-sm-7"><?= $data['vnd_address']; ?></div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4 pt10">
                        <div class="col-xs-12 col-sm-5 "><b>Username: </b></div>
                        <div class="col-xs-12 col-sm-7"><?= $data['vnd_email']; ?></div>
                    </div>


                    <div class="col-xs-12 col-sm-6 col-md-4 pt10">
                        <div class="col-xs-12 col-sm-5 "><b>Credit limit: </b></div>
                        <div class="col-xs-12 col-sm-7"><?= $data['vnd_credit_limit']; ?></div>
                    </div>
					<?
					//
					?>
					<div class="col-xs-12 col-sm-6 col-md-4 pt10">
						<div class="col-xs-12 col-sm-5 "><b>Operates one-way: </b></div>
						<div class="col-xs-12 col-sm-7"><?= $ynList1[$data['vnd_booking_type']]; ?></div>

					</div>  <? //}   ?>
                    <div class="col-xs-12 col-sm-6 col-md-4 pt10">
                        <div class="col-xs-12 col-sm-5 "><b>Total Vehicles Approved: </b></div>
                        <div class="col-xs-12 col-sm-7"><?php
							echo $vhcTot['total_approved'] . "/" . $vhcTot['total_vehicle'];
							$data['vrs_credit_limit'];
							?>
							<?php if ($data['vnp_boost_enabled'])
							{
								?>
								   <b>Boosted</b>

							<?php
							}
							if ($data['vnp_vhc_boost_count']>0)
							{
								echo "  (" . $data['vnp_vhc_boost_count'] . ")";
							}
							?>


</div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4 pt10">
                        <div class="col-xs-12 col-sm-5 "><b>Total Drivers Approved: </b></div>
                        <div class="col-xs-12 col-sm-7"><?
							echo $drvTot['total_approved'] . "/" . $drvTot['total_driver'];
							$data['vrs_credit_limit'];
							?></div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4 pt10">
                        <div class="col-xs-12 col-sm-5 "><b>Exclusive: </b></div>
                        <div class="col-xs-12 col-sm-7">
							<?php
							echo $isExclusive = ($data['vnp_is_attached'] > 0) ? "Yes" : "No";
							?>
                        </div>
                    </div>
					<!--                    <div class="col-xs-12 col-sm-6 col-md-4 pt10">
											<div class="col-xs-12 col-sm-5 "><b>Bank Payment Info: </b></div>
											<div class="col-xs-12 col-sm-7">
					<?php
					if (($data['vnd_bank_name'] != '' && $data['vnd_bank_name'] != NULL) || ($data['vnd_bank_branch'] != '' && $data['vnd_bank_branch'] != NULL) || ($data['vnd_bank_ifsc'] != '' && $data['vnd_bank_ifsc'] != NULL) || ($data['vnd_bank_account_no'] != '' && $data['vnd_bank_account_no'] != NULL))
					{
						echo '<b>Bank Name:</b> ' . $data['vnd_bank_name'];
						echo '<br><b>Branch Name:</b>' . $data['vnd_bank_branch'];
						echo '<br><b>IFSC Code:</b>' . $data['vnd_bank_ifsc'];
						echo '<br><b>A/C Number:</b>' . $data['vnd_bank_account_no'];
					}
					else
					{
						echo 'Missing';
					}
					?>
											</div>
										</div>-->
                    <div class="col-xs-12 col-sm-6 col-md-4 pt10">
                        <div class="col-xs-12 col-sm-5 "><b>Pan Card: </b></div>
                        <div class="col-xs-12 col-sm-7">
							<?php
							if (($model->vndContact->ctt_pan_no != '' && $model->vndContact->ctt_pan_no != NULL))
							{
								echo $model->vndContact->ctt_pan_no;
							}
							else
							{
								$askForPan = '<a href="/aaohome/document/view?ctt_id=' . $model->vnd_contact_id . '" target="_blank">ask for PAN</a>';
								echo 'PAN missing, ' . $askForPan;
							}
							?>
                        </div>
                    </div>



                </div>
                <div class="row bordered">

                    <div class="col-xs-12 pt10">
                        <div class="col-xs-12 col-lg-2 "><b>Home Zone: </b></div>
                        <div class="col-xs-12 col-lg-10"><?= $data['vnd_home_zone']; ?></div>
                    </div>
                    <div class="col-xs-12 pt10">
                        <div class="col-xs-12 col-lg-2 "><b>Accepted Zones: </b></div>
                        <div class="col-xs-12 col-lg-10"><?= $data['vnd_accepted_zone_name']; ?></div>
                    </div>
					<?
					//					if ($data['vnd_return_zone_name'] != '')
					//					{
					//						
					?>
					<!--						<div class="col-xs-12 pt10">
												<div class="col-xs-12 col-lg-2 "><b>Return Zones: </b></div>
												<div class="col-xs-12 col-lg-10">//<? //= $data['vnd_return_zone_name'];  ?></div>
											</div>-->
					<?
					//					} 
					if ($data['vnd_excluded_cities_name'] != '')
					{
					?>
					<div class="col-xs-12 pt10">
						<div class="col-xs-12 col-lg-2 "><b>Excluded Cities: </b></div>
						<div class="col-xs-12 col-lg-10"><?= $data['vnd_excluded_cities_name']; ?></div>
					</div>
					<? } ?>
                </div>
                <div class="row bordered">
                    <div class="col-xs-12 text-center ">
                        <div class="col-xs-12 col-sm-6 col-md-4 pt10">
                            <b>Sedan Count: </b> <?= $data['vnd_sedan_count']; ?>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 pt10">
                            <b>Compact Count: </b> <?= $data['vnd_compact_count']; ?>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 pt10">
                            <b>SUV Count: </b> <?= $data['vnd_suv_count']; ?>
                        </div>  
						<!--                        <div class="col-xs-12 col-sm-6 col-md-4 pt10">
													<b>Sedan Rate: </b> <? //= $model->vnd_sedan_rate;   ?>
												</div>
												<div class="col-xs-12 col-sm-6 col-md-4 pt10">
													<b>Compact Rate: </b> <? //= $model->vnd_compact_rate;   ?>
												</div>
												<div class="col-xs-12 col-sm-6 col-md-4 pt10">
													<b>SUV Rate: </b> <? //= $model->vnd_suv_rate;   ?>
												</div>-->
                    </div>
                </div>
				<?
				if ($data['vnd_credit_throttle_level'] != '' || $data['vnd_notes'] != '')
				{
				?>
				<div class="row bordered">
					<?
					if ($data['vnd_notes'] != '')
					{
					?>
					<div class="col-xs-12 col-sm-6  pt10">
						<div class="col-xs-12 col-sm-3 "><b>Notes: </b></div>
						<div class="col-xs-12 col-sm-9"><?= $data['vnd_notes']; ?></div>
					</div>
					<?
					}if ($data['vnd_credit_throttle_level'] != '')
					{
					?>
					<div class="col-xs-12 col-sm-6  pt10">
						<div class="col-xs-12 col-sm-5 "><b>Credit throttle limit: </b></div>
						<div class="col-xs-12 col-sm-7"><?= $data['vnd_credit_throttle_level']; ?>(%)</div>
					</div>  
					<? } ?>
				</div>
				<? } ?>

                <div class="row bordered text-center">
                    <div class="h5 pl20 ml10 pt5 m0">Bank Details</div>
                    <div class="col-xs-12 col-sm-6 col-md-3 pt10">
                        <div class="col-xs-12 "><b>Bank Name: </b> <?= $data['vnd_bank_name']; ?></div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-3 pt10">
                        <div class="col-xs-12"><b>Bank Branch: </b><?= $data['vnd_bank_branch']; ?></div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-3 pt10">
                        <div class="col-xs-12 "><b>Account No: </b> <?= $data['vnd_bank_account_no']; ?></div>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-3 pt10">
                        <div class="col-xs-12"><b>Account Type: </b><?= $accType[$data['vnd_account_type']]; ?></div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-3 pt10">
                        <div class="col-xs-12"><b>IFSC Code: </b><?= $data['vnd_bank_ifsc']; ?></div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-3 pt10">
                        <div class="col-xs-12"><b>Beneficiary Name: </b><?= $data['vnd_beneficiary_name']; ?></div>
                    </div>
                </div> 
				<?
				if ($data['vnd_firm_type'] != 0)
				{
				?>
				<div class="row bordered">

					<div class="col-xs-12 col-sm-6 col-md-3 pt10">
						<div class="col-xs-12"><b>Firm Type: </b><?= $firm_type[$data['vnd_firm_type']]; ?></div>
					</div>
					<?
					if ($model->vnd_firm_pan != '')
					{
					?>
					<div class="col-xs-12 col-sm-6 col-md-3 pt10">
						<div class="col-xs-12"><b>PAN: </b><?= $model->vnd_firm_pan; ?></div>
					</div>
					<?
					}if ($model->vnd_firm_ccin != '')
					{
					?>
					<div class="col-xs-12 col-sm-6 col-md-3 pt10">
						<div class="col-xs-12"><b>CCIN: </b><?= $model->vnd_firm_ccin; ?></div>
					</div>
					<?
					}
					}
					?>
                </div>
            </div>    
        </div>
    </div>    
</div>



<div class="row booking-log text-center">
    <label class = "control-label h3">Vendor Collection</label>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="col-xs-12 col-md-11">
                <div class="table table-bordered">
					<?php
					$acctData			 = AccountTransDetails::getLastPaymentReceived($data['vnd_id'], '2');
					$paymentRecv		 = ($acctData['paymentReceived'] > 0) ? $acctData['paymentReceived'] : '0';
					$paymentRecvDate	 = ($acctData['ReceivedDate'] != '') ? $acctData['ReceivedDate'] : '';
					$checkContactAccess	 = Yii::app()->user->checkAccess("bookingContactAccess");
					if (!empty($dataProvider))
					{
						$params									 = array_filter($_REQUEST);
						$dataProvider->getPagination()->params	 = $params;
						$dataProvider->getSort()->params		 = $params;
						$this->widget('booster.widgets.TbGridView', array(
							'responsiveTable'	 => true,
							'dataProvider'		 => $dataProvider,
							'template'			 => "<div class='panel-body table-responsive p0'>{items}</div>",
							'itemsCssClass'		 => 'table table-striped table-bordered dataTable',
							'htmlOptions'		 => array('class' => 'panel panel-primary compact mb0'),
							//       'ajaxType' => 'POST',
							'columns'			 => array(
								array('name'	 => 'vrs_credit_limit', 'value'	 => function($data) {
										echo '<i class="fa fa-inr"></i>' . $data['vnd_credit_limit'];
									}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Credit limit'),
								array('name'	 => 'vnd_effective_credit_limit', 'value'	 => function($data) {
										if ($data['vnd_effective_credit_limit'] != 0)
										{
											echo '<i class="fa fa-inr"></i>' . $data['vnd_effective_credit_limit'];
										}
										else
										{
											echo '<i class="fa fa-inr">0</i>';
										}
									}
									, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Effective Credit Limit'),
								array('name'	 => 'vnd_effective_overdue_days', 'value'	 => function($data) {
										if ($data['vnd_effective_overdue_days'] != 0)
										{
											echo '' . $data['vnd_effective_overdue_days'];
										}
										else
										{
											echo '0';
										}
									}
									, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Overdue Days'),
								array('name'	 => 'security', 'value'	 => function($data) {
										if ($data['vnd_security_amount'] > 0)
										{
											echo '<i class="fa fa-inr"></i>' . $data['vnd_security_amount'] . ' on ' . DateTimeFormat::DateToDatePicker($data["vrs_security_receive_date"]);
										}
									}, 'sortable'			 => false, 'filter'			 => FALSE, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Security Deposit'),
								array('name'	 => 'lastTrans', 'value'	 => function($data) {
										$acctData	 = AccountTransDetails::getLastPaymentReceived($data['vnd_id'], '2');
										$paymentRecv = ($acctData['paymentReceived'] > 0) ? $acctData['paymentReceived'] : '0';
										echo '<i class="fa fa-inr">' . $paymentRecv . '</i>';
									}
									, 'sortable'			 => false
									, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center')
									, 'htmlOptions'		 => array('class' => 'text-right')
									, 'header'			 => 'Last Payment recvd amt'),
								array('name'	 => 'lastTransDate',
									'value'	 => function($data) {
										$acctData = AccountTransDetails::getLastPaymentReceived($data['vnd_id'], '2');
										echo ($acctData['ReceivedDate'] != '') ? $acctData['ReceivedDate'] : '';
									},
									'sortable'			 => false
									, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center')
									, 'htmlOptions'		 => array('class' => 'text-left')
									, 'header'			 => 'Last Payment recieved date'),
								array('name'	 => 'totTrans',
									'value'	 => function($data) {
										echo '<i class="fa fa-inr"></i>' . $data['totTrans'] . "<br>";
										echo '<i class="fa fa-inr"></i>' . $data['vrs_locked_amount'];
									}
									, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Running Balance;<br>Locked Amount'),
								array('name'	 => 'withdrawable_balance',
									'value'	 => function($data) {
										echo '<i class="fa fa-inr"></i>' . $data['withdrawable_balance'];
									}
									, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Withdrawable Balance'),
								array('name' => 'rating', 'value' => '$data[rating]', 'sortable' => false, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Rating'),
						)));
					}
					?> 
                </div>
            </div>

            <div class="col-xs-12 col-md-1 pl0 ml0 text-center"><?php echo CHtml::link('Show Details', Yii::app()->createUrl('admin/vendor/vendoraccount/', ['vnd_id' => $model->vnd_id]), ['class' => 'btn btn-primary mb10']) ?></div>
        </div>
    </div>
</div>

<!--<div class="row booking-log">
    <div class="col-xs-12 ">
        <div class="col-xs-12 text-center">
            <label class = "control-label h3 ">Vendor Log</label>
        </div>
<?
//Yii::app()->runController('admin/vendor/showlog/agtid/' . $model->vnd_id . '/view/1');
?>
    </div>
</div>-->


<script  type="text/javascript">
    function reduceLvl()
    {

        var level = <?= $model->vnd_rel_tier; ?>;
        if (level == 0)
        {
            alert("Vendor is aleady in lowe level");
            return false;
        }
        bootbox.confirm({
            message: "Are you sure want to reduce level?",
            buttons: {
                confirm: {
                    label: 'OK',
                    className: 'btn-info'
                },
                cancel: {
                    label: 'CANCEL',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if (result) {
                    var vnd_id = <?= $model->vnd_id; ?>;
                    var href1 = '<?= Yii::app()->createUrl('admin/vendor/reduce') ?>';
                    jQuery.ajax({'type': 'GET', 'url': href1,
                        'data': {'vnd_id': vnd_id},
                        success: function (data)
                        {
                            alert(data);
                            bootbox.hideAll()
                            window.location.reload(true);

                        }
                    });
                }
            }
        });
    }
    function viewLog()
    {

        $href = "<?php echo Yii::app()->createUrl("admin/vendor/showlog") ?>";
        $id = <?php echo $model->vnd_id; ?>;

        jQuery.ajax({type: 'GET',
            url: $href,
            data: {"vndid": $id},
            success: function (data)
            {
                remarkBox = bootbox.dialog({
                    message: data,
                    size: 'large',
                    title: 'Show Log',
                    onEscape: function () {

                    },
                });
            }
        });
    }



    function viewBiddingLog()
    {

        $href = "<?php echo Yii::app()->createUrl("admin/vendor/bidlog") ?>";
        $id = <?php echo $model->vnd_id; ?>;

        jQuery.ajax({type: 'GET',
            url: $href,
            data: {"vndid": $id},
            success: function (data)
            {
                remarkBox = bootbox.dialog({
                    message: data,
                    size: 'large',
                    title: 'Show Log',
                    onEscape: function () {

                    },
                });
            }
        });
    }

    function updateVendorDetails()
    {

        $href = "<?php echo Yii::app()->createUrl("admin/vendor/UpdateDetails") ?>";
        $id = <?php echo $model->vnd_id; ?>;

        jQuery.ajax({type: 'GET',
            url: $href,
            data: {"vnd_id": $id},
            success: function (data)
            {
                data = JSON.parse(data);
                bootbox.alert(data.message);

            }
        });
    }

    function updateVendorDocs(id, status)
    {
        var href = '<?= Yii::app()->createUrl("admin/vendor/updateDoc"); ?>';
        $.ajax({
            "url": href,
            "type": "GET",
            "dataType": "html",
            "data": {"vd_id": id, "vd_status": status},
            "success": function (data1)
            {
                var dataSet = data1.split("~");
                if (dataSet[1] == 1)
                {
                    var img = dataSet[0] + dataSet[1];
                    $(dataSet[0]).show();
                    $(dataSet[0]).css("display", "block");
                    $(dataSet[0]).removeClass('label-info');
                    $(dataSet[0]).addClass('label label-success');
                    $(dataSet[0]).html("Approved");
                    $(img).hide();
                }
                if (dataSet[1] == 2)
                {
                    $(dataSet[0]).show();
                    $(dataSet[0]).css("display", "block");
                    $(dataSet[0]).removeClass('label-info');
                    $(dataSet[0]).removeClass('label-success');
                    $(dataSet[0]).addClass('label label-danger');
                    $(dataSet[0]).html("Rejected");

                    var rejectImg = dataSet[0] + dataSet[1];
                    var approveImg = dataSet[0] + '1';
                    var reloadImg = dataSet[0] + '3';
                    var reloadRemarks = dataSet[0] + '33';
                    $(dataSet[0]).show();
                    $(rejectImg).hide();
                    $(approveImg).hide();
                    $(reloadImg).show();
                    $(reloadRemarks).hide();
                } else if (dataSet[1] == 3)
                {
                    var div = dataSet[0] + 'Div';
                    var img = dataSet[0] + dataSet[1];
                    $(dataSet[0]).hide();
                    $(div).show();
                    $(img).hide();
                }


            }
        });
        return false;
    }
</script>