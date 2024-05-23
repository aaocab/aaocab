<div class="row">
    <div class="col-xs-12">
		<?php
		$stateList	 = CHtml::listData(States::model()->findAll('stt_active = :act AND stt_country_id = :con order by stt_name', array(':act' => '1', ':con' => '99')), 'stt_id', 'stt_name');
		$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'sms-form', 'enableClientValidation' => true,
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
		/* @var $form TbActiveForm */
		?>
        <div class="well pb20">
			<? $cls		 = "col-xs-6 col-sm-4 col-md-3 col-lg-2"; ?>
			<div class="row">
            <div class="<?= $cls ?>"> 
				<?= $form->textFieldGroup($model, 'cty_name', array('widgetOptions' => ['htmlOptions' => []])) ?>
            </div>
            <div class="<?= $cls ?>"> 
                <div class="form-group">
                    <label class="control-label">State</label>
					<?php
					$dataState	 = VehicleTypes::model()->getJSON($stateList);
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'cty_state_id',
						'val'			 => $model->cty_state_id,
						'asDropDownList' => FALSE,
						'options'		 => array('data' => new CJavaScriptExpression($dataState), 'allowClear' => true),
						'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select State')
					));
					?>
                </div> </div>
			
				<div class="<?= $cls ?>"> 
					<?= $form->textFieldGroup($model, 'cty_county', array('widgetOptions' => ['htmlOptions' => []])) ?>
				</div>
				<div class="<?= $cls ?>"> 
					<?= $form->textFieldGroup($model, 'cty_city_desc', array('widgetOptions' => ['htmlOptions' => []])) ?>
				</div>
				<div class="<?= $cls ?>"> 
					<?= $form->textFieldGroup($model, 'cty_ncr', array('widgetOptions' => ['htmlOptions' => []])) ?>
				</div>
				<div class="<?= $cls ?> mt20"> 
					<?= $form->checkboxGroup($model, 'cty_is_airport', array('label' => 'Is Airport')) ?>
				</div>
			</div>
			<div class="row">
				<div class="<?= $cls ?> text-center mt20 pt5">
					<button class="btn btn-primary" type="submit" style="width: 185px;"  name="bookingSearch">Search</button>
				</div>
			</div>
        </div>


		<?php $this->endWidget(); ?>
    </div>


</div>
<div class="row">
    <div class="col-xs-12">
		<?php
		if (!empty($dataProvider))
		{
			$this->widget('booster.widgets.TbExtendedGridView', array(
				'id'				 => 'city-grid',
				'responsiveTable'	 => true,
				'dataProvider'		 => $dataProvider,
				'template'			 => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
				'itemsCssClass'		 => 'table table-striped table-bordered mb0',
				'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
				//     'ajaxType' => 'POST',
				'columns'			 => array(
					array('name'	 => 'cty_name', 'value'	 => function ($data) {
							$url	 = "https://maps.google.com/?q=";
							$ctyName = $data['cty_name'];
							$ctyName .= $data['cty_alias_name'] == '' ? '' : ' (' . $data['cty_alias_name'] . ')';
							echo $ctyName . " " . "<small class='text-warning'><a href='" . $url . "" . $data['cty_lat'] . "," . $data['cty_long'] . "' target='_blank' title='Map'><i class='fas fa-map-marker-alt font-24 color-blue mb30 pl10'></i></a></small>";
						},
						'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Name'),
					array('name'				 => 'stt_name', 'value'				 => '$data[stt_name]',
						'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'State'),
					array('name'				 => 'zon_name', 'value'				 => '$data[zon_name]',
						'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Home Zone'),
					array('name'				 => 'cty_county', 'value'				 => '$data[cty_county]',
						'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'County/District'),
					array('name'				 => 'cty_city_desc', 'value'				 => '$data[cty_city_desc]',
						'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-4 text-left'),
						'htmlOptions'		 => array('class' => 'text-left'),
						'header'			 => 'City Description'),
					array('name'				 => 'cty_ncr', 'value'				 => '$data[cty_ncr]',
						'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-left'),
						'header'			 => 'NCR'),
					array('name'				 => 'vht_makes', 'value'				 => '$data[vht_makes]',
						'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-left'),
						'header'			 => 'Excluded Cab Types'),
					array('name'				 => 'cty_radius', 'value'				 => '$data[cty_radius]',
						'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-left'),
						'header'			 => 'Radius'),
//					array('name'	 => 'ldr_data_filepath', 'filter' => FALSE, 'value'	 => function ($data) {
//					$url="https://maps.google.com/?q=";
//							echo "<br><small class='text-warning'><a href='" . $url . "" . $data['cty_lat'] .",". $data['cty_long'] . "' target='_blank'><i class='fas fa-file-csv font-24 color-green mb30 pl10'></i></a></small>";
//						}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => ''), 'header'			 => 'Map'),
					array(
						'header'			 => 'Action',
						'class'				 => 'CButtonColumn',
						'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
						'template'			 => '{edit}{addrule}{log}',
						'buttons'			 => array(
							'edit'			 => array(
								'url'		 => 'Yii::app()->createUrl("admin/city/add", array(\'ctyid\' => $data[cty_id]))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\city\edit_booking.png',
								'label'		 => '<i class="fa fa-edit"></i>',
								'options'	 => array('style' => '', 'class' => 'btn btn-xs ignoreJob p0', 'title' => 'Edit'),
							),
							'addrule'		 => array(
								'url'		 => 'Yii::app()->createUrl("admin/pricerule/areapricerule", array(\'aprtypeid\' => $data[cty_id], \'aprtype\' => 3))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\driver\show_details.png',
								'label'		 => '<i class="fa fa-type"></i>',
								'options'	 => array('style' => '', 'class' => 'btn btn-xs ignoreJob p0', 'title' => 'Add Type'),
							),
							'log'			 => array(
								'click'		 => 'function(){
                                                    $href = $(this).attr(\'href\');
                                                    jQuery.ajax({type: \'GET\',
                                                    url: $href,
                                                    success: function (data)
                                                    {

                                                        var box = bootbox.dialog({
                                                            message: data,
                                                            title: \'City Log\',
                                                            onEscape: function () {

                                                                // user pressed escape
                                                            }
                                                        });
                                                    }
                                                });
                                                    return false;
                                                    }',
								'url'		 => 'Yii::app()->createUrl("admin/city/showlog", array(\'ctyid\' => $data[cty_id]))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\rate_list\show_log.png',
								'label'		 => '<i class="fa fa-list"></i>',
								'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'class' => 'btn btn-xs conshowlog p0', 'title' => 'Show Log'),
							),
							'htmlOptions'	 => array('class' => 'center'),
						))
			)));
		}
		?>
    </div>
</div>

<script>
	function edit(obj)
	{
		var $drvid = $(obj).attr('drv_id');

		var href2 = '<?= Yii::app()->createUrl("admin/driver/add"); ?>';
		$.ajax({
			"url": href2,
			"type": "GET",
			"dataType": "json",
			"data": {"drvid": $drvid},
			"success": function (data) {
				alert(data);
			}
		});
	}



</script>