<div class="row">
	<?php if (Yii::app()->user->hasFlash('success')): ?>
		<div class="alert alert-success fade in">
			<?php echo Yii::app()->user->getFlash('success'); ?>
		</div>
	<?php endif; ?>

	<!-- Add zone form starts here -->
    <div class="col-xs-12">
		<?php
		$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'homeservicezone-form', 'enableClientValidation' => true,
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
        <div class="">
			<div class="col-xs-12 col-sm-4 col-md-4" >
				<div class="form-group">
					<label class="control-label">Home Service Zone </label>
					<?php
					$zoneList	 = HomeServiceZones::model()->getServiceZoneList($model->hsz_home_id);
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'hsz_service_id',
						'val'			 => $model->hsz_service_id,
						'data'			 => $zoneList,
						'htmlOptions'	 => array('class'			 => 'p0', 'multiple'		 => 'multiple',
							'style'			 => 'width: 100%', 'placeholder'	 => 'Select Home Service Zone', 'required'		 => true)
					));
					?>
				</div></div>
            <div class="col-xs-12 col-sm-4 col-md-4 mt9 mt-xs" >	
				<?= $form->hiddenField($model, 'hsz_home_id', ['value' => $model->hsz_home_id]); ?>		
				<BR><button class="btn btn-primary" type="submit" style="width: 185px;"  name="zoneSearch">Add Zone</button>
            </div>
        </div>
		<?php $this->endWidget(); ?>
    </div>
</div>

<!-- datagrid for Home service zone table start here -->
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
				'columns'			 => array(
					//	array('name' => 'home_zon_name', 'value' => $data['home_zon_name'], 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Home Zone'),
					array('name'	 => 'service_zon_name', 'value'	 => function($data) {
							echo $data['service_zon_name'];
						}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Service Zone '),
					array(
						'header'			 => 'Action',
						'class'				 => 'CButtonColumn',
						'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
						'template'			 => '{delete}',
						'buttons'			 => array(
							'delete'		 => array(
								'click'		 => 'function(){
										if(confirm("Are you sure you want to delete this item?")){
                                                    $href = $(this).attr(\'href\');
                                                    jQuery.ajax({type: \'GET\',
														url: $href,
														success: function (data)
														{
														  location.reload();
														}
													});
											}
                                                    return false;
										}',
								'url'		 => 'Yii::app()->createUrl("admin/zone/removeServiceZone", array("hsz_id" => $data["hsz_id"]))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\zone\remove_zone.png',
								'label'		 => '<i class="fa fa-delete"></i>',
								'options'	 => array('style' => '', 'class' => 'btn btn-xs ignoreJob p0', 'title' => 'Remove'),
							),
							'htmlOptions'	 => array('class' => 'center'),
						))
			)));
		}
		?>
    </div>
</div>