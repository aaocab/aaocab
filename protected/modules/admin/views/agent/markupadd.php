
<style type="text/css">
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
    }
    input[type="number"] {
        -moz-appearance: textfield;
    }
</style>

<?
//Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/lookup/cities?v' . Cities::model()->getLastModified());
$selectizeOptions = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
?>

<?
if ($model->scenario == 'update')
{
	?>
	<div class="col-xs-12">
		<div class="text-right mt40 n "> <a class="btn btn-info mt40 n" href="<?= Yii::app()->createUrl("admin/agent/markuplist") ?>">Go to list</a></div>

	</div>
	<?
}
?>
<div class="form">
    <div class="col-xs-12">

		<?php
		$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'price-surge-form-form',
			// Please note: When you enable ajax validation, make sure the corresponding
			// controller action is handling ajax validation correctly.
			// See class documentation of CActiveForm for details on this,
			// you need to use the performAjaxValidation()-method described there.
			'enableAjaxValidation'	 => false,
		));
		?>

		<?php echo $form->errorSummary($model); ?>
		<?php if (Yii::app()->user->hasFlash('success')): ?>
			<div class="col-xs-12 text-success text-center">
				<?php echo Yii::app()->user->getFlash('success'); ?>
			</div>
		<?php endif; ?> 
		<?
		if (Yii::app()->user->hasFlash('error'))
		{
			?>

			<div class="col-xs-12 alert-error text-center" style="color: #ff0000">
				<?php echo Yii::app()->user->getFlash('error'); ?>
			</div>
		<? } ?> 
		<div class="row">
			<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 form-group">
				<?
				$daterang	 = "Select Price Markup Date Range";
				$createdate1 = ($model->cpm_from_date == '') ? '' : $model->cpm_from_date;
				$createdate2 = ($model->cpm_to_date == '') ? '' : $model->cpm_to_date;

				if ($createdate1 != '' && $createdate2 != '')
				{
					$daterang = date('F d, Y', strtotime($createdate1)) . " - " . date('F d, Y', strtotime($createdate2));
				}
				?>
				<label  class="control-label">Price Markup Date Range</label>
				<div id="bkgMarkupDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
					<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
					<span><?= $daterang ?></span> <b class="caret"></b>
				</div>
				<?
				echo $form->hiddenField($model, 'cpm_from_date');
				echo $form->hiddenField($model, 'cpm_to_date');
				?>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3  ">
				<div class="form-group cityinput">
					<?php // echo $form->drop($model,'cpm_vehicle_type');    ?>
					<label>Channel Partner</label>
					<?php
					//$agtList	 = Agents::model()->getAgentList();
//	$this->widget('booster.widgets.TbSelect2', array(
//	    'model'		 => $model,
//	    'attribute'	 => 'cpm_agent_id',
//	    'val'		 => $model->cpm_agent_id,
//	    'data'		 => $agtList,
//	    'htmlOptions'	 => array('style'		 => 'width:100%', 'width'		 => '100%',
//		'placeholder'	 => 'Select Channel Partner')
//	));

					$this->widget('ext.yii-selectize.YiiSelectize', array(
						'model'				 => $model,
						'attribute'			 => 'cpm_agent_id',
						'useWithBootstrap'	 => true,
						"placeholder"		 => "Select Channel Partner",
						'fullWidth'			 => false,
						'htmlOptions'		 => array('width' => '100%'),
						'defaultOptions'	 => $selectizeOptions + array(
					'onInitialize'	 => "js:function(){
                                  populatePartner(this, '{$model->cpm_agent_id}');
                                }",
					'load'			 => "js:function(query, callback){
                                loadPartner(query, callback);
                                }",
					'render'		 => "js:{
                                option: function(item, escape){
                                return '<div><span class=\"\"><i class=\"fa fa-user mr5\"></i>' + escape(item.text) +'</span></div>';
                                },
                                option_create: function(data, escape){
                                return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
                                }
                                }",
						),
					));
					?>
					<?php echo $form->error($model, 'cpm_agent_id'); ?>
				</div>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
				<?php
				echo $form->dropDownListGroup($model, 'cpm_value_type', // radioButtonListGroup //dropDownListGroup
						['label' => 'Value Type', 'widgetOptions' => ['data' => $model->cpm_value_type_arr]]);
				?>

			</div>

			<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
				<?php echo $form->numberFieldGroup($model, 'cpm_value'); ?>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 form-group">
				<?php // echo $form->drop($model,'cpm_vehicle_type');      ?>
				<label>Car Type</label>
				<?php
				$returnType = "list";
				$vehicleList = SvcClassVhcCat::getVctSvcList($returnType);
				$this->widget('booster.widgets.TbSelect2', array(
					'model'			 => $model,
					'attribute'		 => 'cpm_vehicle_type',
					'val'			 => $model->cpm_vehicle_type,
					'options'		 => array('allowClear' => true),
					'data'			 => $vehicleList,
					'htmlOptions'	 => array('style' => 'width:100%', 'width' => '100%', 'placeholder' => 'Select Car Type')
				));
				?>
				<?php echo $form->error($model, 'cpm_vehicle_type'); ?>
			</div>

			<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 form-group">
				<?php // echo $form->drop($model,'cpm_vehicle_type');      ?>
				<label>Trip Type</label>
				<?php
				$tripType		 = Booking::model()->getBookingType();
				unset($tripType[2]);
				$this->widget('booster.widgets.TbSelect2', array(
					'model'			 => $model,
					'attribute'		 => 'cpm_trip_type',
					'val'			 => $model->cpm_trip_type,
					'options'		 => array('allowClear' => true),
					'data'			 => $tripType,
					'htmlOptions'	 => array('style' => 'width:100%', 'width' => '100%', 'placeholder' => 'Select Trip Type')
				));
				?>
				<?php echo $form->error($model, 'cpm_trip_type'); ?>
			</div>

			<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3  ">
				<div class="form-group ">
					<?php // echo $form->textFieldGroup($model,'cpm_source_zone');      ?>
					<label>Source Zone</label>
					<?php
					$loc			 = Zones::model()->getZoneList();
					$SubgroupArray	 = CHtml::listData(Zones::model()->getZoneList(), 'zon_id', function($loc) {
								return $loc->zon_name;
							});
					$this->widget('booster.widgets.TbSelect2', array(
						'attribute'			 => 'cpm_source_zone',
						'model'			 => $model,
						'data'			 => $SubgroupArray,
						'value'			 => $model->cpm_source_zone,
						'options'		 => array('allowClear' => true),
						'htmlOptions'	 => array(
							'placeholder'	 => 'Source Zone',
							'width'			 => '100%',
							'style'			 => 'width:100%',
						),
					));
					?>
					<?php echo $form->error($model, 'cpm_source_zone'); ?>
				</div></div>

			<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3  ">
				<div class="form-group  ">
					<?php // echo $form->textFieldGroup($model,'cpm_destination_zone');         ?>
					<label>Destination Zone</label>
					<?php
					$this->widget('booster.widgets.TbSelect2', array(
						'attribute'			 => 'cpm_destination_zone',
						'model'			 => $model,
						'data'			 => $SubgroupArray,
						'value'			 => $model->cpm_destination_zone,
						'options'		 => array('allowClear' => true),
						'htmlOptions'	 => array(
							'placeholder'	 => 'Destination Zone',
							'width'			 => '100%',
							'style'			 => 'width:100%',
						),
					));
					?>
					<?php echo $form->error($model, 'cpm_destination_zone'); ?>
				</div>
			</div>

		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3  ">
				<div class="form-group cityinput">
					<label>Source City</label>
					<?php
//        $this->widget('booster.widgets.TbSelect2', array(
//            'model' => $model,
//            'attribute' => 'cpm_source_city',
//            'val' => $model->cpm_source_city,
//            'asDropDownList' => FALSE,
//            // 'data' => $cityList2,
//            'options' => array('data' => new CJavaScriptExpression('$cityList')),
//            'htmlOptions' => array('style' => 'width:100%', 'placeholder' => 'Select Source City')
//        ));
					$this->widget('ext.yii-selectize.YiiSelectize', array(
						'model'				 => $model,
						'attribute'			 => 'cpm_source_city',
						'useWithBootstrap'	 => true,
						"placeholder"		 => "Select Source City",
						'fullWidth'			 => false,
						'htmlOptions'		 => array('width' => '100%',
						//  'id' => 'from_city_id1'
						),
						'defaultOptions'	 => $selectizeOptions + array(
					'onInitialize'	 => "js:function(){
		populateSourceCity(this, '{$model->cpm_source_city}');
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
						),
					));
					?>
					<?php echo $form->error($model, 'cpm_source_city'); ?>
				</div>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
				<div class="form-group cityinput">
					<label>Destination City</label>
					<?php
//	$this->widget('booster.widgets.TbSelect2', array(
//	    'model'		 => $model,
//	    'attribute'	 => 'cpm_destination_city',
//	    'val'		 => $model->cpm_destination_city,
//	    'asDropDownList' => FALSE,
//	    // 'data' => $cityList2,
//	    'options'	 => array('data' => new CJavaScriptExpression('$cityList')),
//	    'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Destination City')
//	));
					$this->widget('ext.yii-selectize.YiiSelectize', array(
						'model'				 => $model,
						'attribute'			 => 'cpm_destination_city',
						'useWithBootstrap'	 => true,
						"placeholder"		 => "Select Destination City",
						'fullWidth'			 => false,
						'htmlOptions'		 => array('width' => '100%',
						//  'id' => 'from_city_id1'
						),
						'defaultOptions'	 => $selectizeOptions + array(
					'onInitialize'	 => "js:function(){
			    populateSourceCity(this, '{$model->cpm_destination_city}');
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
						),
					));
					?>
					<?php echo $form->error($model, 'cpm_destination_city'); ?>
				</div>
			</div>

			<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3  ">
				<?php echo $form->textAreaGroup($model, 'cpm_desc'); ?>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mt10">
				<?php echo $form->checkboxGroup($model, 'cpm_apply_surge'); ?>
			</div>
		</div>
		<div class="col-xs-12 text-center">
			<?php echo CHtml::submitButton('Save', ['class' => 'btn btn-info  ']); ?>
		</div>
		<?php $this->endWidget(); ?>
    </div>
</div><!-- form -->
<script type="text/javascript">
    var start = '<?= date('d/m/Y', strtotime('-1 month')); ?>';
    var end = '<?= date('d/m/Y'); ?>';

    $('#bkgMarkupDate').daterangepicker(
            {
                locale: {
                    format: 'DD/MM/YYYY',
                    cancelLabel: 'Clear'
                },
                "showDropdowns": true,
                "alwaysShowCalendars": true,
                startDate: start,
                endDate: end,
                ranges: {
                    'Today': [moment(), moment()],
                    'Tomorrow': [moment().add(1, 'days'), moment().add(1, 'days')],
                    'Next 7 Days': [moment(), moment().add(6, 'days')],
                    'Next 30 Days': [moment(), moment().add(29, 'days')],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Next Month': [moment().add(1, 'month').startOf('month'), moment().add(1, 'month').endOf('month')],

//                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
//                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
//                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
//                    'This Month': [moment().startOf('month'), moment().endOf('month')],
//                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                }
            }, function (start1, end1) {

        $('#ChannelPartnerMarkup_cpm_from_date').val(start1.format('YYYY-MM-DD'));
        $('#ChannelPartnerMarkup_cpm_to_date').val(end1.format('YYYY-MM-DD'));
        $('#bkgMarkupDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
    });
    $('#bkgMarkupDate').on('cancel.daterangepicker', function (ev, picker) {
        $('#bkgMarkupDate span').html('Select Price Markup Date Range');
        $('#ChannelPartnerMarkup_cpm_from_date').val('');
        $('#ChannelPartnerMarkup_cpm_to_date').val('');
    });

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
    $('form').on('blur', 'input[type=number]', function (e) {
        $(this).off('mousewheel.disableScroll');
        $(this).off('keydown');
    });


</script>