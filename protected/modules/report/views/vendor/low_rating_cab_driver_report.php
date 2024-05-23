<?php
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
?>
<div class="row">
	<?php
	/* @var $model Vendors */
	$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
		'id'					 => 'vendorFrm', 'enableClientValidation' => true,
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
	<div class="col-xs-3 col-sm-4 col-md-4"> 
			<label class="control-label">Vendor</label><br>
			<?php
				$this->widget('ext.yii-selectize.YiiSelectize', array(
					'model'				 => $model,
					'attribute'			 => 'vnd_id',
					'useWithBootstrap'	 => true,
					"placeholder"		 => "Select Vendor",
					'fullWidth'			 => false,
					'htmlOptions'		 => array('width' => '100%'),
					'defaultOptions'	 => $selectizeOptions + array(
				'onInitialize'	 => "js:function(){
				populateVendor(this, '{$model->vnd_name}');
				}",
				'load'			 => "js:function(query, callback){
				loadVendor(query, callback);
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
	</div>

	<div class="col-xs-3 col-sm-6 col-md-2 col-lg-1 mr15 mt20"  >
		<button class="btn btn-primary" type="submit" style="width: 125px;" >Search</button> 
	</div>
	<?php $this->endWidget(); ?>
	<div class="col-xs-3 col-sm-4 col-md-4 form-group ">
		<?php
		$checkExportAccess	 = false;
		if ($roles['rpt_export_roles'] != null)
		{
			$checkExportAccess = Filter::checkACL($roles['rpt_export_roles']);
		}
		if ($checkExportAccess)
		{
			echo CHtml::beginForm(Yii::app()->createUrl('report/vendor/lowRatingCabDriver'), "post", []);
			?>
			<input type="hidden" id="vendorId" name="vendorId" value="<?= $model->vnd_id; ?>"/>
			<input type="hidden" id="export" name="export" value="true"/>
			<button class="btn btn-default btn-5x pr30 pl30 mt20" type="submit" style="width: 185px;">Export</button>
		<?php echo CHtml::endForm();  
		} ?>
	</div>
</div>



<div class="row" >
    <div class="col-xs-12">
        <div class="row">
            <div class="col-xs-12">
                <div class="  table table-bordered">
					<?php
					if (!empty($dataProvider))
					{
						$params									 = array_filter($_REQUEST);
						$dataProvider->getPagination()->params	 = $params;
						$dataProvider->getSort()->params		 = $params;
						$this->widget('booster.widgets.TbGridView', array(
							'id'				 => 'vendorListGrid',
							'responsiveTable'	 => true,
							'dataProvider'		 => $dataProvider,
							'template'			 => "<div class='panel-heading'><div class='row m0'>
							<div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
							</div></div>
							<div class='panel-body table-responsive'>{items}</div>
							<div class='panel-footer'>
							<div class='row'><div class='col-xs-12 col-sm-6 p5'>{summary}</div>
							<div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
							'itemsCssClass'		 => 'table  table-bordered dataTable mb0',
							'htmlOptions'		 => array('class' => 'panel panel-primary compact'),
							'columns'			 => array(
								array('name'	 => 'vnd_code', 'value'	 => function ($data) 
								{
										echo CHtml::link($data["vnd_code"]." ( " .$data["vnd_name"]." )", Yii::app()->createUrl("admin/vendor/view", ["id" => $data['vnd_id']]), ["class" => "", "target" => "_blank"]);

								}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Operator Name'),
								array('name' => 'drv_id', 'value' => '$data[drv_id]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Driver Id'),
								array('name' => 'vhc_id', 'value' => '$data[vhc_id]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Vehicle Ids'),
						)));
					}
					?> 
                </div>
            </div>  
        </div> 
    </div>  
</div>