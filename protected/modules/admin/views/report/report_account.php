<?
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
?>
<style>
    .panel-body {
        padding-top: 0;
        padding-bottom: 0;
    }
    .table>tbody>tr>th {
        vertical-align: middle
    }
    .table>tbody>tr>td, .table>tbody>tr>th {
        padding: 7px;
        line-height: 1.5em;
    }
</style>
<div class="row m0">
    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
					<?php
					$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'account-report-form', 'enableClientValidation' => true,
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
                    <div class="col-xs-12 col-sm-4 col-md-3">
						<?= $form->datePickerGroup($model, 'bkg_create_date1', array('label' => 'From Date', 'widgetOptions' => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'From Date')), 'prepend' => '<i class="fa fa-calendar"></i>')); ?>
                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-3">
						<?=
						$form->datePickerGroup($model, 'bkg_create_date2', array('label'			 => 'To Date',
							'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'To Date')), 'prepend'		 => '<i class="fa fa-calendar"></i>'));
						?>
                    </div>




					<div class="col-xs-12 col-sm-4 col-md-3"> 
                        <div class="form-group cityinput">
                            <label class="control-label">Vendor</label>
							<?php
							$this->widget('ext.yii-selectize.YiiSelectize', array(
								'model'				 => $model,
								'attribute'			 => 'bcb_vendor_id',
								'useWithBootstrap'	 => true,
								"placeholder"		 => "Vendor",
								'fullWidth'			 => false,
								'htmlOptions'		 => array('width' => '100%'),
								'defaultOptions'	 => $selectizeOptions + array(
							'onInitialize'	 => "js:function(){
                                  populateVendor(this, '{$model->bcb_vendor_id}');
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
                        </div> </div>
                   
                    <div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 text-center mt20 p5">
						<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width')); ?>
                    </div>

					<?php $this->endWidget(); ?>
                </div>
                <div>&nbsp;</div>
				<?php
				if (!empty($dataProvider))
				{
					$checkContactAccess						 = Yii::app()->user->checkAccess("bookingContactAccess");
					$params									 = array_filter($_REQUEST);
					$dataProvider->getPagination()->params	 = $params;
					$dataProvider->getSort()->params		 = $params;
					$this->widget('booster.widgets.TbGridView', array(
						'responsiveTable'	 => true,
						'dataProvider'		 => $dataProvider,
						'id'				 => 'reportAccountGrid',
						'template'			 => "<div class='panel-heading'><div class='row m0'>
                                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                                    </div></div>
                                                    <div class='panel-body table-responsive'>{items}</div>
                                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
						'itemsCssClass'		 => 'table table-striped table-bordered dataTable mb0',
						'htmlOptions'		 => array('class' => 'panel panel-primary  compact'),
						//    'ajaxType' => 'POST',
						'columns'			 => array(
							array('name'	 => 'bkg_booking_id', 'value'	 => function($data) {
									if ($data->bkg_booking_id != '')
									{
										echo CHtml::link($data->bkg_booking_id, Yii::app()->createUrl("admin/booking/view", ["id" => $data->bkg_id]), ["class" => "viewBooking", "onclick" => "return viewBooking(this)"]);
										echo "<br>";
										if ($data->bkgUserInfo->bkg_user_id != NULL)
										{
											if (Users::model()->checkUserMarkCount($data->bkgUserInfo->bkg_user_id) > 0)
											{
												echo '<span class="fa-stack" title="Bad Customer">
  <i class="fa fa-user fa-stack-1x"></i>
  <i class="fa fa-ban fa-stack-2x text-danger"></i>
</span>';
											}
										}
										if ($data->bkgBcb->bcb_cab_id != NULL)
										{

											if (Vehicles::model()->checkVehicleMarkCount($data->bkgBcb->bcb_cab_id) > 0)
											{
												echo '<span class="fa-stack" title="Bad Car">
  <i class="fa fa-car fa-stack-1x"></i>
  <i class="fa fa-ban fa-stack-2x text-danger"></i>
</span>';
											}
										}
										if ($data->bkgBcb->bcb_driver_id != NULL)
										{

											if (Drivers::model()->checkDriverMarkCount($data->bkgBcb->bcb_driver_id) > 0)
											{
												echo '<span class="fa-stack" title="Bad Driver">
  <i class="fa-user-secret fa-stack-1x"></i>
  <i class="fa fa-ban fa-stack-2x text-danger"></i>
</span>';
											}
										}
									}
								}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Booking ID'),
							array('name' => 'bkg_user_name', 'value' => '$data->bkg_user_name." ".$data->bkgUserInfo->bkg_user_lname', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Customer'),
							array('name'	 => 'from_city_name', 'value'	 => function($data) {
									echo $data->bkgFromCity->cty_name . " - " . $data->bkgToCity->cty_name;
								}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Route'),
							array('name' => 'bkg_vnd_name', 'value' => '$data->bkgBcb->bcbVendor->vnd_name', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Vendor Name'),
							array('name'	 => 'bkg_pickup_date',
								'value'	 => function ($data) {
									return DateTimeFormat::DateTimeToLocale($data->bkg_pickup_date);
								},
								'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'col-xs-2,text-center'), 'header'			 => 'Pickup Date'),
							array('name'	 => 'bkg_amount', 'value'	 => function($data) {
									if ($data->bkgInvoice->bkg_total_amount > 0)
									{
										echo '<i class="fa fa-inr"></i>' . $data->bkgInvoice->bkg_total_amount;
									}
								}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'col-xs-2', 'style' => 'white-space:nowrap;text-align: center'), 'header'			 => 'Total Amount'),
							array('name'	 => 'bkg_vendor_amount', 'value'	 => function($data) {
									if ($data->bkgInvoice->bkg_vendor_amount > 0)
									{
										echo '<i class="fa fa-inr"></i>' . $data->bkgInvoice->bkg_vendor_amount;
									}
								}, 'sortable'			 => false, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'col-xs-2', 'style' => 'white-space:nowrap;text-align: center'), 'header'			 => 'Vendor Amount'),
							array('name'	 => 'bkg_advance_amount', 'value'	 => function($data) {
									if ($data->bkgInvoice->bkg_advance_amount > 0)
									{
										echo '<i class="fa fa-inr"></i>' . $data->bkgInvoice->bkg_advance_amount;
									}
								}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'col-xs-2', 'style' => 'white-space:nowrap;text-align: center'), 'header'			 => 'Advance Amt'),
							array('name'	 => 'bkg_amount_due', 'value'	 => function($data) {
									if ($data->bkgInvoice->bkg_total_amount > 0 && $data->bkgInvoice->bkg_advance_amount > 0)
									{
										echo '<i class="fa fa-inr"></i>' . round($data->bkgInvoice->bkg_total_amount - $data->bkgInvoice->bkg_advance_amount);
									}
								}, 'sortable'			 => false, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'col-xs-2', 'style' => 'white-space:nowrap;text-align: center'), 'header'			 => 'Amount Due'),
							array(
								'header'			 => 'Action',
								'class'				 => 'CButtonColumn',
								'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center'),
								'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
								'template'			 => '{edit}{log}',
								'buttons'			 => array(
									'edit'			 => array(
										'click'		 => 'function(){
                                                    $href = $(this).attr(\'href\');
                                                    jQuery.ajax({type: \'GET\',
                                                    url: $href,
                                                    success: function (data)
                                                    {
                                                        bootbox.dialog({
                                                            message: data,
                                                            title: \'Update Account\',
                                                            onEscape: function () {

                                                                // user pressed escape
                                                            }
                                                        });
                                                    }
                                                });
                                                    return false;
                                                    }',
										'url'		 => 'Yii::app()->createUrl("admin/report/accountedit", array("bkgId" => $data->bkg_id))',
										'imageUrl'	 => false,
										'label'		 => '<i class="fa fa-edit"></i>',
										'options'	 => array('data-toggle' => 'ajaxModal', 'style' => 'margin-right: 4px', 'class' => 'btn btn-xs btn-info ignoreJob', 'title' => 'Edit Account'),
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
                                                            title: \'Booking Log\',
                                                            onEscape: function () {

                                                                // user pressed escape
                                                            }
                                                        });
                                                    }
                                                });
                                                    return false;
                                                    }',
										'url'		 => 'Yii::app()->createUrl("admin/booking/showlog", array("booking_id" => $data->bkg_id))',
										'imageUrl'	 => false,
										'label'		 => '<i class="fa fa-list"></i>',
										'options'	 => array('data-toggle' => 'ajaxModal', 'style' => 'margin-right: 4px', 'class' => 'btn btn-primary btn-xs conshowlog', 'title' => 'Show Log'),
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
<?php
$version = Yii::app()->params['customJsVersion'];
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/custom.js?v=' . $version, CClientScript::POS_HEAD);
?>
<script>
    $sourceList = null;
    function refreshAccGrid() {
        $('#reportAccountGrid').yiiGridView('update');
    }
    function populateVendor(obj, vndId) {
        obj.load(function (callback) {
            var obj = this;
            if ($sourceList == null) {
                xhr = $.ajax({
                    url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allvendorbyquery', ['onlyActive' => 0, 'vnd' => ''])) ?>' + vndId,
                    dataType: 'json',
                    data: {},
                    //  async: false,
                    success: function (results) {
                        $sourceList = results;
                        obj.enable();
                        callback($sourceList);
                        obj.setValue(vndId);
                    },
                    error: function () {
                        callback();
                    }
                });
            } else {
                obj.enable();
                callback($sourceList);
                obj.setValue(vndId);
            }
        });
    }
    function loadVendor(query, callback) {
        //	if (!query.length) return callback();
        $.ajax({
            url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allvendorbyquery')) ?>?onlyActive=0&q=' + encodeURIComponent(query),
            type: 'GET',
            dataType: 'json',
            global: false,
            error: function () {
                callback();
            },
            success: function (res) {
                callback(res);
            }
        });
    }

</script>


