<style type="text/css">
    .picform div.row{
        background-color: #EEEEFE;
        padding-top:3px;
        padding-bottom: 3px
    }
    .modal-header{
        padding:10px;
    }
</style>
<?
/* @var $model Vehicles  */
$model		 = Vehicles::model()->resetScope()->findByPk($vhcId);
$vtypeList	 = VehicleTypes::model()->getParentVehicleTypes(2);
$vTypeData	 = VehicleTypes::model()->getJSON($vtypeList);
?>
<div class="panel">
    <div class="panel-body p0">
        <div class="col-xs-12 mt20">
            <div class="row">
                <div class="col-xs-12 col-md-7 pl0 text-center">
                    <div class="col-xs-12">
                        <div class="row">
							<?
							$ImagePath	 = BookingPayDocs::getDocPathById($bmodel->bpay_id);
							$filePdf	 = '<a href="' . $ImagePath . '"  target="_blank"> <img src="/images/pdf.jpg"  height="100%"><br>Click to see file</a>';
							$fileImage	 = '<a href="' . $ImagePath . '"  target="_blank" id="vhdimage"> <img src="' . $ImagePath . '"  width="100%" id="bpayImage"></a>';
							echo (pathinfo($ImagePath, PATHINFO_EXTENSION) == 'pdf') ? $filePdf : $fileImage;
							?>

                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-5 ">
					<?php
					$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'verify-car-form', 'enableClientValidation' => true,
						'clientOptions'			 => array(
							'validateOnSubmit'	 => false,
							'errorCssClass'		 => 'has-error',
							'afterValidate'		 => 'js:function(form,data,hasError){
				if(!hasError){

				}
			    }'
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
					/* @var $form TbActiveForm */
					?>                          
					<?= $form->hiddenField($bmodel, 'bpay_id') ?>
					<?= $form->hiddenField($bmodel, 'bpay_type') ?>
                    <div class="picform">
						<div class="row mb5">
							<div class="col-xs-5">Document Type : </div>
							<div class="col-xs-7 bold"><?= $bmodel->getDocTypeText() ?></div>
						</div>
						<div class="row mb5">
							<div class="col-xs-5">Code : </div>
							<div class="col-xs-7"><?= $model->vhc_code ?></div>
						</div>
						<div class="row mb5">
							<div class="col-xs-5">Number : </div>
							<div class="col-xs-7">
								<?= $form->textFieldGroup($model, 'vhc_number', array('label' => false, 'widgetOptions' => array('htmlOptions' => ['placeholder' => 'Vehicle Number']))) ?>
							</div>
						</div>
						<?php  //if($model->vhc_type_id!=5){?>
							<div class="row mb5">
							<div class="col-xs-5">Cab Type: </div>
							<?php// $cabType = SvcClassVhcCat::model()->getVctSvcList('string', '', $model->vhcType->vht_VcvCatVhcType->vcv_vct_id)?>
							<?php 
                                                        $cabTypeArr = VehicleCategory::model()->getTypeClassbyid($model->vhc_id);
                                                        $cabType=$cabTypeArr['vct_label'].' ('.$cabTypeArr['scc_label'].')';
                                                        ?>
                                                        <div class="col-xs-7"><?= (($model->vhcType && $model) ? $cabType : '');?></div>
						</div>
					   <?php //} ?>
						<div class="row mb5">
							<div class="col-xs-5">Car Model :</div>
							<div class="col-xs-7">
								<?php
								$this->widget('booster.widgets.TbSelect2', array(
									'model'			 => $model,
									'attribute'		 => 'vhc_type_id',
									'val'			 => $model->vhc_type_id,
									'asDropDownList' => FALSE,
									'options'		 => array('data' => new CJavaScriptExpression($vTypeData)),
									'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Type')
								));
								?>
							</div>
						</div>
						<div class="row mb5">
							<div class="col-xs-5">Manufacture Year : </div>
							<div class="col-xs-7">
								<?= $form->numberFieldGroup($model, 'vhc_year', array('label' => false, 'widgetOptions'	 => array('htmlOptions' => array('min' => date('Y') - 25, 'max' => date('Y')))));?>
							</div>
						</div>
						<div class="row mb5">
							<div class="col-xs-5">Color : </div>
							<div class="col-xs-7">
								<?= $form->textFieldGroup($model, 'vhc_color', array('label' => false, 'widgetOptions' => array())) ?>
							</div>
						</div>
						<div class="row mb5">
							<div class="col-xs-5">Seating Capacity : </div>
							<div class="col-xs-7"><?= $model->vhcType->vht_capacity; ?></div>
						</div>
						<div class="row">
							<div class="col-xs-5">Luggage Capacity : </div>
							<div class="col-xs-7"><?= $model->vhcType->vht_bag_capacity; ?> </div>
						</div>
					</div>
		            <div class="row">
                    </div> 
					<?php $this->endWidget(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    
</script>
