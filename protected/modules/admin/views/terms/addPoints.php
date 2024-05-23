<style>
    .checkbox-inline{
        padding-left: 0 !important;
    }
    .new-booking-list .form-horizontal .form-group{ margin-left: 0; margin-right: 0;}
    .new-booking-list label{ font-size: 11px;}
	.usertype,
	.cash,
	.coin,
	.fixed{ 
		padding: 10px; 
		margin: 10px; 
		border: 1px solid silver; 
	}
</style>
<?php
$version		 = Yii::app()->params['siteJSVersion'];
//Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/promo.js?v=' . $version);
//Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/city.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/plugins/form-typeahead/typeahead.bundle.min.js');
?> 
<div class="row">
    <div class="col-xs-12 col-md-11 col-lg-11  new-booking-list" style="float: none; margin: auto">	
		<?php
		$form			 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'termPointsForm', 'enableClientValidation' => true,
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
				'class' => 'form-horizontal',
			),
		));
		/* @var $form TbActiveForm */
		?>
		<?php echo $form->errorSummary($model); ?>
		<div class="row">
            <div class="col-xs-12">
                <div class="panel panel-default panel-border">
                    <div class="panel-body">
						<div class="row mb15">
							<div class="col-xs-12 col-sm-4">
								<div class="vou_pro">
									<label>Type</label>
									<?php
									$typeList		 = TncPoints::getTypeJSON(true);
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $model,
										'attribute'		 => 'tnp_for',
										'val'			 => explode(',', $model->tnp_for),
										'data'			 => $typeList,
										'htmlOptions'	 => array(
											'multiple'		 => 'multiple',
											'placeholder'	 => 'Select Type',
											'width'			 => '100%',
											'style'			 => 'width:100%',
										),
									));
									?>
								</div>
							</div>
							<div class="col-xs-12 col-sm-4">
								<div class="vou_pro">
									<label>Position</label>
									<?= $form->textFieldGroup($model, 'tnp_position', array('label' => '')) ?>

								</div>
							</div>
							<div class="col-xs-12 col-sm-4">
								<div class="vou_pro">
									<label>Tier</label>
									<?php
									$tireList		 = ServiceClass::getTier();
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $model,
										'attribute'		 => 'tnp_tier',
										'val'			 => explode(',', $model->tnp_tier),
										'data'			 => $tireList,
										'htmlOptions'	 => array(
											'multiple'		 => 'multiple',
											'placeholder'	 => 'Select Tire',
											'width'			 => '100%',
											'style'			 => 'width:100%',
										),
									));
									?>
								</div>

							</div>
						</div>
						<div class="row mb15">
							<div class="col-xs-12 col-sm-4">
								<div class="vou_pro">
									<label>Booking Type</label>
									<?php
									$bookingTypeList = TncPoints::getBookingTypeJSON();
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $model,
										'attribute'		 => 'tnp_trip_type',
										'val'			 => explode(',', $model->tnp_trip_type),
										'data'			 => $bookingTypeList,
										'htmlOptions'	 => array(
											'multiple'		 => 'multiple',
											'placeholder'	 => 'Select Type',
											'width'			 => '100%',
											'style'			 => 'width:100%',
										),
									));
									?>
								</div>
							</div>
							<div class="col-xs-12 col-sm-4">
								<div class="vou_pro">
									<label>C Type</label>
									<?php
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $model,
										'attribute'		 => 'tnp_c_type',
										'val'			 => $model->tnp_c_type,
										'asDropDownList' => FALSE,
										'options'		 => array('data' => new CJavaScriptExpression(TncPoints::getCType())),
										'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Type')
									));
									?>
								</div>
							</div>
						</div>
                        <div class="row mb15">
							<div class="col-xs-12 col-sm-10">
								<label>Text</label>
								<?= $form->textAreaGroup($model, 'tnp_text', array('label' => '', 'rows' => 15, 'cols' => 100, 'placeholder' => 'Add Text')) ?>
							</div>
						</div>
						<!--  -->
						<div class="row">
							<div class="col-xs-12 text-center pb10">
								<input type="submit" value="Create Term Points" name="yt0" id="promosubmit" class="btn btn-primary pl30 pr30">
								<input type="button" value="Back" name="termsSubject" id="termsSubject" class="btn btn-primary pl30 pr30">
							</div>
						</div>				
                    </div>
                </div>
            </div>
        </div>
		<?php $this->endWidget(); ?>
    </div>
</div>

<script>
    $('#termsSubject').click(function () {

        window.location.href = '/admpnl/terms/listPoints';

    });
</script>