<div class="row">
    <div class="col-xs-12">
		<?php
		$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
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

		<?php $this->endWidget(); ?>
    </div>


</div>
<div class="row">
    <div class="col-xs-12">
		<?php
		if (!empty($dataProvider))
		{
			$this->widget('booster.widgets.TbGridView', array(
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
					array('name'	 => 'Cab Type',
						'type'	 => 'raw',
						'value'	 => function($data) 
						{
							$ct = SvcClassVhcCat::getVctSvcList("string", 0, 0, $data['apr_cab_type']);
							//$ct = VehicleTypes::model()->getCarType();
							//echo print_r($ct, true);
							echo $ct;
						},
						'sortable'			 => false,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Cab Type'),
					array('name'	 => 'triptype',
						'type'	 => 'raw',
						'value'	 => function($data) {
							$ct = AreaPriceRule::model()->areatype;
							echo $ct[$data['apr_area_type']];
						},
						'sortable'			 => false,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Area Type'),
					array('name'				 => 'apr_area_name',
						'value'				 => '$data[apr_area_name]',
						'sortable'			 => false,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Area Name'),
					array(
						'header'			 => 'Action',
						'class'				 => 'CButtonColumn',
						'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
						'template'			 => '{edit}',
						'buttons'			 => array(
							'edit'			 => array(
								'url'		 => 'Yii::app()->createUrl("admin/pricerule/areapricerule", array(\'aprid\' => $data[apr_id]))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\city\edit_booking.png',
								'label'		 => '<i class="fa fa-edit"></i>',
								'options'	 => array('style' => '', 'class' => 'btn btn-xs ignoreJob p0', 'title' => 'Edit'),
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