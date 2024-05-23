<div class="row">
    <div class="col-xs-12">
		<div class='panel panel-default'>
			<?php
			$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
				'id'					 => 'tag-form', 'enableClientValidation' => true,
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
			<div class='panel-body'>
				<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
					<?= $form->textFieldGroup($model, 'tagName', array('widgetOptions' => array('htmlOptions' => ['placeholder'=>'Search tags', 'style' => 'text-transform:uppercase']))) ?>
				</div>
			</div>
			<div class='panel-footer'>
				<?php echo CHtml::submitButton("Search", array('class' => 'btn btn-primary')); ?>
			</div>
			<?php $this->endWidget(); ?>
		</div>
	</div>


</div>
<div class="row">
    <div class="col-xs-12">
		<?php
		if (!empty($dataProvider))
		{
			$showSigns								 = '<i class="fa fa-check text-success"></i> : <i class="fa fa-times text-danger"></i>';
			$params									 = array_filter($_REQUEST);
			$dataProvider->getPagination()->params	 = $params;
			$dataProvider->getSort()->params		 = $params;
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
					array('name'				 => 'tag_name', 'value'				 => '$data[tag_name]',
						'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Tag'),
					array('name'				 => 'tag_desc', 'value'				 => '$data[tag_desc]',
						'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Description'),
					array('name'				 => 'tag_booking',
						'value'	 => function ($data) {
							echo ($data['tag_booking']) ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-times text-danger"></i>';
						},						 
						'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Booking'),
					array('name'	 => 'tag_user',
						'value'	 => function ($data) {
							echo ($data['tag_user']) ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-times text-danger"></i>';
						},
						'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'User'),
					array('name'	 => 'tag_partner',
						'value'	 => function ($data) {
							echo ($data['tag_partner']) ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-times text-danger"></i>';
						},
						'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Partner'),
					array('name'	 => 'tag_vendor',
						'value'	 => function ($data) {
							echo ($data['tag_vendor']) ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-times text-danger"></i>';
						},
						'sortable'								 => true,
						'headerHtmlOptions'						 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'							 => array('class' => 'text-center'),
						'header'								 => 'Vendor'),
					array('name'	 => 'tag_driver',
						'value'	 => function ($data) {
							echo ($data['tag_driver']) ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-times text-danger"></i>';
						},
						'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Driver'),
			)));
		}
		?>
    </div>
</div>

<script>
</script>