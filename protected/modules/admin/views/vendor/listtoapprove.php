<script>
	$(document).ready(function () {
		$('#Vendors_vnd_agreement_date').datepicker({
			format: 'dd/mm/yyyy'
		});
	});
</script>
<?php
$vendorCity			 = (Cities::model()->getCityOnlyByBooking1());
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
?>
<div class="row">
	<?php
	if (!$showListOnly)
	{

		Yii::app()->session['vnd_is_nmi']	 = $model->vnd_is_nmi;
		$form								 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'vehicletype-form', 'enableClientValidation' => true,
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

		<div id="userView1">

			<div class="col-xs-12">
				<div class="row">


					<div class="col-xs-12 col-sm-3 col-md-2"> 
						<div class="form-group">
							<label class="control-label" style="margin-left:5px;">Search By Home Zone</label>
							<?php
							$zoneListJson						 = Zones::model()->getJSON();
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model->vendorPrefs,
								'attribute'		 => 'vnp_home_zone',
								'val'			 => $model->vendorPrefs->vnp_home_zone,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($zoneListJson), 'allowClear' => true),
								'htmlOptions'	 => array('style' => 'width:100%;margin-left:5px;', 'placeholder' => 'Zone List')
							));
							?>
						</div>
					</div>
					<div class="col-xs-12 col-sm-3 col-md-3"> 
						<div class="form-group cityinput">
							<label class="control-label">Search By Home City</label>
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
					</div>
					<div class="col-xs-12 col-sm-3 col-md-3"> 
						<div class="form-group">
							<label class="control-label" style="margin-left:5px;">Search By Car Model</label>
							<?php
							$vtypeList							 = VehicleTypes::model()->getVehicleTypeList1();
							$vtypeListJson						 = VehicleTypes::model()->getJSON($vtypeList);

							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $vhtModel,
								'attribute'		 => 'vht_id',
								'val'			 => $vhtModel->vht_id,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($vtypeListJson), 'allowClear' => true),
								'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Vehicle Model')
							));
							?>
						</div>
					</div>

					<div class="col-xs-12 col-sm-3 mt10">
						<?php echo $form->checkboxGroup($model, 'vnd_is_nmi', array('label' => 'Show only pending vendors in NMI zones')); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="/admpnl/generalReport/zoneCsv" target="_blank">(See Current NMI zones)</a>		
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-sm-3 col-md-3 mt10">
						<?php echo $form->checkboxGroup($model, 'vnd_registered_platform', ['label' => 'Show DCO registered only', 'widgetOptions' => ['htmlOptions' => ['value' => 1]]]);
						?>
					</div>

					<div class="col-xs-3 col-sm-3 mt15">
						<div class="form-group">
							<input class="form-control" type="checkbox" id="vnd_active" name="vnd_active" value="2" <?php
							if ($qry['vnd_active'] > 0 || $_POST['vnd_active'])
							{
								echo 'checked="checked"';
							}
							?> >&nbsp;Show Rejected Vendor
						</div></div>
					<div class="col-xs-3 col-sm-3 mt20">
						<button class="btn btn-info  " type="submit"  name="Search" style="width: 185px;">Search</button>
					</div>
				</div>
			</div>
		</div>
	</div>



	<?php
	$this->endWidget();
	?><div class=" col-xs-12 pt30"><?
}
else
{
	?><div class=" "><?
		}
		?>

		<div class="projects ">
			<div class="panel panel-default">
				<div class="panel-body">
					<?php
					if (!empty($dataProvider))
					{
						$this->widget('booster.widgets.TbGridView', array(
							'id'				 => 'vendorjoining-grid',
							'responsiveTable'	 => true,
							'filter'			 => $model,
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
								array('name'	 => 'vnd_name', 'filter' => CHtml::activeTextField($model, 'vnd_name', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->getAttributeLabel('vnd_name'))),
									'value'	 => function ($data) {
										echo CHtml::link($data["vnd_name"], Yii::app()->createUrl("admin/vendor/view", ["id" => $data['vnd_id']]), ["class" => "", "onclick" => "", 'target' => '_blank']);
									},
									'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => $model->getAttributeLabel('vnd_name')),
								array('name' => 'ctt_business_name', 'filter' => CHtml::activeTextField($model->vndContact, 'ctt_business_name', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->vndContact->getAttributeLabel('ctt_business_name'))), 'value' => '$data["ctt_business_name"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Company Name'),
								array('name'	 => 'phn_phone_no', 'filter' => CHtml::activeTextField($model->vndContact->contactPhones, 'phn_phone_no', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->vndContact->contactPhones->getAttributeLabel('phn_phone_no'))), 'value'	 => function ($data) {
										echo $data['phn_phone_no'];
										if ($data['phn_is_verified'] != 0)
										{
											echo ' <span><img src="/images/icon/reconfirmed.png" style="cursor:pointer" title="Contact Verified" width="26"></span>';
										}
										else
										{
											echo ' <span><img src="/images/icon/unblock.png" style="cursor:pointer" title="Contact UnVerified" width="26"></span>';
										}
									}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Phone'),
								array('name'	 => 'eml_email_address', 'filter' => CHtml::activeTextField($model->vndContact->contactEmails, 'eml_email_address', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->vndContact->contactEmails->getAttributeLabel('eml_email_address'))), 'value'	 => function ($data) {
										echo $data['eml_email_address'];
										if ($data['eml_is_verified'] != 0)
										{
											echo ' <span><img src="/images/icon/reconfirmed.png" style="cursor:pointer" title="Contact Verified" width="26"></span>';
										}
										else
										{
											echo ' <span><img src="/images/icon/unblock.png" style="cursor:pointer" title="Contact UnVerified" width="26"></span>';
										}
									}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Email'),
								//array('name' => 'vnd_city_name', 'filter' => CHtml::activeTextField($model, 'vnd_city_name', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->getAttributeLabel('vnd_city'))), 'value' => '$data->cty_name', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'City'),
								array('name' => 'vnd_create_date', 'filter' => CHtml::activeDateField($model, 'vnd_create_date', array('class' => 'form-control', 'placeholder' => 'Search by Joining Date')), 'value' => 'date("d/m/Y H:iA",strtotime($data["vnd_create_date"]))', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Joining on'),
								array('name'	 => 'vnd_active', 'filter' => CHtml::activeHiddenField($model, 'vnd_active', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->getAttributeLabel('vnd_active'))), 'value'	 => function ($data) {
										echo ($data["vnd_active"] == 2) ? 'Rejected' : 'Pending';
									}, 'headerHtmlOptions'	 => array(), 'header'			 => 'Status'),
								array(
									'header'			 => 'Action',
									'class'				 => 'CButtonColumn',
									'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center'),
									'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
									'template'			 => '{approve}{delete}{reject}{revert}{log}',
									'buttons'			 => array(
										'approve'		 => array(
											'url'		 => 'Yii::app()->createUrl("admin/vendor/add", array("agtid" => $data["vnd_id"],"type"=>"approve"))',
											'imageUrl'	 => false,
											'label'		 => '<i class="fa fa-check"></i>',
											'options'	 => array('data-toggle' => 'ajaxModal', 'style' => 'margin-right: 8px', 'class' => 'btn btn-xs btn-warning approve', 'title' => 'Approve'),
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
										'delete'		 => array(
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
											'url'		 => 'Yii::app()->createUrl("admin/vendor/del", array("vndid" => $data["vnd_id"]))',
											'imageUrl'	 => false,
											'label'		 => '<i class="fa fa-remove"></i>',
											'options'	 => array('data-toggle' => 'ajaxModal', 'style' => 'margin-right: 8px', 'class' => 'btn btn-xs btn-danger delete', 'title' => 'Delete'),
										),
										'reject'		 => array(
											'click'		 => 'function(){
											$href = $(this).attr(\'href\');
											jQuery.ajax({type: \'GET\',
											url: $href,
											success: function (data)
											{ 
                                                                                                var box = bootbox.dialog({
													message: data,
													title: \'Vendor Reject\',
													size: \'large\',
													onEscape: function () {

														// user pressed escape
													}
												});
											}
										});
											return false;
											}',
											'url'		 => 'Yii::app()->createUrl("admin/vendor/reject", array("vndid" => $data["vnd_id"]))',
											'imageUrl'	 => false,
											'label'		 => '<i class="fa fa-minus-circle"></i>',
											'visible'	 => '$data["vnd_active"]==2?false:true;',
											'options'	 => array('data-toggle' => 'ajaxModal', 'style' => 'margin-right: 8px', 'class' => 'btn btn-xs btn-danger reject', 'title' => 'Reject'),
										),
										'revert'		 => array(
											'click'		 => 'function(){
											$href = $(this).attr(\'href\');
											jQuery.ajax({type: \'GET\',
											url: $href,
											success: function (data)
											{ 
                                                                                        var box = bootbox.dialog({
													message: data,
													title: \'Vendor Revert\',
													size: \'large\',
													onEscape: function () {

														// user pressed escape
													}
												});
											}
										});
											return false;
											}',
											'url'		 => 'Yii::app()->createUrl("admin/vendor/revert", array("vndid" => $data["vnd_id"],"reason"=>$data["vnd_delete_reason"],"reason_other"=>$data["vnd_delete_other"]))',
											'imageUrl'	 => false,
											'label'		 => '<i class="fa fa-undo"></i>',
											'visible'	 => '$data["vnd_active"]==2?true:false;',
											'options'	 => array('data-toggle' => 'ajaxModal', 'style' => 'margin-right: 8px', 'class' => 'btn btn-xs btn-success revert', 'title' => 'Revert'),
										),
										'htmlOptions'	 => array('class' => 'center'),
									))
						)));
					}
					?>

				</div>
			</div>
		</div>
	</div>
</div>
