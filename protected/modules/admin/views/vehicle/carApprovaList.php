<style type="text/css">
    .pic{
        max-width: 100%;
        max-height: 175px;

    }
</style>

<?php


$docType = VehicleDocs::model()->doctypeTxt;
//print_r($docType);exit;
?>
<div id="list-content">
    <div class="row" >
        <div class="panel">
           <div class="panel-body">
                <div class="col-xs-12">
                    <?php
                    $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                        'id' => 'vehicletype-form', 'enableClientValidation' => true,
                        'clientOptions' => array(
                            'validateOnSubmit' => true,
                            'errorCssClass' => 'has-error'
                        ),
                        // Please note: When you enable ajax validation, make sure the corresponding
                        // controller action is handling ajax validation correctly.
                        // See class documentation of CActiveForm for details on this,
                        // you need to use the performAjaxValidation()-method described there.
                        'enableAjaxValidation' => false,
                        'errorMessageCssClass' => 'help-block',
                        'htmlOptions' => array(
                            'class' => '',
                        ),
                    ));
                    ?>
                    <div class="row">

                        <div class="col-xs-6 col-sm-3">
                            <?= $form->textFieldGroup($model, 'vhcnumber', array('label' => 'Vehicle Number', 'widgetOptions' => array())) ?>
                        </div>
                        <div class="col-xs-6 col-sm-3">
                            <label class="control-label">Verification Type </label>
                            
                            <?php
                          
                            
                            $this->widget('booster.widgets.TbSelect2', array(
                                'model' => $model,
                                'attribute' => 'vhd_type',
                                'val' => $model->vhd_type,
                                
                                'data' => $dropType,
                                'options' => ['allowClear' => true],
                                'htmlOptions' => array('style' => 'width:100%', 'placeholder' => 'Select Document Type')
                            ));
                            ?>


                        </div>
                        <div class="col-xs-12 col-sm-3 text-center mt20 pt5">
                            <button class="btn btn-primary" type="submit" style="width: 185px;"  name="bookingSearch">Search</button>
                        </div>
                    </div>
                    <?php $this->endWidget(); ?>
                </div>
                <div class="docgrid">
                    <div class="col-xs-12">
                        <div class="row">
                            <?php		
                      //     echo "+++++++++++++++".$dataProvider->getPagination()->params['page']."+++++++++++";
                           $page =$dataProvider->getPagination()->params['page'] | 1;
                            if (!empty($dataProvider)) {
                                $params = array_filter($_REQUEST);
                                $type =$params['VehicleDocs']['vhd_type'];
                                if($type==2)
                                {
                                    echo'<div class="panel-heading">Pending Boost Verification List</div>';
                                }
                                if($type==1)
                                {
                                    echo'<div class="panel-heading">Pending Car Verification List</div>';
                                }
                                $dataProvider->getPagination()->params = $params;
                                $dataProvider->getSort()->params = $params;

                                $i = 0;
                                $items = '<div class="">';

                                foreach ($dataProvider->getData() as $cab) {
									if(!empty($cab['bpay_id'])) 
									{
										$vhcId = $cab['vhc_id'];
									    $bpayBkgId = $cab['bpay_bkg_id'];
										if (in_array($cab['bpay_type'], [8, 9, 10, 11])) {
											$checkVerification = VehicleStats::model()->checkVerification($vhcId, $cab['bpay_type']);
										} else {
											$checkVerification = '';
										}
										if ($i == 4) {
											$items .= '</div><div class="row1">';
											$i = 0;
										}
										//$picfile = $cab['bpay_image'];
										$picfile	 = BookingPayDocs::getDocPathById($cab['bpay_id']);
										$picid = $cab['bpay_id'];
										if (in_array($cab['bpay_type'], [8, 9, 10, 11])) 
										{
											if ($checkVerification == 'Cab Verification') {
												$title = "Verify that pictures are matching cab number " . $cab['vhc_number'] . " ";
												$fileImage = '<img src="' . $picfile . '" class="pic-bordered pic btn p0 pt10" onclick="openBoostPic(' . "'$bpayBkgId','$title','$page'" . ')">';
												
											} else {
												$title = "Verify that car has all the boost related stickers also verify that pictures are matching cab number  " . $cab['vhc_number'] . " ";
												$fileImage = '<img src="' . $picfile . '" class="pic-bordered pic btn p0 pt10" onclick="openBoostPic(' . "'$bpayBkgId','" . $title . "','" . " Verify car  " . $cab['vhc_number'] . " and Gozo Boost stickers in the image Approve if Boost sticker is found. " . "'" . " , " . $cab['bpay_type'] .  " , " . $vhcId .')">';
																							}
										} 										
										$filename = $fileImage;							
										$items .= '<div class="col-xs-3 mt30" id="'.$bpayBkgId.'update"><div class="text-center">
										
										Cab Number : <b><a class="font15x" id="openBoostPic" onclick="openBoostPic(' . "'$bpayBkgId','$title','$page'" . ')">' . $cab['vhc_number'] . '</a></b><br>
										Document Type : <b>' . $docType[$cab['bpay_type']] . '</b><br>
										<br>		
										 ' . $filename . '
										</div></div>';
											$i++;
									}
									else 
									{
										$vhdId	  = $cab['vhd_id'];
										$vhdModel = VehicleDocs::model()->findByPk($vhdId);
										$vhcId = $vhdModel->vhd_vhc_id;
										if ($i == 4)
										{
											$items	 .= '</div><div class="row1">';
											$i		 = 0;
										}
										//$picfile	 = $cab['vhd_file'];
                                                                                $picfile	 = VehicleDocs::getDocPathById($vhdId);
										$picid		 = $cab['vhd_id'];
										$title= " Verify that pictures are matching cab number  ". $cab['vhc_number'] . " Also verify that car has all the boost related stickers. ";
										$fileImage	 = '<img src="' . $picfile . '" class="pic-bordered pic btn p0 pt10" onclick="openBoostPic2(' . "'$vhcId','$title'".')">';
										$filename	 =  $fileImage;								
										$items		.= '<div class="col-xs-3 mt30"><div class="text-center">
										
										Cab Number : <b><a class="font15x" id="openBoostPic" onclick="openBoostPic2(' . "'$vhcId','$title'".')">' . $cab['vhc_number'] . '</a></b><br>
										Document Type : <b>' . $docType[$cab['vhd_type']] . '</b><br>
										<br>										
										 ' . $filename . '
										</div></div>';
											$i++;
									}									
                                }
                                $items .= '</div>';
                                $this->widget('booster.widgets.TbGridView', array(
                                    'responsiveTable' => true,
                                    'filter' => $model,
                                    'dataProvider' => $dataProvider,
                                    'id' => 'vehicleAndBoostListGrid',
                                    'template' => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>$items</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
                                    'itemsCssClass' => 'table table-striped table-bordered mb0',
                                    'htmlOptions' => array('class' => 'table-responsive panel panel-primary  compact'),
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
    function openpic(pid, doctype, vnum, type, vhcid) {
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
            "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/vehicle/showcarimg')) ?>",
            "data": {"bpayId": pid, "vhcId": vhcid},

            success: function (data) {
                box = bootbox.dialog({
                    message: data,
                    className: "bootbox-xs",
                    title: "<span class='text-center'>" + title + "</span>",
                    size: "large",
                    onEscape: function () {
                        box.modal('hide');
                    }
                }).on('shown.bs.modal', function () {
                    box.removeAttr("tabindex");
                });
            }
        });
    }
    function openBoostPic(bpayBkgId, title ,page)
    {
        $.ajax({
            "type": "GET",
            "dataType": "text",
            "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/vehicle/showallcarimg')) ?>",
            "data": {"bpayBkgId": bpayBkgId,"rootPage": page},
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
	function openBoostPic2(vhcId, title)
    {
		$.ajax({
			"type": "GET",
			"dataType": "text",
			"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/vehicle/showboostdocimg')) ?>",
			"data": {"vhcId": vhcId,"boost":1},
			success: function (data) {
				box1 = bootbox.dialog({
					message: data,
					className: "bootbox-xs",
					title: "<span class='text-center'>" + title +  "</span>",
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
        box.modal('hide');
        $('#vehicleAndBoostListGrid').yiiGridView('update');
        box1.modal('hide');
    }
</script>
