<?php
//Yii::app()->clientScript->registerCssFile(ASSETS_URL . '/plugins/form-select2/select2.css');
$selectizeOptions = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
?>
<style type="text/css">
	.selectize-input {
        /*min-width: 0px !important;*/
    }
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
    }
    input[type="number"] {
        -moz-appearance: textfield;
    } 
    .form-horizontal .form-group{ margin-left: 0; margin-right: 0;}
</style>
<?
if ($success)
{
	?>
	<div class="col-lg-6 col-md-8 col-sm-12 col-sm-12 alert alert-success text-center " style="float: none; margin: auto">
		Added successfully<br>
		<a class="btn btn-warning m20 " href="<?= Yii::app()->createUrl('admin/quoteRequest/create') ?>">Add new</a>
		<a class="btn btn-facebook m20" href="<?= Yii::app()->createUrl('admin/quoteRequest/list') ?>">Go to list</a>
	</div>
	<?
}
else
{


	$isAjaxRequest	 = Yii::app()->request->isAjaxRequest;
	$css			 = ($isAjaxRequest) ? '' : 'col-lg-6 col-md-8 ';
	?>
	<div class=" <?= $css ?>  col-sm-12 pt10" style="float: none; margin: auto">
		<div class="panel panel-border">
			<div class="panel panel-heading pb10 pt10"><div class="row">
					<span class=" col-xs-6 pt10">Request Quotation </span>
					<span class=" text-right col-xs-6 pb5"> <a class="btn btn-info " href="<?= Yii::app()->createUrl('admin/quoteRequest/list') ?>">View Request Quote List</a>
					</span></div></div>
			<?php
			$form			 = $this->beginWidget('booster.widgets.TbActiveForm', array(
				'id'					 => 'quote-form', 'enableClientValidation' => true,
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
			?>
			<?= $form->hiddenField($model, 'cqt_id', ['value' => $model->cqt_id]) ?>
			<div class="panel-body">

				<?php echo $form->errorSummary($model); ?>
				<div class="row">
					<div class="col-xs-12 col-sm-6 col-md-6 ">
						<label>Journey Date</label>
						<?
						$minDate		 = ($model->cqt_pickup_date == '') ? date('Y-m-d 06:00:00', strtotime('+1 day')) : $model->cqt_pickup_date;
						?>
						<?=
						$form->datePickerGroup($model, 'cqt_pickup_date_date', array('label'			 => '',
							'widgetOptions'	 => array('options'		 => array('autoclose'	 => true,
									'startDate'	 => $minDate,
									'format'	 => 'dd/mm/yyyy'),
								'htmlOptions'	 => array(
									'required'		 => true,
									'placeholder'	 => 'Pickup Date',
									'value'			 => DateTimeFormat::DateTimeToDatePicker($minDate),
									'class'			 => 'form-control border-radius')),
							'groupOptions'	 => ['class' => 'm0'],
							'prepend'		 => '<i class="fa fa-calendar"></i>'));
						?>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-6 ">
						<label>Journey Time</label>
						<?=
						$form->timePickerGroup($model, 'cqt_pickup_date_time', array('label'			 => '',
							'widgetOptions'	 => array('options'		 => array('autoclose' => true),
								'htmlOptions'	 => array('placeholder'	 => 'Pickup Time',
									'id'			 => 'CustomQuote_cqt_pickup_date_time',
									'value'			 => date('h:i A', strtotime($minDate))))));
						?>
					</div>
				</div>
				<div class="row">

					<div class="col-xs-12 col-sm-6  ">
						<div class="form-group">
							<label  class="control-label">From City</label>
							<?php
							$this->widget('ext.yii-selectize.YiiSelectize', array(
								'model'				 => $model,
								'attribute'			 => 'cqt_from_city',
								'useWithBootstrap'	 => true,
								"placeholder"		 => "Select Source City",
								'fullWidth'			 => false,
								'htmlOptions'		 => array('width'	 => '100%',
									'id'	 => 'cqt_from_city'
								),
								'defaultOptions'	 => $selectizeOptions + array(
							'onInitialize'	 => "js:function(){
                                            populateSourceCity(this, '{$model->cqt_from_city}');
                                                }",
							'load'			 => "js:function(query, callback){
                                            loadSourceCity(query, callback);
                                            }",
							'render'		 => "js:{
                                            option: function(item, escape){
                                            return '<div><span class=\"\"><i class=\"fa fa-map-marker mr5\"></i>' + escape(item.text) +'</span></div>';
                                            },
                                            option_create: function(data, escape){
                                            return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
                                            }
                                            }",
								)
							));
							?>
							<span class="has-error"><? echo $form->error($model, 'cqt_from_city'); ?></span>
						</div>
					</div>
					<div class="col-xs-12 col-sm-6">
						<div class="form-group">
							<label class="control-label" for="exampleInputCompany6">Car Model</label>
							<?php
							$returnType		 = "list";
							$vehicleList	 = SvcClassVhcCat::getVctSvcList($returnType);
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'cqt_cab_type',
								'val'			 => $model->cqt_cab_type,
								'asDropDownList' => true,
								'data'			 => $vehicleList,
								'htmlOptions'	 => array('class' => 'form-control', 'style' => 'width:100%', 'placeholder' => 'Select Car Type')
							));
							?>
							<span class="has-error"><? echo $form->error($model, 'cqt_cab_type'); ?></span>
						</div>
					</div>
				</div>
				<div class="row">

					<div class="col-xs-12 col-sm-6  ">
						<?= $form->numberFieldGroup($model, 'cqt_no_of_days', array('label' => 'Total # days', 'widgetOptions' => array('htmlOptions' => ['placeholder' => 'Total # days', 'min' => 1, 'max' => 10]))) ?>

					</div> 
					<div class="col-xs-12 col-sm-6">
						<?= $form->dropDownListGroup($model, 'cqt_booking_type', array('label' => 'Booking Type', 'widgetOptions' => array('data' => $model->booking_type, 'htmlOptions' => ['placeholder' => 'Booking Type']))) ?>

					</div>
				</div>
				<div class="row">
					<div class="col-xs-12    ">
						<?=
						$form->textAreaGroup($model, 'cqt_description', array('label'			 => "Quotation Description",
							'widgetOptions'	 => array('htmlOptions' => array())))
						?>
					</div>

				</div>	
			</div>
			<div class="panel-footer text-center  border-bottom">
				<input class="btn btn-primary btn-block p10 pl50 pr50"  type="submit"  value="Register" />
			</div>

			<?php $this->endWidget(); ?>

		</div>
	</div>
<? } ?>
<script type="text/javascript">
	$('#CustomQuote_cqt_pickup_date_time').timepicker({'defaultTime': false, 'autoclose': true});
	$('form').on('focus', 'input[type=number]', function (e) {
		$(this).on('mousewheel.disableScroll', function (e) {
			e.preventDefault()
		})
		$(this).on("keydown", function (event) {
			if (event.keyCode === 38 || event.keyCode === 40) {
				event.preventDefault();
			}
		});
	});
	  $('.bootbox').removeAttr('tabindex');
	$('form').on('blur', 'input[type=number]', function (e) {
		$(this).off('mousewheel.disableScroll');
		$(this).off('keydown');
	});
</script>