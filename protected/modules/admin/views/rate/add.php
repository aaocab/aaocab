<?php
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/jquery.mask.min.js');
Yii::app()->clientScript->registerCssFile(ASSETS_URL . '/plugins/form-select2/select2.css');

$routeList	 = Route::model()->getRouteList();
$vehicleList = VehicleTypes::model()->getVehicleTypeList();
$stateList	 = array("" => "Select state") + CHtml::listData(States::model()->findAll('stt_active = :act AND stt_country_id = :con order by stt_name', array(':act' => '1', ':con' => '99')), 'stt_id', 'stt_name');
if (Yii::app()->request->getParam('rteid') != "")
{
	$readonly = true;
}
else
{
	$readonly = false;
}
?>
<div class="row">
    <div class="col-lg-4 col-md-6 col-sm-8 pb10" style="float: none; margin: auto">
        <div style="text-align:center;" class="col-xs-12">

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
					$form	 = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'rate-form', 'enableClientValidation' => true,
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
							'class'		 => 'form-horizontal', 'enctype'	 => 'multipart/form-data'
						),
					));
					/* @var $form TbActiveForm */
					?>
                    <div class="panel panel-default">
                        <div class="panel-body">
							<?php echo CHtml::errorSummary($model); ?>
							<? //= $form->dropDownListGroup($model, 'rte_route_id', array('label' => '', 'widgetOptions' => array('data' => $routeList))) ?>

                            <div class="form-group">
                                <div class="input-group">
                                    <label class="control-label">Route</label>
									<?php
									$data	 = Lookup::model()->getJSONRoutes($routeList);

									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $model,
										'attribute'		 => 'rte_route_id',
										'val'			 => $model->rte_route_id,
										'asDropDownList' => FALSE,
										'options'		 => array('data' => new CJavaScriptExpression($data)),
										'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Route', 'readonly' => $readonly)
									));
									?>
                                    <div class="input-group-btn" style="padding-top: 25px;">
                                        <a id="addNewRoute" class="btn btn-primary btn-label"><i class="fa fa-plus"></i></a>
                                    </div>
                                    <span class="has-error"><? echo $form->error($model, 'rte_route_id'); ?></span>
                                </div>
                            </div>
							<?php
//                            if ($model->rte_vehicletype_id) {
//                                $vehId = $model->rte_vehicletype_id;
//                                $vName = $vehicleList[$vehId];
//                            } else {
//                                $vName = "Select a vehicle";
//                            }
							?>

							<? //= $form->dropDownListGroup($model, 'rte_vehicletype_id', array('label' => '', 'widgetOptions' => array('data' => array($vehId => $vName)))) ?>



                            <div class="form-group">
                                <label class="control-label">Vehicle</label>
								<?php
								$this->widget('booster.widgets.TbSelect2', array(
									'model'			 => $model,
									'attribute'		 => 'rte_vehicletype_id',
									'val'			 => $model->rte_vehicletype_id,
									'asDropDownList' => FALSE,
									'options'		 => array('data' => new CJavaScriptExpression('[]')),
									'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Vehicle', 'readonly' => $readonly)
								));
								?>
                                <span class="has-error"><? echo $form->error($model, 'rte_vehicletype_id'); ?></span>
                                <input type="hidden" id="vehicleTypeId" value="<?= $model->rte_vehicletype_id ?>">
                            </div>
							<?= $form->textFieldGroup($model, 'rte_excl_amount', array('label' => 'Amount (Excluding Service Tax)')) ?>
							<?= $form->textFieldGroup($model, 'rte_amount', array('label' => 'Amount')) ?>
							<?php ?>
                            <input type="checkbox" name="returncheck" id="returncheck"  value="1">
                            Check this to update fares for return route also.
							<?php // }      ?>

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

        var availableTags = [];
        var front_end_height = $(window).height();
        var footer_height = $(".footer").height();
        var header_height = $(".header").height();
        var id = $('#vehicleTypeId').val();
        if (id != '')
        {
            getVehicleTypes1();
        }
        $("#Rate_rte_route_id").change(function () {
            getVehicleTypes();
        });
    });

    function getVehicleTypes()
    {
        var rtid = $("#Rate_rte_route_id").val();
        var href2 = '<?= Yii::app()->createUrl("admin/rate/availablevehicles"); ?>';
        $.ajax({
            "url": href2,
            "type": "GET",
            "dataType": "json",
            "data": {"rutid": rtid},
            "success": function (data1) {
                $data2 = data1;
                var placeholder = $('#<?= CHtml::activeId($model, "rte_vehicletype_id") ?>').attr('placeholder');
                $('#<?= CHtml::activeId($model, "rte_vehicletype_id") ?>').select2({data: $data2, placeholder: placeholder});
            }
        });
    }

    function getVehicleTypes1()
    {
        var rtid = $("#Rate_rte_route_id").val();
        var href2 = '<?= Yii::app()->createUrl("admin/rate/availablevehicles"); ?>';
        $.ajax({
            "url": href2,
            "type": "GET",
            "dataType": "json",
            "data": {"rutid": 0},
            "success": function (data1) {
                $data2 = data1;
                var placeholder = $('#<?= CHtml::activeId($model, "rte_vehicletype_id") ?>').attr('placeholder');

                $('#<?= CHtml::activeId($model, "rte_vehicletype_id") ?>').select2({data: $data2, placeholder: placeholder});

                $('#<?= CHtml::activeId($model, "rte_vehicletype_id") ?>').select2('val', $('#vehicleTypeId').val());
            }
        });
    }
    var routeBox;
    $('#addNewRoute').click(function () {
        $href = '<?= Yii::app()->createUrl('admin/route/add', ['callback' => 'refreshRoute']) ?>';
        jQuery.ajax({type: 'GET', url: $href,
            success: function (data) {
                routeBox = bootbox.dialog({
                    message: data,
                    title: 'Add Route',
                    onEscape: function () {
                        // user pressed escape
                    },
                });
                routeBox.on('hidden.bs.modal', function (e) {
                    $('body').addClass('modal-open');
                });
            }});
    });

    var refreshRoute = function () {
        var routeId = $rut_id;
        routeBox.hide();
        if (routeId != '')
        {
            $href = '<?= Yii::app()->createUrl('admin/route/json') ?>';
            jQuery.ajax({type: 'POST', "dataType": "json", url: $href,
                success: function (data1) {
                    $data = data1;
                    $('#<?= CHtml::activeId($model, "rte_route_id") ?>').select2({data: $data, multiple: false});
                    $('#<?= CHtml::activeId($model, "rte_route_id") ?>').select2('val', routeId);
                    getVehicleTypes();
                }
            });
        }
    };


</script>
