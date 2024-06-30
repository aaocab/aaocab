<style>
	.search-cabs-box {
		background: #fff;
		border: #e2e2e2 1px solid;
		position: relative;
		-webkit-box-shadow: 0px 7px 8px -2px rgba(230,230,230,1);
		-moz-box-shadow: 0px 7px 8px -2px rgba(230,230,230,1);
		box-shadow: 0px 7px 8px -2px rgba(230,230,230,1);
	}
</style>
<?php
/* @var $model UnregVendorRequest */
$selectizeOptions = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];

/* @var $bkgModel Booking */
/* @var $buvModel BookingUnregVendor */

if ($sendParams['buv_is_add'] == 0 && $sendParams['buv_active'] == 1)
{
	$styleCSS = 'style="display:block"';
}
?>
<script>
    if ($.fn.yiiGridView != undefined && $.fn.yiiGridView.settings['assinvendorgrid'] != undefined) {
        $(document).off('click.yiiGridView', $.fn.yiiGridView.settings['assinvendorgrid'].updateSelector);
    }
</script>
<style type="text/css">
    .checkbox{
        margin-top: 0;margin-bottom: 0;
    }
</style>
<div id="vendor-content" class="panel-advancedoptions" >
    <div class="errorSummary alert alert-block alert-danger" style="display: none"></div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel">
				<div class="panel panel-default">
					<?php
					$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'unregvendorForm', 'enableClientValidation' => true,
						'clientOptions'			 => array(
							'validateOnSubmit'	 => true,
							'errorCssClass'		 => 'has-error',
						),
						// Please note: When you enable ajax validation, make sure the corresponding
						// controller action is handling ajax validation correctly.
						// See class documentation of CActiveForm for details on this,
						// you need to use the performAjaxValidation()-method described there.
						'enableAjaxValidation'	 => false,
						'errorMessageCssClass'	 => 'help-block',
						'htmlOptions'			 => array(
							'class' => 'form-horizontal'
						),
					));
					/* @var $form TbActiveForm */
					?>

					<div class="panel-body pt20 new-booking-list">
						<div class="row">
							<div id="vndMSg" <?= $styleCSS; ?> 
								 <p><b><h3>This vendor (<?= $model->uvr_vnd_name ?>&nbsp;<?= $model->uvr_vnd_lname ?>) has been sent to approval queue.
											Approve this vendor, get his car and driver papers and assign to booking <?= $bkgModel->bkg_booking_id ?>.</h3></b>&nbsp;
									</br>

									<a href="#" onclick="windowsClosed()">close window</a></p>
								</br>


							</div></br>


							<div class="col-xs-12 col-sm-6 col-md-5" >
								<h3><i class="fa fa-map-signs"></i>APPLICANT DETAILS</h3>
								<div class="col-xs-12 search-cabs-box mb30 hidden-xs">
									<div class="row p10">
										<h5><p><b>First name : </b><?= $model->uvr_vnd_name ?></p>
											<p><b>Last name : </b><?= $model->uvr_vnd_lname ?></p>
											<p><b>Phone number : </b><?= $model->uvr_vnd_phone ?></p>
											<p><b>Email address : </b><?= $model->uvr_vnd_email ?></p>
											<p><b>City : </b> <?= Cities::getName($model->uvr_vnd_city_id) ?></p>
										</h5>
									</div>
									<?= $form->hiddenField($model, 'uvr_vnd_name'); ?>
									<?= $form->hiddenField($model, 'uvr_vnd_lname'); ?>
									<?= $form->hiddenField($model, 'uvr_vnd_phone'); ?>
									<?= $form->hiddenField($model, 'uvr_vnd_email'); ?>
									<?= $form->hiddenField($model, 'uvr_vnd_city_id'); ?>
									<input type="hidden" name="buv_id"  id="buv_id"  value="<?= $sendParams['buv_id'] ?>">

									<div class="row" id="div_applicant_show">
										<?php
										if ($sendParams['buv_is_add'] == 0 && $sendParams['buv_active'] == 1)
										{
											?>
											<div class="col-xs-11 col-md-8 col-lg-4 top-buffer">
												<button type="button" class="btn btn-primary" onclick="unregisterVendorAdd('2')">Accept Bid</button>
											</div>

											<div class="col-xs-11 col-md-8 col-lg-4 top-buffer">
												<button type="button" class="btn btn-primary" onclick="unregisterVendorAdd('3')">Decline Bid and Close</button>
											</div>
											<?php
										}
										?>
									</div> 

								</div>
							</div>


							<div class="col-xs-12 col-sm-6 col-md-4">
								<h3><i class="fa fa-map-signs"></i>BOOKING DETAILS</h3>
								<div class="col-xs-12 search-cabs-box mb30 hidden-xs">
									<div class="row p10">
										<h5><p><b>Booking Id : </b><?= $sendParams['booking_id'] ?></p>
											<p><b>Trip Id : </b><?= $sendParams['trip_id'] ?></p>
											<p><b>Route : </b><?= $sendParams['from_city'] . " - " . $sendParams['to_city']; ?></p>
											<p><b>Booking Create Date : </b><?= $sendParams['created_date'] ?></p>
											<p><b>Pickup Date : </b><?= $sendParams['pickup_date'] ?></p>
											<p><b>Pickup Address : </b><?= $sendParams['pickup_address'] ?></p>
											<p><b>Trip Distance : </b><?= $sendParams['trip_distance'] . "Kms." ?></p>
											<p><b>Bid Amount : </b><?= $sendParams['buv_bid_amount'] ?></p>
										</h5>
									</div>
								</div>

							</div>	

							<div class="col-xs-12 col-sm-6 col-md-3">
								<h3><div id="error_message"></div></h3>
								<div id="venjoin1">
									<h3><i class="fa fa-map-signs"></i>DOCUMENT DETAILS</h3>
									<div class="col-xs-12 search-cabs-box mb30 hidden-xs">
										<div class="row p10">
											<h5>
												<p><b> Voter No : </b>
													<?= ($model->uvr_vnd_voter_no != '') ? $model->uvr_vnd_voter_no : 'NA'; ?></p>
												<p><b>Voter Front :</b>
													<?php
													if ($model->uvr_vnd_voter_id_front_path != '')
													{
														?>
														<a href="<?= $model->uvr_vnd_voter_id_front_path; ?>" target="_blank">Attachment Link</a>
														<?php
													}
													else
													{
														echo 'NA';
													}
													?>
												</p>

												<p><b> Pan No : </b>
													<?= ($model->uvr_vnd_pan_no != '') ? $model->uvr_vnd_pan_no : 'NA'; ?> </p>
												<p><b>Pan Front :</b>
													<?php
													if ($model->uvr_vnd_pan_front_path != '')
													{
														?>
														<a href="<?= $model->uvr_vnd_pan_front_path; ?>" target="_blank">Attachment Link</a>
														<?php
													}
													else
													{
														echo 'NA';
													}
													?>
												</p>
												<p><b> Licence No : </b>
													<?= ($model->uvr_vnd_license_no != '') ? $model->uvr_vnd_license_no : 'NA'; ?>
												</p>

												<p><b>Licence Front :</b>
													<?php
													if ($model->uvr_vnd_licence_front_path != '')
													{
														?>
														<a href="<?= $model->uvr_vnd_licence_front_path; ?>" target="_blank">Attachment Link</a>
														<?php
													}
													else
													{
														echo 'NA';
													}
													?>
												</p>	

												<p><b>Licence Exp Date :</b>
													<?= ($model->uvr_vnd_license_exp_date != '') ? date('d-m-Y', strtotime($model->uvr_vnd_license_exp_date)) : 'NA'; ?>
												</p>					
												<p><b>DCO :</b>
													<?= ($model->uvr_vnd_is_driver == 1) ? 'Yes' : 'No'; ?>
												</p>

												</br>

										</div>

									</div>
								</div>

							</div>


						</div>
					</div>
				</div>

			</div><?php $this->endWidget(); ?>
		</div>
	</div>
</div>
</div>
</div><!--<?
print_r($GLOBALS['time']);
?>-->
<script type="text/javascript">

    $(document).ready(function () {
        $('#vndMSg').hide();
    });
    $sourceList = null;
    function populateSource(obj, cityId)
    {
        obj.load(function (callback) {
            var obj = this;
            if ($sourceList == null) {
                xhr = $.ajax({
                    url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery', ['apshow' => 0, 'city' => ''])) ?>' + cityId,
                    dataType: 'json',
                    data: {
                        // city: cityId
                    },
                    //  async: false,
                    success: function (results) {
                        $sourceList = results;
                        obj.enable();
                        callback($sourceList);
                        obj.setValue(cityId);
                    },
                    error: function () {
                        callback();
                    }
                });
            } else {
                obj.enable();
                callback($sourceList);
                obj.setValue(cityId);
            }
        });
    }
    function loadSource(query, callback) {
        //	if (!query.length) return callback();
        $.ajax({
            url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery')) ?>?apshow=0&q=' + encodeURIComponent(query),
            type: 'GET',
            dataType: 'json',
            global: false,
            error: function () {
                callback();
            },
            success: function (res) {
                callback(res);
            }
        });
    }

    function unregisterVendorAdd(isAdd)
    {
        var buv_id, firstName, lastName, phoneNumber, emailAddress, businessCity;
        var is_Add = isAdd;
        buv_id = $('#buv_id').val();
        firstName = $('#UnregVendorRequest_uvr_vnd_name').val();
        lastName = $('#UnregVendorRequest_uvr_vnd_lname').val();
        phoneNumber = $('#UnregVendorRequest_uvr_vnd_phone').val();
        emailAddress = $('#UnregVendorRequest_uvr_vnd_email').val();
        businessCity = $('#UnregVendorRequest_uvr_vnd_city_id').val();

        if (isNaN($('#<?= CHtml::activeId($model, "uvr_vnd_phone") ?>').val()) == false)
        {
            $href = '<?= Yii::app()->createUrl('admin/booking/addVndAccept') ?>';
            jQuery.ajax({type: 'GET', url: $href,
                data: {'buv_id': buv_id, 'is_Add': is_Add, 'firstName': firstName, 'lastName': lastName, 'phoneNumber': phoneNumber, 'emailAddress': emailAddress, 'businessCity': businessCity},
                success: function (data)
                {
                    //alert(data);
                    if (data)
                    {
                        var obj = JSON.parse(data);
                        if (obj.newStatus == 2)
                        {
                            $('#vndMSg').show();
                            $('#div_applicant_show').hide();
                        }
                        if (obj.newStatus == 3)
                        {
                            $('#vndMSg').hide();
                            $('#div_applicant_show').hide();
                            windowsClosed();
                        }

                    } else
                    {
                        console.log(data);
                        var data1 = JSON.parse(data);
                        if ($('#<?= CHtml::activeId($model, "uvr_vnd_name") ?>').val() == '')
                        {
                            $('#<?= CHtml::activeId($model, "uvr_vnd_name") ?>').parent().addClass('has-error');
                            $('#<?= CHtml::activeId($model, "uvr_vnd_name") ?>').parent().find('.help-block').css('display', 'block');
                            $('#<?= CHtml::activeId($model, "uvr_vnd_name") ?>').parent().find('.help-block').text(data1.UnregVendorRequest_uvr_vnd_name[0]);
                        }
                    }

                    //					setTimeout(function(){
                    //					window.location.href = '<?= Yii::app()->createUrl('aaohome/booking/list?tab=2') ?>';
//					}, 1000);	
                },
                error: function () {
                    alert(error);
                }
            });
        }


    }

    function windowsClosed()
    {
        window.close();
        //var objWindow = window.open("about:blank", "_self");
        //objWindow.close();
    }

</script>

