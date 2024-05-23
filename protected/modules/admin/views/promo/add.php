<style>
    .checkbox-inline{
        padding-left: 0 !important;
    }
    .new-booking-list .form-horizontal .form-group{ margin-left: 0; margin-right: 0;}
    .new-booking-list label{ font-size: 11px;}
</style>
<?
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/plugins/form-typeahead/typeahead.bundle.min.js');
$jsrefresh	 = "
if($.isFunction(window.redirectList))
{
window.redirectList();
}
else
{
window.location.reload();
}
";
$datefrom	 = ($model->prm_valid_from != '') ? $model->prm_valid_from : 'now';
$dateto		 = ($model->prm_valid_upto != '') ? $model->prm_valid_upto : 'now';
?>

<div class="row">
    <div class="col-xs-12 col-md-7 col-lg-7  new-booking-list" style="float: none; margin: auto">
		<?
		if ($status != "")
		{
			?>
			<span style="color : green;margin-bottom: 10px;"><?= $status; ?></span>   
		<? } ?>
		<?php
		$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'promotion-form', 'enableClientValidation' => false,
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
        <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-default panel-border">
                    <div class="panel-body">
                        <h3 class="pb10 mt0">Add new Promotion</h3>
                        <div class="row mb15">
							<?= CHtml::errorSummary($model); ?> 
                            <div class="col-xs-12 col-sm-8">

                                Promo Description *
								<?= $form->textFieldGroup($model, 'prm_desc', array('label' => '')) ?>
                            </div>
                            <div class="col-xs-12 col-sm-4">
                                <label>Code *</label>
								<?= $form->textFieldGroup($model, 'prm_code', array('label' => '')) ?>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-xs-12 col-sm-4">
                                <label>Promo Type</label>
								<?= $form->radioButtonListGroup($model, 'prm_type', array('label' => '', 'widgetOptions' => array('htmlOptions' => [], 'data' => Promotions::$promoType), 'inline' => true)) ?>
                            </div>
                            <div class="col-xs-12 col-sm-4">
                                <label>Promo can be used how many times</label>
								<?= $form->numberFieldGroup($model, 'prm_use_max', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('min' => 0, 'placeholder' => 'How Many Times')))) ?>
                            </div>
                            <div class="col-xs-12 col-sm-4">
                                <label>Offer Value *</label>
								<?= $form->textFieldGroup($model, 'prm_value', array('label' => '')) ?>
                                <div id="error_promo_type" style="color:#da4455">

                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-xs-12 col-sm-12">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-4">
                                        <label>Promo Value Type</label> 
										<?= $form->radioButtonListGroup($model, 'prm_value_type', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['onchange' => 'promoValueType(this)'], 'data' => Promotions::$valueType), 'inline' => true)) ?>
                                        <input type="hidden" id="promo_valuetype" value="<?= $model->prm_value_type ?>">
                                    </div>

                                    <div class="col-xs-12 col-sm-4" id="promo_minimum" style="display:none">
                                        <label>Minimum Offer Amount</label>
										<?= $form->textFieldGroup($model, 'prm_min', array('label' => '', 'widgetOptions' => array('htmlOptions' => []))) ?>
                                    </div>
                                    <div class="col-xs-12 col-sm-4" id="promo_maximum" style="display:none">
                                        <label>Maximum Offer Amount</label>
										<?= $form->textFieldGroup($model, 'prm_max', array('label' => '', 'widgetOptions' => array('htmlOptions' => []))) ?>
                                        <div id="error_promo_type" style="color:#da4455">
                                        </div>
                                    </div>
									<div class="col-xs-12 col-sm-4">
                                        <label>Activate On</label> 
										<?= $form->radioButtonListGroup($model, 'prm_activate_on', array('label' => '', 'widgetOptions' => array('htmlOptions' => [], 'data' => Promotions::$activateOn), 'inline' => true)) ?>
                                    </div>
                                </div>

                                <div class="row mb15">
                                    <div class="col-xs-12 col-md-6"><label>Offer Valid From</label>
                                        <div class="row ">
                                            <div class="col-xs-12 col-sm-7 pr5">
												<?=
												$form->datePickerGroup($model, 'prm_valid_from_date', array('label'			 => '',
													'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('required' => true, 'value' => date('d/m/Y', strtotime($datefrom)))), 'prepend'		 => '<i class="fa fa-calendar"></i>'));
												?>
                                            </div>

                                            <div class="col-xs-12 col-sm-5 pl0">
												<?=
												$form->timePickerGroup($model, 'prm_valid_from_time', array('label'			 => '',
													'widgetOptions'	 => array('options' => array('autoclose' => true), 'htmlOptions' => array('required' => true, 'value' => date('h:i A', strtotime($datefrom))))));
												?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-md-6"><label>Offer Valid Upto</label>
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-7 pr5">
												<?=
												$form->datePickerGroup($model, 'prm_valid_upto_date', array('label'			 => '',
													'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('required' => true, 'value' => date('d/m/Y', strtotime($dateto)))), 'prepend'		 => '<i class="fa fa-calendar"></i>'));
												?>
                                            </div>
                                            <div class="col-xs-12 col-sm-5 pl0">
												<?=
												$form->timePickerGroup($model, 'prm_valid_upto_time', array('label'			 => '',
													'widgetOptions'	 => array('options' => array('autoclose' => true), 'htmlOptions' => array('required' => true, 'value' => date('h:i A', strtotime($dateto))))));
												?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">

                                    <div class="col-xs-12 col-sm-4">
                                        <label class="control-label">Source Type</label></br>
										<? //= $form->textFieldGroup($model, 'prm_source_type', array());?>
										<?=
										$form->checkboxListGroup($model, 'prm_source_type_show', array('label'			 => '',
											'widgetOptions'	 => array('data' => Promotions::$source_type), 'inline'		 => true, 'htmlOptions'	 => ['class' => 'p0']));
										?>
                                    </div>

                                    <div class="col-xs-12 col-sm-4">
                                        <label class="control-label">Applicable Type</label> 
										<?= $form->radioButtonListGroup($model, 'prm_applicable_type', array('label' => '', 'widgetOptions' => array('data' => Promotions::$applicableType), 'inline' => true)) ?>
                                    </div>
                                    <div class="col-xs-12 col-sm-4">
                                        <label class="control-label">  Applicable User Type</label> 
										<?= $form->radioButtonListGroup($model, 'prm_applicable_user_type', array('label' => '', 'widgetOptions' => array('data' => Promotions::$applicableUserType), 'htmlOptions' => ['class' => 'p0'], 'inline' => true)) ?>
                                    </div>
                                </div>
                                <div class="row mb15 mt10">
                                    <div class="col-xs-12 col-sm-4">
                                        <label>  Applicable Trip Type</label> 
										<?= $form->radioButtonListGroup($model, 'prm_applicable_trip_type', array('label' => '', 'widgetOptions' => array('data' => Promotions::$applicableTripType), 'inline' => true)) ?>
                                    </div>
                                    <div class="col-xs-12 col-sm-8">
                                        <label>Promo code discount will be given on next or current booking? </label> 
										<?= $form->radioButtonListGroup($model, 'prm_next_trip_apply', array('label' => '', 'widgetOptions' => array('data' => Promotions::$nextTrip), 'inline' => true)) ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 text-center pb10">
                                        <input type="submit" value="<?= $isNew ?>" name="yt0" class="btn btn-primary pl30 pr30">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
		<?php $this->endWidget(); ?>
    </div>
</div>
<script type="text/javascript">
    $(function () {
        $('.glyphicon').addClass('fa').removeClass('glyphicon');
        $('.glyphicon-time').addClass('fa-clock-o').removeClass('glyphicon-time');
        $('.glyphicon-chevron-up').addClass('fa-chevron-up').removeClass('glyphicon-chevron-up');
        var date = new Date();
        date.setDate(date.getDate() - 1);
        $('#Promotions_prm_valid_from_date').datepicker({
            format: 'dd/mm/yyyy',
            startDate: date
        });
        $('#Promotions_prm_valid_upto_date').datepicker({
            format: 'dd/mm/yyyy',
            startDate: date
        });
    });
    var checked = $('#<?= CHtml::activeId($model, 'prm_value_type') ?>').is(":checked");
    if (checked)
    {

    }
    function promoValueType(obj)
    {
        if (obj.value == 1) {
            $('#promo_valuetype').val(1);
            //$('#<?= CHtml::activeId($model, 'prm_min') ?>').show();
            //$('#<?= CHtml::activeId($model, 'prm_max') ?>').show();
            $("#promo_minimum").show();
            $("#promo_maximum").show();

        } else if (obj.value == 2) {
            $('#promo_valuetype').val(2);
            //$('#<?= CHtml::activeId($model, 'prm_min') ?>').hide();
            //$('#<?= CHtml::activeId($model, 'prm_max') ?>').hide();
            $("#promo_minimum").hide();
            $("#promo_maximum").hide();
        }
    }

    $('#promotion-form').submit(function (event) {
        error = false;
        $("#error_promo_type").text("");
        var promo_type = $('#promo_valuetype').val();
        var promo_min = $('#<?= CHtml::activeId($model, 'prm_min') ?>').val();
        var promo_max = $('#<?= CHtml::activeId($model, 'prm_max') ?>').val();
        var promo_value = $('#<?= CHtml::activeId($model, 'prm_value') ?>').val();
        if (promo_type != '' || promo_type != null)
        {
            if (promo_type == 1 && (promo_min == '' || promo_min == null))
            {
                error = true;
                $("#error_promo_type").text('Please enter promo maximum and minimum value.');
            }
            if (promo_type == 1 && (promo_max == '' || promo_max == null))
            {
                error = true;
                $("#error_promo_type").text('Please enter promo maximum and minimum value.');
            }

            if (promo_type == 2 && (promo_value == '' || promo_value == null))
            {
                error = true;
                $("#error_promo_type").text('Please enter promo value.');
            }
        }
        if (error)
        {
            event.preventDefault();
        }
    });
</script>

