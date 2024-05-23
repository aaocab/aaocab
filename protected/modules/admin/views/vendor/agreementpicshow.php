<div class="panel">
    <div class="panel-body p0">
        <div class="col-xs-12">
            <div class="row">
                <div class="col-xs-12 col-md-7 pl0 text-center">
                    <div class="col-xs-12">
                        <div class="row rotateImg">
							<?php
							$flag=0;
							if ($docModel['vag_soft_flag']==0) {
								    $flag=1;	
							}
							//$Url=$docModel['vag_soft_path'];
                            $Url = VendorAgreement::getPathById($docModel['vag_id'], VendorAgreement::SOFT_PATH);
//							$spiltPath= explode("\attachments", $Url);
//							$ImagePath="/attachments".str_replace("\\",'/',$spiltPath[1]);
							$filePdf	 = '<a href="' . $Url . '"  target="_blank"> <img src="/images/pdf.jpg"  height="100%"><br>Click to see file</a>';
							$fileImage	 = '<img src="' . $Url . '"   width="100%" id="vdimage">';
						    $imageType=pathinfo($docModel->vag_soft_path, PATHINFO_EXTENSION);
						  	echo (pathinfo($docModel->vag_soft_path, PATHINFO_EXTENSION) == 'pdf') ? $filePdf : $fileImage;
							?> 
						</div>
                    </div>
					<?php if ($imageType != 'pdf' && $spiltPath[1]!="")	{?>
						<div class="col-xs-12 mt10">
							<div class="row">
								<a class="btn btn-primary" class ="rotate" id="rtleft" val="<?= $docModel->vag_id ?>">Rotate <i class="fa fa-rotate-270 fa-rotate-left"></i></a>
								<a class="btn btn-primary" class ="rotate" id="rtright" val="<?= $docModel->vag_id ?>">Rotate <i class="fa fa-rotate-90 fa-rotate-right "></i></a>
							</div>
						</div>
                    <?php } ?>
                </div>
                <div class="col-xs-12 col-md-5 ">
					<?php
					$form	 = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'verify-form',
						'enableClientValidation' => true,
						'clientOptions'			 => array(
							'validateOnSubmit'	 => false,
							'errorCssClass'		 => 'has-error',
							'afterValidate'		 => 'js:function(form,data,hasError){
                                        if(!hasError){                                        
                                        }
                                    }'
						),
						'enableAjaxValidation'	 => false,
						'errorMessageCssClass'	 => 'help-block',
						'htmlOptions'			 => array(
							'class' => '',
						),
					));
					/* @var $form TbActiveForm */
					?> 
					<?= $form->hiddenField($model, 'ctt_id') ?>
					<?= $form->hiddenField($docModel, 'vag_id') ?>
					<?= $form->hiddenField($docModel, 'vag_vnd_id') ?>
					
					<div class="row mb5">
                        <div class="col-xs-5">Document Name: </div>
                        <div class="col-xs-7 bold">Agreement File </div>
                    </div>                         
                    <div class="row mb5">
                        <div class="col-xs-5">Name : </div>
                     <div class="col-xs-7 bold"><?= $model->ctt_business_name!=""?  $model->ctt_business_name :  $model->ctt_first_name." ".$model->ctt_last_name?></div>
                    </div>                
					<?php  if ($model->ctt_address!= ''){	?>
						<div class="row mb5">
							<div class="col-xs-5">Address : </div>
							<div class="col-xs-7"><?= $model->ctt_address ?></div>
						</div>
                    <?php } ?>
                  <?php  if ($model->ctt_city != '') {?>
						<div class="row mb5">
							<div class="col-xs-5">City : </div>
							<div class="col-xs-7"><?php $cityDetails=Cities::model()->findByPk($model->ctt_city); echo $cityDetails->cty_name;  ?></div>
						</div>
                    <?php } 
					if ($model->ctt_state != '') {?>
						<div class="row mb5">
							<div class="col-xs-5">State : </div>
							<div class="col-xs-7"><?php $stateDetails=States::model()->findByPk($model->ctt_state); echo $stateDetails->stt_name;  ?></div>
						</div>
                    <?php } ?>
					<div class="row mb5">
									<div class="col-xs-5">Digital Agreement Date: </div>
									<div class="col-xs-7 bold"><?= $vag_digital_date = ($docModel->vag_digital_date) ? DateTimeFormat::DateTimeToDatePicker($docModel->vag_digital_date) : '' ?></div>
                    </div>
			        <div class="row mb5">
									<div class="col-xs-5">Soft Agreement Date: </div>
									<div class="col-xs-7 bold"><?=$vag_soft_date= ($docModel->vag_soft_date) ? DateTimeFormat::DateTimeToDatePicker($docModel->vag_soft_date) : '' ?></div>
                    </div>
				    <div class="row mb5">
							<div class="col-xs-5 ">Created Date: </div>
							<div class="col-xs-7"><?= date('d/m/Y', strtotime($model->ctt_created_date)); ?></div>
					</div>
					<div class="row mb5">
							<div class="col-xs-5 ">Status: </div>
							<?php
							 if($docModel->vag_active==1){
								         echo "<div class='col-xs-7 text-success'>Active</div>";
							  }
					        else{
								echo "<div class='col-xs-7 text-danger'>InActive</div>";
							}    ?>
					</div>
					<div class="row mb5">
							<div class="col-xs-5 ">Approved: </div>
							<?php
							 if($docModel->vag_soft_flag==1){
								         echo "<div class='col-xs-7 text-success'>Approved</div>";
							  }
							  else if($docModel->vag_soft_flag==2){
								   echo "<div class='col-xs-7 text-danger'>Rejected</div>";
							  }
					        else{
								 echo "<div class='col-xs-7 text-primary'>Waiting for Approval</div>";
							}    ?>
					</div>
					
					
					 <div class="row bg-gray pt10 mt10">
							<div class="col-xs-5 ">Agreement Date:</div>
							<div class="col-xs-7">								
								<?php
									 if ($docModel->vag_soft_date)	{										     
                                             $docModel->vag_soft_date = DateTimeFormat::DateTimeToDatePicker($docModel->vag_soft_date);
									    }
									?>							
								<?=	$form->datePickerGroup($docModel, 'vag_soft_date', array(
											   'label'=> '',
											   'widgetOptions'	 => array(              
												               'options' => array('autoclose'	 => true,
													            'format'=> 'dd/mm/yyyy'),'htmlOptions'	 => array(
											'readonly'=>true,
											'placeholder'	 => 'Agreement Date',
											'value'=>$docModel->vag_soft_date==""?date("d/m/Y"):$docModel->vag_soft_date,
											'class'			 => 'input-group border-gray full-width')),
									        'prepend'		 => '<i class="fa fa-calendar"></i>'));
										?>
							</div>
						</div> 
					
					<div class="row">
                        <div class="col-xs-12 bg-gray pt10">
                            <div class="row">
                                <div class="col-xs-12"> <?= $form->textAreaGroup($docModel, 'vag_remarks' ,array('label' =>"Remark", 'widgetOptions' => array('htmlOptions' => array('value' =>$docModel->vag_remarks,'placeholder'=>"Remark" ,'id'=>"vag_remarks"))))?></div>
                            </div>
                            <div class="row text-center mb5">
								<?php
							      if($flag==1){
								         echo '<a class="btn btn-primary btn-xs pl5 pr5" id="btnAppr" name="btnAppr">Approve</a> <a class="btn btn-danger btn-xs pl5 pr5" id="btnDspr" name="btnDspr">Disapprove</a>';
							       } ?>
							</div>
                        </div>
                    </div>
<?php $this->endWidget(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
			$('#btnAppr').click(function (e) {
				$.ajax({
					"type": "POST",
					"dataType": "json",
					"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/vendor/agreementapprovedoc', ['btntype' => 'approve'])) ?>",
					"data": $('#verify-form').serialize(),
					"success": function (data) {
								if (data.status) {
									bootbox.hideAll();
							   } else {
								   alert(JSON.stringify(data.message));
							   }                    
							}
					 });
				e.preventDefault();
				return false; 
			});
			$('#btnDspr').click(function (e) {
				remarks = $('#vag_remarks').val();
				if (remarks.trim() != '') {
					$.ajax({
						"type": "POST",
						"dataType": "json",
						"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/vendor/agreementapprovedoc', ['btntype' => 'problem'])) ?>",
					   "data": $('#verify-form').serialize(),
						"success": function (data) {
							   if (data.status) {
									bootbox.hideAll();
							   } else {
								  alert(JSON.stringify(data.message));
							   }
						   }
					});
				} else {
					$('#VendorAgreement_vag_remarks_em_').text('Remarks is required');
					$('#VendorAgreement_vag_remarks_em_').addClass('text-danger');
					$('#VendorAgreement_vag_remarks_em_').show();
				}
				 e.preventDefault();
				 return false;
			});
			$('#rtleft').click(function () {
				imgRotate('left');
			});
			$('#rtright').click(function () {
				imgRotate('right');
			});
	});
    function imgRotate(rttype) {
        picpath = '<?= $ImagePath ?>';
        $.ajax({
            "type": "GET",
            "dataType": "json",
            "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/index/imagerotate')) ?>",
            data: {'picpath': picpath, 'rttype': rttype},
            "success": function (data) {
                if (data.success) {
                    $(".rotateImg").html('<img src="' + data.imagefile + '"  width="100%">');
                }
            }
        });
    }	
</script>