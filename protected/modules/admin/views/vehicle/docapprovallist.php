<style type="text/css">
    .pic{
        max-width: 100%;
        max-height: 175px;

    }
</style>

<?php
$docType = VehicleDocs::model()->doctypeTxt;
unset($docType[8], $docType[9], $docType[10], $docType[11], $docType[13]);
?>
<div id="list-content">
    <div class="row" >
        <div class="panel">
            <div class="panel-heading">Pending Document Vehicles to approve</div>
            <div class="panel-body">
                <div class="col-xs-12">
					<?php
					$form	 = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'vehicletype-form', 'enableClientValidation' => true,
						'clientOptions'			 => array(
							'validateOnSubmit'	 => true,
							'errorCssClass'		 => 'has-error'
						),
						// Please note: When you enable ajax validation, make sure the corresponding
						// controller action is handling ajax validation correctly.
						// See class documentation of CActiveForm for details on this,
						// you need to use the performAjaxValidation()-method described there.
						'enableAjaxValidation'	 => false,
						'errorMessageCssClass'	 => 'help-block',
						'htmlOptions'			 => array(
							'class' => '',
						),
					));
					?>
                    <div class="row">

                        <div class="col-xs-6 col-sm-3">
							<?= $form->textFieldGroup($model, 'vhcnumber', array('label' => 'Vehicle Number', 'widgetOptions' => array())) ?>
                        </div>
                        <div class="col-xs-6 col-sm-3">
                            <label class="control-label">Document Type </label>
							<?php
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'vhd_type',
								'val'			 => $model->vhd_type,
								'data'			 => $docType,
								'options'		 => ['allowClear' => true],
								'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Document Type')
							));
							?>
                        </div>
						<div class="col-xs-12 col-sm-3">
							<label class="control-label"></label>
							<?= $form->checkboxListGroup($model, 'newestVhc', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Sort by newest vehicle first'), 'htmlOptions' => []))) ?>
						</div>
                        <div class="col-xs-12 col-sm-3 text-center mt20 pt5">
                            <button class="btn btn-primary" type="submit" style="width: 185px;"  name="bookingSearch">Search</button>
                        </div>
                    </div>
					<?php $this->endWidget(); ?>
                    <div>
                        <a class="btn btn-primary pull-right mt20 mr10" style="text-decoration:none;" href="/aaohome/vehicle/carverifydoclist">Pending approval for boost and cab verification</a></div>
                </div>
                <div class="docgrid">
                    <div class="col-xs-12">
                        <div class="row">
							<?php
							if (!empty($dataProvider))
							{
								$params									 = array_filter($_REQUEST);
								$dataProvider->getPagination()->params	 = $params;
								$dataProvider->getSort()->params		 = $params;

								$i		 = 0;
								$items	 = '<div class="">';

								foreach ($dataProvider->getData() as $cab)
								{
									$vhdId		 = $cab['vhd_id'];
									$vhdModel	 = VehicleDocs::model()->findByPk($vhdId);
									$vhcId		 = $vhdModel->vhd_vhc_id;
									if (in_array($cab['vhd_type'], [8, 9, 10, 11]))
									{
										$checkVerification	 = VehicleStats::model()->checkVerification($vhcId, $cab['vhd_type']);
										$checkVerifyBooking	 = VehicleStats::model()->getBookingIdByVhcId($vhcId, $cab['vhd_type']);
									}
									else
									{
										$checkVerification = '';
									}
									if ($i == 4)
									{
										$items	 .= '</div><div class="row1">';
										$i		 = 0;
									}
									$picfile = VehicleDocs::getDocPathById($vhdId);
									if ($vhdModel->vhd_s3_data == '')
									{
										$picfile = VehicleDocs::getDocPathById($vhdId) . "?v=" . time();
									}
									$pdfImage	 = "/images/pdf.jpg";
									$picid		 = $cab['vhd_id'];
									if (in_array($cab['vhd_type'], [8, 9, 10, 11]))
									{
										if ($checkVerification == 'Cab Verification')
										{
											$fileImage	 = '<img src="' . $picfile . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $docType[$cab['vhd_type']] . "','" . $checkVerification . " For " . $checkVerifyBooking . " Verify car  " . $cab['vhc_number'] . " in the image against the Car " . $cab['vhc_number'] . " for booking " . $checkVerifyBooking . " given in the panel, Approve if car " . $cab['vhc_number'] . " is matching" . "'" . " , " . $cab['vhd_type'] . ')">';
											$filePdf	 = '<img src="' . $pdfImage . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $docType[$cab['vhd_type']] . "','" . $checkVerification . " For " . $checkVerifyBooking . " Verify car " . $cab['vhc_number'] . " in the image against the Car " . $cab['vhc_number'] . " for booking " . $checkVerifyBooking . " given in the panel, Approve if car " . $cab['vhc_number'] . " is matching" . "'" . " , " . $cab['vhd_type'] . ')">';
											$title		 = "Verify that pictures are matching cab number " . $cab['vhc_number'] . " ";
										}
										else
										{
											$title		 = " Verify that pictures are matching cab number  " . $cab['vhc_number'] . " Also verify that car has all the boost related stickers. ";
											$fileImage	 = '<img src="' . $picfile . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $docType[$cab['vhd_type']] . "','" . " Verify car  " . $cab['vhc_number'] . " and Gozo Boost stickers in the image Approve if Boost sticker is found. " . "'" . " , " . $cab['vhd_type'] . ')">';
											$filePdf	 = '<img src="' . $pdfImage . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $docType[$cab['vhd_type']] . "','" . " Verify car " . $cab['vhc_number'] . " and Gozo Boost stickers in the image Approve if Boost sticker is found. " . "'" . " , " . $cab['vhd_type'] . ')">';
										}
									}
									else
									{
										$fileImage	 = '<img src="' . $picfile . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $docType[$cab['vhd_type']] . "','" . $cab['vhc_number'] . "'" . " , " . $cab['vhd_type'] . ')">';
										$filePdf	 = '<img src="' . $pdfImage . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $docType[$cab['vhd_type']] . "','" . $cab['vhc_number'] . "'" . " , " . $cab['vhd_type'] . ')">';
									}
									$filename	 = (pathinfo($picfile, PATHINFO_EXTENSION) == 'pdf') ? $filePdf : $fileImage;
									$items		 .= '<div class="col-xs-3 mt30"><div class="text-center">
										
                                Cab Number : <b><a class="font15x" id="openBoostPic" onclick="openBoostPic(' . "'$vhcId','$title'" . ')">' . $cab['vhc_number'] . '</a></b><br>
                                Document Type : <b>' . $docType[$cab['vhd_type']] . '</b><br>
								
								<b>' . $checkVerification . '</b><br>
                                 ' . $filename . '
                            </div></div>';
									$i++;
								}
								$items .= '</div>';
								$this->widget('booster.widgets.TbGridView', array(
									'responsiveTable'	 => true,
									'filter'			 => $model,
									'dataProvider'		 => $dataProvider,
									'id'				 => 'vehicleListGrid',
									'template'			 => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>$items</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
									'itemsCssClass'		 => 'table table-striped table-bordered mb0',
									'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
								));
							}
							?>

                        </div>
                    </div>
                </div></div>
        </div>
    </div>


</div>

<script type="text/javascript">
	function openpic(pid, doctype, vnum, type) {
		if (type == 8 || type == 9 || type == 10 || type == 11)
		{
			var title = vnum;
		} else
		{
			var title = doctype + " - " + vnum;
		}
		$.ajax({
			"type": "GET",
			"dataType": "html",
			"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/vehicle/showdocimg')) ?>",
			"data": {"vhdid": pid},

			success: function (data) {
				box1 = bootbox.dialog({
					message: data,
					className: "bootbox-xs",
					title: "<span class='text-center'>" + title + "</span>",
					size: "large",
					onEscape: function () {
						box1.modal('hide');
					}
				}).on('shown.bs.modal', function () {
					box1.removeAttr("tabindex");
				});
			}
		});
	}
	function openBoostPic(vhcId, title)
	{
		$.ajax({
			"type": "GET",
			"dataType": "text",
			"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/vehicle/showboostdocimg')) ?>",
			"data": {"vhcId": vhcId},
			success: function (data) {
				box1 = bootbox.dialog({
					message: data,
					className: "bootbox-xs",
					title: "<span class='text-center'>" + title + "</span>",
					size: "large",
					onEscape: function () {
						box1.modal('hide');
					}
				}).on('shown.bs.modal', function () {
					box1.removeAttr("tabindex");
				});
			}
		});
	}
	function refreshApprovalList() {
		box1.modal('hide');
		$('#vehicleListGrid').yiiGridView('update');
	}
</script>