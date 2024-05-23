<div class="row">
    <div class="col-xs-12">
		<?php
		if (!$showListOnly)
		{
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
			<div class="row col-xs-12 mt10">
				<div class="col-xs-12 col-sm-3 col-md-3">Region
					<?php
					$regionList			 = VehicleTypes::model()->getJSON(Vendors::model()->getRegionList());
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'vnd_region',
						'val'			 => $model->vnd_region,
						'asDropDownList' => FALSE,
						'options'		 => array('data' => new CJavaScriptExpression($regionList), 'allowClear' => true),
						'htmlOptions'	 => array('class' => 'p0', 'style' => 'width: 100%', 'placeholder' => 'Select Region')
					));
					?>
				</div>
				<div class="col-xs-12 col-sm-3 col-md-3">Home Zone
					<?php
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'vnd_zone',
						'val'			 => $model->vnd_zone,
						'asDropDownList' => FALSE,
						'options'		 => array('data' => new CJavaScriptExpression(Zones::model()->getJSON()), 'allowClear' => true),
						'htmlOptions'	 => array('style' => 'width:100%;margin-left:5px;', 'placeholder' => 'Zone List')
					));
					?>
				</div>
				<div class="col-xs-12 col-sm-3 col-md-3">Logged In
					<?php
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'vnd_is_loggedin',
						'val'			 => $model->vnd_is_loggedin,
						'asDropDownList' => FALSE,
						'options'		 => array('data' => new CJavaScriptExpression(VehicleTypes::model()->getJSON(Vendors::model()->filterType)), 'allowClear' => true),
						'htmlOptions'	 => array('class' => 'p0', 'style' => 'width: 100%', 'placeholder' => 'Logged In')
					));
					?>

				</div>

				<div class="col-xs-12 col-sm-3 col-md-3">Voter-ID
					<?php
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'vnd_is_voterid',
						'val'			 => $model->vnd_is_voterid,
						'asDropDownList' => FALSE,
						'options'		 => array('data' => new CJavaScriptExpression(VehicleTypes::model()->getJSON(Vendors::model()->filterType)), 'allowClear' => true),
						'htmlOptions'	 => array('class' => 'p0', 'style' => 'width: 100%', 'placeholder' => 'Voter-ID')
					));
					?>
				</div>
			</div>



			<div class="row col-xs-12 mt10">
				<div class="col-xs-12 col-sm-3 col-md-3">PAN
					<?php
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'vnd_is_pan',
						'val'			 => $model->vnd_is_pan,
						'asDropDownList' => FALSE,
						'options'		 => array('data' => new CJavaScriptExpression(VehicleTypes::model()->getJSON(Vendors::model()->filterType)), 'allowClear' => true),
						'htmlOptions'	 => array('class' => 'p0', 'style' => 'width: 100%', 'placeholder' => 'PAN')
					));
					?>
				</div>
				<div class="col-xs-12 col-sm-3 col-md-3">Aadhaar
					<?php
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'vnd_is_aadhar',
						'val'			 => $model->vnd_is_aadhar,
						'asDropDownList' => FALSE,
						'options'		 => array('data' => new CJavaScriptExpression(VehicleTypes::model()->getJSON(Vendors::model()->filterType)), 'allowClear' => true),
						'htmlOptions'	 => array('class' => 'p0', 'style' => 'width: 100%', 'placeholder' => 'Aadhar')
					));
					?>
				</div>
				<div class="col-xs-12 col-sm-3 col-md-3">License
					<?php
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'vnd_is_license',
						'val'			 => $model->vnd_is_license,
						'asDropDownList' => FALSE,
						'options'		 => array('data' => new CJavaScriptExpression(VehicleTypes::model()->getJSON(Vendors::model()->filterType)), 'allowClear' => true),
						'htmlOptions'	 => array('class' => 'p0', 'style' => 'width: 100%', 'placeholder' => 'License')
					));
					?>
				</div>
				<div class="col-xs-12 col-sm-3 col-md-3">Agreement
					<?php
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'vnd_is_agreement',
						'val'			 => $model->vnd_is_agreement,
						'asDropDownList' => FALSE,
						'options'		 => array('data' => new CJavaScriptExpression(VehicleTypes::model()->getJSON(Vendors::model()->filterType)), 'allowClear' => true),
						'htmlOptions'	 => array('class' => 'p0', 'style' => 'width: 100%', 'placeholder' => 'Agreement')
					));
					?>
				</div>
			</div>


			<div class="row col-xs-12 mt10">
				<div class="col-xs-12 col-sm-3 col-md-3">Enter Vendor
					<?= $form->textFieldGroup($model, 'vnd_operator', array('label' => '', 'widgetOptions' => ['htmlOptions' => [array('placeholder' => 'Operator Name')]])) ?>
				</div>
				<div class="col-xs-12 col-sm-3 col-md-3">Bank Details
					<?php
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'vnd_is_bank',
						'val'			 => $model->vnd_is_bank,
						'asDropDownList' => FALSE,
						'options'		 => array('data' => new CJavaScriptExpression(VehicleTypes::model()->getJSON(Vendors::model()->filterType)), 'allowClear' => true),
						'htmlOptions'	 => array('class' => 'p0', 'style' => 'width: 100%', 'placeholder' => 'Bank Details')
					));
					?>
				</div>
				<!--				<div class="col-xs-12 col-sm-3 col-md-3">Approved
				<?php
//					$this->widget('booster.widgets.TbSelect2', array(
//						'model'			 => $model,
//						'attribute'		 => 'vnd_is_approve',
//						'val'			 => $model->vnd_is_approve,
//						'asDropDownList' => FALSE,
//						'options'		 => array('data' => new CJavaScriptExpression(VehicleTypes::model()->getJSON(Vendors::model()->filterType)), 'allowClear' => true),
//						'htmlOptions'	 => array('class' => 'p0', 'style' => 'width: 100%', 'placeholder' => 'Approved')
//					));
				?>
								</div>				-->
				<div class="col-xs-12 col-sm-3 col-md-3">Search By Home City

					<?php
					$vendorCity			 = (Cities::model()->getCityOnlyByBooking1());
					$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
						'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
						'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
						'openOnFocus'		 => true, 'preload'			 => false,
						'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
						'addPrecedence'		 => false,];
					?>
					<?php
					$this->widget('ext.yii-selectize.YiiSelectize', array(
						'model'				 => $model,
						'attribute'			 => 'vnd_city',
						'useWithBootstrap'	 => true,
						"placeholder"		 => "Select City",
						'fullWidth'			 => false,
						'htmlOptions'		 => array('width' => '100%',
						//  'id' => 'from_city_id1'
						),
						'defaultOptions'	 => $selectizeOptions + array(
					'onInitialize'	 => "js:function(){
                                                            populateSourceCity(this, '{$model->vnd_city}');
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
				</div>

				<div class="col-xs-12 col-sm-3 col-md-3">Search By Car Model
					<?php
					$vtypeList			 = VehicleTypes::model()->getVehicleTypeList1();
					$vtypeListJson		 = VehicleTypes::model()->getJSON($vtypeList);

					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'vnd_car_model',
						'val'			 => $model->vnd_car_model,
						'asDropDownList' => FALSE,
						'options'		 => array('data' => new CJavaScriptExpression($vtypeListJson), 'allowClear' => true),
						'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Car Model')
					));
					?>
				</div>
			</div>
			<div class="row col-xs-12 mt10">

				<div class="col-xs-12 col-sm-3 col-md-3">
					<?= $form->textFieldGroup($model, 'vnd_email', array('label' => 'Email', 'widgetOptions' => ['htmlOptions' => ['placeholder' => 'Search by Email']])) ?>
				</div>
				<div class="col-xs-12 col-sm-3 col-md-3">					
					<?= $form->textFieldGroup($model, 'vnd_phone', array('label' => 'Phone', 'widgetOptions' => ['htmlOptions' => ['placeholder' => 'Search By Phone']])) ?> 
				</div>


				<div class="col-xs-12 col-sm-3 col-md-3">
					<?= $form->datePickerGroup($model, 'vnd_create_date1', array('label' => 'Joining Date', 'widgetOptions' => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'Joining Date', 'value' => ($model->vnd_create_date1 != '' ? (DateTimeFormat::DateToDatePicker($model->vnd_create_date1)) : ''))), 'prepend' => '<i class="fa fa-calendar"></i>')); ?>
				</div>
				<div class="col-xs-12 col-sm-3 col-md-3">Vendor Status
					<?php
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'vnd_active',
						'val'			 => $model->vnd_active,
						'asDropDownList' => FALSE,
						'options'		 => array('data' => new CJavaScriptExpression(VehicleTypes::model()->getJSON(Vendors::model()->vendorStatus)), 'allowClear' => true),
						'htmlOptions'	 => array('class' => 'p0', 'style' => 'width: 100%', 'placeholder' => 'Status')
					));
					?>
				</div>
			</div>
			<div class ="row col-xs-12 mt10">

				<div class="col-xs-12 col-sm-3 col-md-3">
					<?php echo $form->checkboxGroup($model, 'vnd_is_nmi', array('label' => 'Show only pending vendors in NMI zones')); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="/admpnl/generalReport/zoneCsv" target="_blank">(See Current NMI zones)</a>		
				</div>
				<div class="col-xs-12 col-sm-3 col-md-3">
	<!--					<input class="form-control" type="checkbox" id="vnd_active" name="vnd_active" value="2" <?php
//					if ($qry['vnd_active'] > 0 || $_POST['vnd_active'])
//					{
//						echo 'checked="checked"';
//					}
					?> >&nbsp;Show Rejected Vendor-->
				</div>
				<div class="col-xs-3 col-sm-3 mt10"> </div>
				<div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 text-center mt20" style="padding: 4px;">
					<button class="btn btn-primary" type="submit" style="width: 185px;"  name="zoneSearch">Search</button> 
				</div>
			</div>

			<?php
			$this->endWidget();
		}
		?>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
		<?php
		$checkExportAccess = Yii::app()->user->checkAccess("Export");
		if ($checkExportAccess && !$showListOnly)
		{
			?>
			<?= CHtml::beginForm(Yii::app()->createUrl('admin/vendor/regprogress'), "post", ['style' => "margin-bottom: 10px; margin-top: 10px; margin-left: 20px;"]); ?>
			<input type="hidden" id="export1" name="export1" value="true"/>
			<input type="hidden" id="export_vnd_region" name="export_vnd_region" value="<?= $model->vnd_region; ?>">
			<input type="hidden" id="export_vnd_zone" name="export_vnd_zone" value="<?= $model->vnd_zone; ?>">
			<input type="hidden" id="export_vnd_is_loggedin" name="export_vnd_is_loggedin" value="<?= $model->vnd_is_loggedin; ?>">
			<input type="hidden" id="export_vnd_is_voterid" name="export_vnd_is_voterid" value="<?= $model->vnd_is_voterid; ?>">
			<input type="hidden" id="export_vnd_is_pan" name="export_vnd_is_pan" value="<?= $model->vnd_is_pan; ?>">
			<input type="hidden" id="export_vnd_is_aadhar" name="export_vnd_is_aadhar" value="<?= $model->vnd_is_aadhar; ?>">            
			<input type="hidden" id="export_vnd_is_license" name="export_vnd_is_license" value="<?= $model->vnd_is_license; ?>">
			<input type="hidden" id="export_vnd_is_agreement" name="export_vnd_is_agreement" value="<?= $model->vnd_is_agreement; ?>">
			<input type="hidden" id="export_vnd_operator" name="export_vnd_operator" value="<?= $model->vnd_operator; ?>">
			<input type="hidden" id="export_vnd_is_bank" name="export_vnd_is_bank" value="<?= $model->vnd_is_bank; ?>">
			<!--<input type="hidden" id="export_vnd_is_approve" name="export_vnd_is_approve" value="<? //= $model->vnd_is_approve;  ?>">-->

			<input type="hidden" id="export_vnd_ciy" name="export_vnd_city" value="<?= $model->vnd_city; ?>">
			<input type="hidden" id="export_vnd_car_model" name="export_vnd_car_model" value="<?= $model->vnd_vehicle_type; ?>">
			<input type="hidden" id="export_vnd_email" name="export_vnd_email" value="<?= $model->vnd_email; ?>">
			<input type="hidden" id="export_vnd_phone" name="export_vnd_phone" value="<?= $model->vnd_phone; ?>">
			<input type="hidden" id="export_vnd_create_date1" name="export_vnd_create_date1" value="<?= $model->vnd_create_date1; ?>">
			<input type="hidden" id="export_vnd_active" name="export_vnd_active" value="<?= $model->vnd_active; ?>">
			<input type="hidden" id ="export_vnd_is_nmi" name ="export_vnd_is_nmi" value="<?= $model->vnd_is_nmi ?>">

			<button class="btn btn-default" type="submit" style="width: 185px;">Export Below Table</button>
			<?= CHtml::endForm() ?>
			<?php
		}
		if (!empty($dataProvider))
		{

			$this->widget('booster.widgets.TbGridView', array(
				'responsiveTable'	 => true,
				'id'				 => 'vendorRegProgressGrid',
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
					array('name'	 => 'vnd_name', 'value'	 => function($data) {
							echo CHtml::link($data['vnd_name'], Yii::app()->createUrl('/admpnl/vendor/view', array('id' => $data['vnd_id'])), array('target' => '_blank'));
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Vendor'),
					array('name'				 => 'zon_name', 'value'				 => $data['zon_name'], 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Home Zone'),
					array('name'				 => 'region', 'value'				 => $data['region'], 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Region'),
					array('name'				 => 'vnd_create_date', 'value'				 => 'DateTimeFormat::DateTimeToLocale($data["vnd_create_date"])', 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Registered'),
					array('name'				 => 'vrs_last_logged_in', 'value'				 => 'DateTimeFormat::DateTimeToLocale($data["vrs_last_logged_in"])', 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Last Logged In'),
//					array('name'				 => 'last_login', 'value'				 => 'DateTimeFormat::DateTimeToLocale($data["last_login"])', 'sortable'			 => true,
//						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
//						'htmlOptions'		 => array('class' => 'text-right'),
//						'header'			 => 'Logged In'),
					array('name'				 => 'vag_digital_date', 'value'				 => 'DateTimeFormat::DateTimeToLocale($data["vag_digital_date"])', 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-right'),
						'header'			 => 'Digital Agreement Date'),
					array('name'	 => 'vrascore', 'value'	 => function($data) {
							echo ($data['vrascore'] > 0) ? CHtml::link($data['vrascore'], Yii::app()->createUrl('/admpnl/vendor/view', array('id' => $data['vnd_id'])), array('target' => '_blank')) : 'NA';
							echo "<br>";
							echo ($data['vrs_docs_r4a'] == 1) ? '[ Ready 4 Approval ]' : '';
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'R4A score(Ready for Approval Score)'),
					array('name'	 => 'vag_draft_agreement', 'value'	 => function($data) {
                            $draftAgreement = VendorAgreement::getPathById($data['vag_id'], VendorAgreement::DRAFT_AGREEMENT);    
							echo ($data['vag_draft_agreement'] != '') ? CHtml::link('Draft', $draftAgreement, array('target' => '_blank')) : 'No Draft';
							echo " / ";
                            $digitalAgreement = VendorAgreement::getPathById($data['vag_id'], VendorAgreement::DIGITAL_AGREEMENT);    
							echo ($data['vag_digital_agreement'] != '') ? CHtml::link('Digital', $digitalAgreement, array('target' => '_blank')) : 'No Digital';
						}, 'sortable'			 => false,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Agreement'),
					array('name'				 => 'bank_details', 'value'				 => $data['bank_details'], 'sortable'			 => false,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Bank Details'),
					array('name'	 => 'countdriver',
						'value'	 => function($data) {
							echo($data['countdriver'] > 0) ? CHtml::link($data['countdriver'], Yii::app()->createUrl('admin/driver/list', ['vnd' => $data['vnd_id']]), array('target' => '_blank')) : 0;
						},
						'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Drivers Added'),
					array('name'	 => 'countcars',
						'value'	 => function($data) {
							echo ($data['countcars'] > 0) ? CHtml::link($data['countcars'], Yii::app()->createUrl('admin/vehicle/list', ['vnd' => $data['vnd_id']]), array('target' => '_blank')) : 0;
						},
						'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Cars Added'),
					array('name'				 => 'approve', 'value'				 => $data['approve'], 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Approved'),
					array(
						'header'			 => 'Action',
						'class'				 => 'CButtonColumn',
						'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center'),
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
						'template'			 => '{approve}{delete}{view}{log}{orientationSet}{orientationUnset}',
						'buttons'			 => array(
							'approve'			 => array(
								'url'		 => 'Yii::app()->createUrl("admin/vendor/add", array("agtid" => $data["vnd_id"],"type"=>"approve"))',
								'imageUrl'	 => false,
								'visible'	 => '($data["vnd_active"] != 1)',
								'label'		 => '<i class="fa fa-check"></i>',
								'options'	 => array('data-toggle' => 'ajaxModal', 'style' => 'margin-right: 8px', 'class' => 'btn btn-xs btn-warning approve', 'title' => 'Approve'),
							),
							'delete'			 => array(
								'click'		 => 'function(){
                                            var con = confirm("Are you sure you to delete this vendor?");
                                            return con;
                                            }',
								'url'		 => 'Yii::app()->createUrl("admin/vendor/safedelete", array("agtid" => $data["vnd_id"]))',
								'imageUrl'	 => false,
								'visible'	 => '($data["vnd_active"] != 1)',
								'label'		 => '<i class="fa fa-remove"></i>',
								'options'	 => array('data-toggle' => 'ajaxModal', 'style' => 'margin-right: 8px', 'class' => 'btn btn-xs btn-danger delete', 'title' => 'Delete'),
							),
							'log'				 => array(
								'click'		 => 'function(){
								$href = $(this).attr(\'href\');
								jQuery.ajax({type: \'GET\',
								url: $href,
								success: function (data)
								{

									var box = bootbox.dialog({
										message: data,
										title: \'Vendor Log\',
										size: \'large\',
										onEscape: function () {

											// user pressed escape
										}
									});
								}
							});
								return false;
								}',
								'url'		 => 'Yii::app()->createUrl("admin/vendor/showlog", array("vndid" => $data[vnd_id]))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\vendor\show_log.png',
								'label'		 => '<i class="fa fa-list"></i>',
								'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'class' => 'btn btn-xs conshowlog p0', 'title' => 'Show Log'),
							),
							'orientationSet'	 => array(
								'click'		 => 'function(e)
								{                                                        
									try
									{
										$href = $(this).attr("href");
										jQuery.ajax({type:"GET",url:$href,success:function(data)
										{
											bootbox.dialog({ 
											message: data, 
											className:"bootbox-sm",
											title:"Is vendor orientation call complete? ",
											success: function(result){
												if(result.success)
												{

												}else
												{
													alert(\'Sorry error occured\');
												}
											},
											error: function(xhr, status, error){
												alert(\'Sorry error occured\');
											}
										});
										}}); 
									}
									catch(e)
									{ 
										alert(e); 
									}
									return false;

								}',
								'url'		 => 'Yii::app()->createUrl("admpnl/vendor/unsetOrientation", array("vnd_id" => $data["vnd_id"]))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\vendor\orientationUncheck.png',
								'visible'	 => '($data[vnp_is_orientation] == 1  && $data[vnp_orientation_type] == 0)',
								'label'		 => '<i class="fa fa-toggle-on"></i>',
								'options'	 => array('data-toggle'	 => 'ajaxModal',
									'id'			 => 'isOrientation',
									'style'			 => '',
									'rel'			 => 'popover',
									'data-placement' => 'left',
									'class'			 => 'btn btn-xs set p0',
									'title'			 => 'Orientation call required')
							),
							'orientationUnset'	 => array(
								'click'		 => 'function(e)
								{                                                        
									try
									{
										$href = $(this).attr("href");
										jQuery.ajax({type:"GET",url:$href,success:function(data)
										{
											bootbox.dialog({ 
											message: data, 
											className:"bootbox-sm",
											title:"Is vendor orientation call complete? ",
											success: function(result){
												if(result.success)
												{

												}else
												{
													alert(\'Sorry error occured\');
												}
											},
											error: function(xhr, status, error){
												alert(\'Sorry error occured\');
											}
										});
										}}); 
									}
									catch(e)
									{ 
										alert(e); 
									}
									return false;

								}',
								'url'		 => 'Yii::app()->createUrl("admpnl/vendor/unsetOrientation", array("vnd_id" => $data["vnd_id"]))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\vendor\orientation_no.png',
								'visible'	 => '($data[vnp_is_orientation] == 1 && $data[vnp_orientation_type] == 2)',
								'label'		 => '<i class="fa fa-toggle-on"></i>',
								'options'	 => array('data-toggle'	 => 'ajaxModal',
									'id'			 => 'isOrientationUnset',
									'style'			 => '',
									'rel'			 => 'popover',
									'data-placement' => 'left',
									'class'			 => 'btn btn-xs unset p0',
									'title'			 => 'Orientation call required')
							),
							'view'				 => array(
								'click'		 => 'function(){
                                    $href = $(this).attr(\'href\');
                                    jQuery.ajax({type: \'GET\',
                                    url: $href,
                                    success: function (data)
                                    {
                                        var box = bootbox.dialog({
                                            message: data,
                                            title: \' Contact Details: \',
                                            size: \'large\',
                                            onEscape: function () {

                                                // user pressed escape
                                            }
                                        });
                                    }
                                });
                                    return false;
                                    }',
								'url'		 => 'Yii::app()->createUrl("admin/contact/view", array(\'ctt_id\' => $data[ctt_id],\'viewType\' =>\'vendor\' ))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\vendor\contact.png',
								'label'		 => '<i class="fas fa-eye"></i>',
								'options'	 => array('style' => '', 'class' => 'btn btn-xs ignoreJobView p0', 'title' => 'View Contact'),
							),
							'htmlOptions'		 => array('class' => 'center'),
						))
			)));
		}
		?>
    </div>
</div>
<script>
    function refreshVendorGrid()
    {
        $('#vendorRegProgressGrid').yiiGridView('update');
    }
</script>	