
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
<div class="row">
    <a class="btn btn-primary mb10" href="<?= Yii::app()->createUrl('admin/vehicle/serviceclasstype') ?>" style="text-decoration: none;margin-right: 15px;float: right;">Add new</a>
    <div class="col-xs-12">

		<?php
		if (!empty($dataProvider))
		{
			$this->widget('booster.widgets.TbExtendedGridView', array(
				'id'				 => 'service_class_grid',
				'responsiveTable'	 => true,
				'dataProvider'		 => $dataProvider,
				'filter'			 => $model,
				'template'			 => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
				'itemsCssClass'		 => 'table table-striped table-borered mb0',
				'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
				//     'ajaxType' => 'POST',
				'columns'			 => array(
					array('name'				 => 'scc_label', 'value'=> '$data[scc_label]',
						'sortable'			 => true,
						'filter'			 => CHtml::activeTextField($model, 'scc_label', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->getAttributeLabel('scc_label'))),
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Lable'),
					array('name'				 => 'scc_desc', 'value'	=> '$data[scc_desc]',
						'sortable'			 => true,
						'filter'			 => CHtml::activeTextField($model, 'scc_desc', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->getAttributeLabel('scc_desc'))),
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Description'),
					array('name'				 => 'scc_is_cng', 'value'=> '$data[scc_is_cng]== 1?"Yes":"No"',
						'sortable'			 => true,
						'filter'			 => false,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'CNG'),
					array('name'				 => 'scc_is_petrol_diesel', 'value'	=> '$data[scc_is_petrol_diesel]== 1?"Yes":"No"',
						'sortable'			 => true,
						'filter'			 => false,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Petrol Or Diesel'),
					array('name'				 => 'scc_active', 'value'	=> '$data[scc_active]== 1?"Active":"Inactive"',
						'sortable'			 => true,
						'filter'			 => false,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Status'),
					array(
						'header'			 => 'Action',
						'class'				 => 'CButtonColumn',
						'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
						'template'			 => '{edit}{activeserviceclass}{deactiveserviceclass}',
						'buttons'			 => array(
							'edit'				 => array(
								'url'		 => 'Yii::app()->createUrl("admin/vehicle/serviceclassType", array(\'service_id\' => $data[scc_id]))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\city\edit_booking.png',
								'label'		 => '<i class="fa fa-edit"></i>',
								'options'	 => array('style' => '', 'class' => 'btn btn-xs editBtn p0', 'title' => 'Edit'),
							),
							'activeserviceclass'	 => array(
								"click"		 => "function(e){   var con = confirm('are you sure want to activate service class?'); 
                                                        if(con){change_status(this);}}",
								'url'		 => 'Yii::app()->createUrl("aaohome/vehicle/changeservicestatus", array("scc_id" => $data[scc_id],"scc_active"=>$data[scc_active]))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\inactive.png',
								'visible'	 => '($data[scc_active] == 0)',
								'label'		 => '<i class="fa fa-toggle-on"></i>',
								'options'	 => array('data-toggle' => '', 'id' => '', 'style' => '', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs activateCat p0', 'title' => 'Activate Class')
							),
							'deactiveserviceclass'	 => array(
								"click"		 => "function(e){   var con = confirm('Are you sure want to deactivate service class?');
                                                        if(con){change_status(this);}}",
								'url'		 => 'Yii::app()->createUrl("aaohome/vehicle/changeservicestatus", array("scc_id" => $data[scc_id],"scc_active"=>$data[scc_active]))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\active.png',
								'visible'	 => '($data[scc_active] == 1)',
								'label'		 => '<i class="fa fa-toggle-on"></i>',
								'options'	 => array('data-toggle' => '', 'id' => '', 'style' => '', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs deactivateCat p0', 'title' => 'Deactive Class')
							),
							'htmlOptions'		 => array('class' => 'center'),
						))
			)));
		}
		?>
    </div>
</div>
<?php $this->endWidget(); ?>
<script>
	function refreshCategoryGrid() {
		$('#service_class_grid').yiiGridView('update');
	}
	function change_status(obj) {
		event.preventDefault();
		//alert('hello');
		$href = $(obj).attr("href");
		//alert($href);
		$.ajax({
			type: "GET",
			url: $href,
			success: function (data)
			{
				console.log(data);
				if (data)
				{
					refreshCategoryGrid();
				} else
				{
					alert('Sorry error occured');
				}

			}, error: function (xhr, status, error) {
				alert('Sorry error occured');
			}
		});
		
		return false;
	}
	





</script>