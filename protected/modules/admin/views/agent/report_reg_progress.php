<div class="row">
    <div class="col-xs-12">
		<?php
		/* @var $model Vendors */
		$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'regProgressForm', 'enableClientValidation' => true,
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
        <div class="row  ">
            <div class="col-xs-12 col-sm-6 col-md-3">Voter-ID
				<?php
				$this->widget('booster.widgets.TbSelect2', array(
					'model'			 => $model,
					'attribute'		 => 'agt_is_voterid',
					'val'			 => $model->agt_is_voterid,
					'asDropDownList' => FALSE,
					'options'		 => array('data' => new CJavaScriptExpression(VehicleTypes::model()->getJSON(Vendors::model()->filterType)), 'allowClear' => true),
					'htmlOptions'	 => array('class' => 'p0', 'style' => 'width: 100%', 'placeholder' => 'Voter-ID')
				));
				?>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3">Driver License
				<?php
				$this->widget('booster.widgets.TbSelect2', array(
					'model'			 => $model,
					'attribute'		 => 'agt_is_driver_license',
					'val'			 => $model->agt_is_driver_license,
					'asDropDownList' => FALSE,
					'options'		 => array('data' => new CJavaScriptExpression(VehicleTypes::model()->getJSON(Vendors::model()->filterType)), 'allowClear' => true),
					'htmlOptions'	 => array('class' => 'p0', 'style' => 'width: 100%', 'placeholder' => 'Driver License')
				));
				?>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-3"> Aadhar
				<?php
				$this->widget('booster.widgets.TbSelect2', array(
					'model'			 => $model,
					'attribute'		 => 'agt_is_aadhar',
					'val'			 => $model->agt_is_aadhar,
					'asDropDownList' => FALSE,
					'options'		 => array('data' => new CJavaScriptExpression(VehicleTypes::model()->getJSON(Vendors::model()->filterType)), 'allowClear' => true),
					'htmlOptions'	 => array('class' => 'p0', 'style' => 'width: 100%', 'placeholder' => 'Aadhar')
				));
				?>
            </div>
			<div class="col-xs-12 col-sm-6 col-md-3"> Name
				<?= $form->textFieldGroup($model, 'agt_first_name', array('label' => '', 'widgetOptions' => ['htmlOptions' => ['placeholder' => 'Name']]))
				?>
            </div> 

        </div>
		<div class="col-xs-12 text-center " style="padding: 4px;">
			<button class="btn btn-primary" type="submit" style="width: 185px;"  name="report">Search</button> 
		</div>
		<?php $this->endWidget(); ?>
    </div>
</div>


<div class="row">
    <div class="col-xs-12">
		<?php
		$checkExportAccess	 = Yii::app()->user->checkAccess("Export");
		if ($checkExportAccess)
		{
			?>
			<?= CHtml::beginForm(Yii::app()->createUrl('admin/agent/regprogress'), "post", ['style' => "margin-bottom: 10px; margin-top: 10px; margin-left: 20px;"]); ?>
			<input type="hidden" id="export1" name="export1" value="true"/>
			<input type="hidden" id="export_agt_is_voterid" name="export_agt_is_voterid" value="<?= $model->agt_is_voterid; ?>">
			<input type="hidden" id="export_agt_is_driver_license" name="export_agt_is_driver_license" value="<?= $model->agt_is_driver_license; ?>">
			<input type="hidden" id="export_agt_is_aadhar" name="export_agt_is_aadhar" value="<?= $model->agt_is_aadhar; ?>">
			<input type="hidden" id="export_agt_first_name" name="export_agt_first_name" value="<?= $model->agt_first_name; ?>">
			<button class="btn btn-default" type="submit" style="width: 185px;">Export Below Table</button>
			<?= CHtml::endForm() ?>
			<?php
		}
		if (!empty($dataProvider))
		{
			$this->widget('booster.widgets.TbGridView', array(
				'responsiveTable'	 => true,
				'dataProvider'		 => $dataProvider,
				'template'			 => "<div class='panel-heading'>
		    <div class='row m0'>
			<div class='col-xs-12 col-sm-6 pt5'>{summary}</div>
			<div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
		    </div>
		</div>
		<div class='panel-body'>{items}</div>
		<div class='panel-footer'>
		    <div class='row m0'>
			<div class='col-xs-12 col-sm-6 p5'>{summary}</div>
			<div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
		    </div>
		</div>",
				'itemsCssClass'		 => 'table table-striped table-bordered mb0',
				'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
				'columns'			 => array(
					array('name'				 => 'agt_fname', 'value'				 => $data['agt_fname'], 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Name'),
					array('name'				 => 'agt_email', 'value'				 => $data['agt_email'], 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Email'),
					array('name'				 => 'cty_name', 'value'				 => $data['cty_name'], 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'City Name'),
					array('name'				 => 'agt_create_date', 'value'				 => $data['agt_create_date'], 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Registered'),
					array('name'				 => 'voterPath', 'value'				 => $data['voterPath'], 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Voter Id'),
					array('name'	 => 'aadharPath', 'value'	 => function($data) {
							echo ($data['aadharPath'] != 'No') ? CHtml::link('Aadhar Link', Yii::app()->createUrl($data['aadharPath']), array('target' => '_blank')) : 'No';
						}, 'sortable'			 => false,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Aadhar'),
					array('name'				 => 'driverLicense', 'value'				 => $data['driverLicense'], 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Driver License'),
					array('name'				 => 'tradeLicense', 'value'				 => $data['tradeLicense'], 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Trade License'),
					array('name'				 => 'bankDeatils', 'value'				 => $data['bankDeatils'], 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Bank Details'),
			)));
		}
		?>
    </div>
</div>