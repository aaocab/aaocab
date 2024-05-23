<?php
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/jquery.mask.min.js');
Yii::app()->clientScript->registerCssFile(ASSETS_URL . '/plugins/form-select2/select2.css');
$cityList = CHtml::listData(Cities::model()->findAll(array('order' => 'cty_name', 'condition' => 'cty_active=:act', 'params' => array(':act' => '1'))), 'cty_id', 'cty_name');

$stateList = array("" => "Select State") + CHtml::listData(States::model()->findAll('stt_active = :act AND stt_country_id = :con order by stt_name', array(':act' => '1', ':con' => '99')), 'stt_id', 'stt_name');
?>
<div class="row">
    <div class="col-lg-4 col-md-6 col-sm-8 pb10" style="float: none; margin: auto">
        <div style="text-align:center;" class="col-xs-12">
            <h3>Add a new driver</h3>
			<?php
			if ($status == "emlext")
			{
				echo "<span style='color:#ff0000;'>This email address is already registered. Please try again using a new email address.</span>";
			}
			elseif ($status == "added")
			{
				echo "<span style='color:#00aa00;'>Driver added successfully.</span>";
			}
			else
			{
				//do nothing
			}
			?>
        </div>
        <div class="row">
            <div class="upsignwidt">
                <div class="col-xs-12">
					<?php
					$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'driver-register-form', 'enableClientValidation' => true,
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
                    <div class="panel panel-default">
                        <div class="panel-body">
							<?php echo CHtml::errorSummary($model); ?>
							<?= $form->textFieldGroup($model, 'drv_name', array('label' => '')) ?>
							<?= $form->emailFieldGroup($model, 'drv_email', array('label' => '')) ?>
							<?= $form->textFieldGroup($model, 'drv_phone', array('label' => '')) ?>

							<?
							if ($model->drv_doj)
							{
								$model->drv_doj = DateTimeFormat::DateToLocale($model->drv_doj);
							}
							echo $form->datePickerGroup($model, 'drv_doj', array('label'			 => '',
								'widgetOptions'	 => array('options' => array('autoclose' => true, 'endDate' => '+1d', 'format' => 'dd/mm/yyyy'))
							));
							?>



							<?= $form->textFieldGroup($model, 'drv_lic_number', array('label' => '')) ?>
							<?= $form->textFieldGroup($model, 'drv_issue_auth', array('label' => '')) ?>
							<?php
							if ($model->drv_lic_exp_date)
							{
								$model->drv_lic_exp_date = DateTimeFormat::DateToLocale($model->drv_lic_exp_date);
							}
							echo $form->datePickerGroup($model, 'drv_lic_exp_date', array('label'			 => '',
								'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => '+1d', 'format' => 'dd/mm/yyyy'))
							));
							?>

							<?= $form->textAreaGroup($model, 'drv_address', array('label' => '')) ?>

							<?= $form->dropDownListGroup($model, 'drv_state', array('label' => '', 'widgetOptions' => array('data' => $stateList))) ?>

							<?php
							if ($model->drv_city)
							{
								$cityId		 = $model->drv_city;
								$cityName	 = $cityList[$cityId];
							}
							else
							{
								$cityName = "Select City";
							}
							?>
                            <div id="cityDiv" >
								<?= $form->dropDownListGroup($model, 'drv_city', array('label' => '', 'widgetOptions' => array('data' => array($cityId => $cityName)))) ?>
                            </div>

                            <div style="margin-left: 20px;margin-top: -10px" >

								<?= $form->checkboxListGroup($model, 'chk', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Background checked')))) ?>
                            </div>


                            <div class="panel-footer" style="text-align: center">
								<?php echo CHtml::submitButton('Add', array('class' => 'btn btn-primary')); ?>
                            </div>
                        </div>
                    </div><?php $this->endWidget(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo CHtml::endForm(); ?>
<script type="text/javascript">
    $(document).ready(function () {
        $('#Drivers_drv_phone').mask('9999999999');
        var availableTags = [];
        var front_end_height = $(window).height();
        var footer_height = $(".footer").height();
        var header_height = $(".header").height();
        $("#Drivers_drv_state").change(function () {

            var stid = $("#Drivers_drv_state").val();
            $("#Drivers_drv_city").text('').attr('value', '');

            var href2 = '<?= Yii::app()->createUrl("admin/driver/cityfromstate"); ?>';
            $.ajax({
                "url": href2,
                "type": "GET",
                "dataType": "json",
                "data": {"id": stid},
                "success": function (data) {
                    $("#cityDiv").show();
                    {
                        $('#Drivers_drv_city').append($('<option>').text("Select City").attr('value', ''));
                        $.each(data.citylist, function (key, value) {
                            $('#Drivers_drv_city').append($('<option>').text(value).attr('value', key));
                        });
                    }
                }
            });
        });
    });
    function validateCheckHandlerss() {
        if ($("#formId").validation({errorClass: 'validationErr'})) {

            if ($('#drv_email').val() != "") {
                var pattern = /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/
                var retVal = pattern.test($('#drv_email').val());
                if (retVal == false)
                {
                    $('#errId').html("The email address you have entered is invalid.");
                    return false;
                }
            }
            return true;
        } else
        {
            return false;
        }
    }



</script>
