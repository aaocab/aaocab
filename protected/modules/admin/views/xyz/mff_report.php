<link rel="stylesheet" href="/css/font-awesome/css/font-awesome.css">
<link rel="stylesheet" href="/css/site.min.css">
<link rel="stylesheet" type="text/css" href="/css/component.css"/>
<link href="/css/hover.css" rel="stylesheet" media="all">
<link rel="stylesheet" href="/css/site.css?v=site.css?v=<?= Yii::app()->params['sitecssVersion']; ?>">
<title>Magnetic Field Festival Report</title>
<? if ($error == 1)
{
	?>
	<div class="row m0 mt20" id="passwordDiv">
		<?php
		$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'booking-form', 'enableClientValidation' => true,
			'clientOptions'			 => array(
				'validateOnSubmit'	 => true,
				'errorCssClass'		 => 'has-error'
			),
			'enableAjaxValidation'	 => false,
			'errorMessageCssClass'	 => 'help-block',
			'htmlOptions'			 => array(
				'class'		 => 'form-horizontal', 'enctype'	 => 'multipart/form-data',
			),
		));
		?>
		<div class="col-xs-offset-4 col-xs-4">   
			<div class="form-group row text-center">
				<input class="form-control" type="password" id="psw" name="psw" value="" placeholder="Password" required/>
			</div>
			<div class="Submit-button row text-center">
	<?= CHtml::submitButton('Submit', array('class' => 'btn btn-primary pl30 pr30')); ?>
			</div>
		</div>
	<?php $this->endWidget(); ?>
	</div>
<? } ?>
<? if ($error == 2)
{
	?>
	<div class="row m0 mt20" id="wrongPassword" style="">
		<div class="col-xs-offset-4 col-xs-4">
			<h3>Wrong Password</h3>
			<img src="http://static.commentcamarche.net/es.ccm.net/pictures/Ud6krzOUaQiVrbx4IWkuzUrMD8vWr4qbG1wMtmWKQ94r7Doi6fybXXnACJoLFtKR-lol.png">
		</div>
	</div>
<? } ?>
<? if ($error == 0)
{
	?>
	<div class="row" id="bookingsDiv" style="margin-top: 10px;">  
		<div class="col-xs-12 col-sm-12 col-md-12 float-none marginauto">      
				<? if ($admin == 1)
				{
					?>
				<div class="row m0 mt20">
					<?php
					$form			 = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'search-form', 'enableClientValidation' => true,
						'clientOptions'			 => array(
							'validateOnSubmit'	 => true,
							'errorCssClass'		 => 'has-error'
						),
						'enableAjaxValidation'	 => false,
						'errorMessageCssClass'	 => 'help-block',
						'htmlOptions'			 => array(
							'class' => 'form-horizontal'
						),
					));
					?>    
					<div class="col-xs-12 well"  style="text-align: center">
						<div class="col-xs-3">    
							<input type="radio" name="booktype" value="1"  <? echo ($bType == 1) ? 'checked' : ''; ?>>Corporate&nbsp;&nbsp;<input type="radio" name="booktype" value="2" <? echo ($bType == 2) ? 'checked' : ''; ?>>Non Corporate&nbsp;&nbsp;<input type="radio" name="booktype" value="3" <? echo ($bType == 3) ? 'checked' : ''; ?>>All
						</div>
						<div class="col-xs-5">    
							<?
							$vendorListJson	 = Vendors::model()->getJSONCorpvendor();
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $modelBookingMff,
								'attribute'		 => 'vendor_id',
								'val'			 => $model->vendor_id,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($vendorListJson), 'allowClear' => true),
								'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Vendor')
							));
							?>
						</div>
						<div class="col-xs-4"> 
							<div class="col-xs-8">
								<?
								$data			 = array_unique(CHtml::listData(BookingMff::model()->findAll(array('select' => 't.bmf_id, t.bmf_pickup_cordinator', 'distinct' => true)), 'bmf_id', 'bmf_pickup_cordinator'));
								?>
								<?=
								$form->typeAheadGroup($modelBookingMff, 'bmf_pickup_cordinator', array('label'			 => '', 'widgetOptions'	 => array(
										'options'		 => array(
											'hint'		 => true,
											'highlight'	 => true,
											'minLength'	 => 1
										),
										'datasets'		 => ['source' => $data],
										'htmlOptions'	 => ['placeholder' => "Enter Coordinator Name"])))
								?>  
							</div>
							<div class="col-xs-4">
								<input type="button" class="btn btn-info" name="searchbycord" onclick="filterByCond();" value="search">
							</div>
						</div>

					</div>
		<?php $this->endWidget(); ?>
				</div>
	<? } ?>
			<div class="col-xs-12" style="color: blue;background: whitesmoke;text-align: center">
				<u>Booking Details(Magnetic Fields Festival)</u>
			</div> <div class="col-xs-12" style="text-align: center">(Total :<?= count($model) ?>)</div>
			<table class="table table-bordered" style="background: #fff; font-size: 13px;">
				<thead>

					<tr style="color: black;background: whitesmoke">
						<th class="text-center"><u>Booking ID</u></th>
						<th class="text-center"><u>Name</u></th>
						<th class="text-center"><u>Contact</u></th>
						<th class="text-center"><u>Coordinator</u></th>
						<th class="text-center"><u>Status</u></th>
						<th class="text-center"><u>Cab Details</u></th> 
						<th class="text-center"><u>Flight Number</u></th> 
						<th class="text-center"><u>Routes</u></th>
						<th class="text-center"><u>Pickup Date/Time</u></th>
						<th class="text-center"><u>Cab Type</u></th>
	<? if ($admin == 1)
	{
		?>
							<th class="text-center">Payment to collect</th>
					<? } ?>
						<th class="text-center"><u>Action</u></th>
					</tr>
				</thead>
				<tbody id="booking_row">                         
							<?
							foreach ($model as $data)
							{
								?>
						<tr>
							<td class="text-center"><?= $data['bkg_booking_id'] ?><br><?
								$str = "";
								if ($data['bkg_agent_id'] > 0)
								{
									$str = '<b>(Corporate Booking)</b>';
								}
								if ($data['bkg_advance_amount'] > 0)
								{
									$str .= "<br>(Advance Paid: Rs." . round($data['bkg_advance_amount']) . ")";
								}
								echo $str;
								?></td>
							<td class="text-center"><?= $data['name'] ?></td>
							<td class="text-center" style="word-break: break-all;"><? echo $data['bkg_contact_no'] . "<br>" . $data['bkg_user_email']; ?></td>
							<td class="text-center"><? echo ($data['cordinator'] != '') ? $data['cordinator'] : ''; ?></td>
							<td class="text-center"><?
								if ($data['bmf_status'] != '')
								{
									$mffStatus = $data['bmf_status'];
								}
								else
								{
									$mffStatus = 1;
									if ($data['bkg_status'] == 6)
									{
										$mffStatus = 4;
									}
									if ($data['bkg_status'] == 5)
									{
										$mffStatus = 3;
									}
									if ($data['bkg_status'] == 7)
									{
										$mffStatus = 4;
									}
								}
								echo BookingMff::model()->getStatus($mffStatus);
								?></td>
							<td class="text-center"><?
								if ($data['vendorname'] != '')
								{
									if ($admin == 1)
									{
										echo "<b>Vendor :</b>" . $data['vendorname'];
									}

									if ($data['driver'] != '' && $data['driver_phone'] != '' && $data['cabnumber'] != '')
									{
										$strAppr = "";
										if ($data['drv_approved'] == 1)
										{
											$strAppr = "<span class='label label-success p2 m2' title='Driver Approved'><i class='fa fa-check'></i></span>";
										}
										else
										{
											$strAppr = "<span class='label label-danger p2 m2' title='Driver Not Approved'><i class='fa fa-times'></i></span>";
										}
										if ($admin != 1)
										{
											$strAppr = '';
										}
										echo "<br><b>Driver :</b>" . $data['driver'] . " " . $strAppr;
										echo "<br><b>Driver Phone :</b>" . $data['driver_phone'];
										$strCabAppr = "";
										if ($data['vhc_approved'] == 1)
										{
											$strCabAppr = "<span class='label label-success p2 m2' title='Cab Approved'><i class='fa fa-check'></i></span>";
										}
										else
										{
											$strCabAppr = "<span class='label label-danger p2 m2' title='Cab Not Approved'><i class='fa fa-times'></i></span>";
										}
										if ($admin != 1)
										{
											$strCabAppr = '';
										}
										echo "<br><b>Cab Number :</b>" . $data['cabnumber'] . " " . $strCabAppr;
									}
								}
								?></td>
							<td class="text-center"><?
								echo "<b>" . $data['bkg_flight_no'] . "</b>";
								if ($data['bkg_flight_info'] != '')
								{
									$flightInfoArr = json_decode($data['bkg_flight_info']);
									echo "<br>Last Updated :" . $flightInfoArr->lastUpdated . "<br>"
									. $flightInfoArr->from . " to " . $flightInfoArr->to . "(" . $flightInfoArr->status . ")<br>"
									//  ."Scheduled Departure :".$flightInfoArr->schDept."<br>"
									. "Scheduled Arr :" . date('d-m-Y H:i:s', strtotime($flightInfoArr->schArr)) . "<br>"
									// ."Actual Departure :".$flightInfoArr->actDept."<br>"
									. "Actual Arr :" . date('d-m-Y H:i:s', strtotime($flightInfoArr->actArr)) . "<br>"
									. "Delay Arr :" . $flightInfoArr->delayArr . "<br>"
									. "Arrival Terminal :" . $flightInfoArr->arrTerminal . "<br>";
								}
								?></td>
							<td class="text-center"><?= BookingRoute::model()->getRouteName($data['bkg_id']); ?></td>                        
							<td class="text-center"><?= date("d/m/Y H:i:s", strtotime($data['bkg_pickup_date'])) ?></td>
							<td class="text-center"><?
								if ($data['assigned_cab_type'] != '')
								{
									echo "Assigned :" . $data['assigned_cab_type'] . "<br>Requested :" . $data['cab_type'];
								}
								else
								{
									echo "Requested :" . $data['cab_type'];
								}
								?></td>
								<? if ($admin == 1)
								{
									?>
								<td><?= round($data['bkg_due_amount']) ?></td>
		<? } ?>
							<td class="text-center" style="white-space: nowrap">  

								<? if ($admin == 1)
								{
									?>                          
									<img src="<?= Yii::app()->request->baseUrl . '\images\icon\lead_report\assign_CSR.png' ?>" style="cursor: pointer" title="Add/Change Pickup Coordinator" onclick="addCordinator('<?= $data['bkg_id'] ?>')">
			<!--                               <button class="btn btn-primary btn-small p0 m0" title="Change Status" onclick="changeStatus(<? // =$data['bkg_id'] ?>)">change status</button>-->

									<img src="<?= Yii::app()->request->baseUrl . '\images\icon\lead_report\convert_lead_booking.png' ?>" style="cursor: pointer" title="Change Status" onclick="showStatus(<?= $data['bkg_id'] ?>,<?= $mffStatus ?>, '<?= $data['bkg_booking_id'] ?>')">
								<? } ?>
									<?
									if ($data['bkg_flight_no'] != '')
									{
										//                        $diff = floor((strtotime($data['bkg_pickup_date']) - time()) / 3600);
										//			if ($diff <= 4)
										//			{	
										?>
									<button class="btn btn-info btn-small" title="CHECK FLIGHT STATUS" onclick="showFlightStatus(<?= $data['bkg_id'] ?>)"><i class="fa fa-plane" aria-hidden="true"></i></button>
							<?
							// }
						}
						?>
							</td>
						</tr>

			<?
		}
		?>
				</tbody>
			</table>

		</div>
		<!--        <div class="col-xs-12 col-sm-12 col-md-10 float-none marginauto well ">
	<?php
	// $this->widget('CLinkPager', array('pages' => $usersList->pagination));
	?>
				</div>-->
	</div>






<? } ?>
<script>
    function showFlightStatus(bkgId) {

        jQuery.ajax({type: 'GET',
            url: '<?= Yii::app()->createUrl('admin/booking/flightstatus'); ?>',
            dataType: 'json',
            data: {'bkgId': bkgId},
            success: function (data) {
                if (data.success) {
                    var actualdepart = (data.actualDepartTime == null || data.actualDepartTime == 'null' || data.actualDepartTime == undefined) ? '&nbsp;' : data.actualDepartTime;
                    var actualarrive = (data.actualArriveTime == null || data.actualArriveTime == 'null' || data.actualArriveTime == undefined) ? '&nbsp;' : data.actualArriveTime;
                    var delayarrive = (data.delayArrive == null || data.delayArrive == 'null' || data.delayArrive == undefined) ? '&nbsp;' : data.delayArrive + " minutes";
                    var arriveTerminal = (data.arriveTerminal == null || data.arriveTerminal == 'null' || data.arriveTerminal == undefined) ? '&nbsp;' : data.arriveTerminal;
                    var html = "<div><b>Status :</b>" + data.status +
                            "<br><br><b>From :</b>" + data.from + "<br><br><b>To :</b>" + data.to +
                            "<br><br><b>Scheduled Departure :</b>" + data.scheduledDepartTime + "<br><br><b>Scheduled Arrival :</b>" + data.scheduledArriveTime +
                            "<br><br><b>Actual Departure :</b>" + actualdepart + "<br><br><b>Actual Arrival :</b>" + actualarrive +
                            "<br><br><b>Delay Arrival :</b>" + delayarrive + "<br><br><b>Arrival Terminal :</b>" + arriveTerminal +
                            "</div>";
                    var flightbootbox = bootbox.dialog({
                        message: html,
                        title: 'Track Flight',
                        onEscape: function () {
                        }
                    });

                } else {
                    alert(data.msg);
                }



            },
            error: function (x) {
                alert(x);
            }
        });
    }
    $('input:radio[name="booktype"]').unbind('change');
    $('input:radio[name="booktype"]').change(
            function () {
                if ($(this).is(':checked')) {
                    filterByCond();
                }
            });
    function filterByCond() {
        $('#search-form').submit();
    }
    function addCordinator(bkg_id) {

        jQuery.ajax({type: 'GET',
            url: '<?= Yii::app()->createUrl('admin/xyz/addcordinator'); ?>',
            dataType: 'html',
            data: {'bkgId': bkg_id},
            success: function (data) {
                var cordinatorbootbox = bootbox.dialog({
                    message: data,
                    title: 'Add Pickup Cordinator',
                    onEscape: function () {
                    }
                });
            },
            error: function (x) {
                alert(x);
            }
        });
    }


    function showStatus(bkg_id, status, booking_id) {
        var cordinatorbootbox = bootbox.dialog({
            message: '<div><input type="radio" name="statusid" id="stat1" value="1">Pickup Pending&nbsp;&nbsp;<input type="radio" name="statusid"  id="stat2"  value="2">Car Ready&nbsp;&nbsp;<input type="radio" name="statusid" id="stat5" value="5">Customer Not Ready&nbsp;&nbsp;<input type="radio" name="statusid" id="stat3"  value="3">Picked Up&nbsp;&nbsp;<input type="radio" name="statusid" id="stat4"  value="4">Reached<br><input type="button" class="btn btn-info" onclick="changeStatus(' + bkg_id + ');" value="submit"></div>',
            title: 'Change Booking Status (' + booking_id + ')',
            onEscape: function () {
            }
        });
        if (status == 1) {
            document.getElementById("stat1").checked = true;
        }
        if (status == 2) {
            document.getElementById("stat2").checked = true;
        }
        if (status == 3) {
            document.getElementById("stat3").checked = true;
        }
        if (status == 4) {
            document.getElementById("stat4").checked = true;
        }
        if (status == 5) {
            document.getElementById("stat5").checked = true;
        }

    }
    function changeStatus(bkg_id) {
        var status = $("input:radio[name=statusid]:checked").val();
        jQuery.ajax({type: 'GET',
            url: '<?= Yii::app()->createUrl('admin/xyz/changestatus'); ?>',
            dataType: 'json',
            data: {'bkgId': bkg_id, 'status': status},
            success: function (data) {
                if (data.success) {
                    location.href = '<?= Yii::app()->createUrl('admin/xyz/mffreport'); ?>';
                }
            },
            error: function (x) {
                alert(x);
            }
        });
    }

</script>
