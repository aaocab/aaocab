<style type="text/css">
    .edit-button{
        display: none;
    }
    .booking-log{
        display: none;
    }
    .below-buttons{
        display: none;
    }
    .selectize-input { width:100%;}
	.yii-selectize { min-width: 100%;}
</style>
<?
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
?>
<div class="row">
<div class="col-xs-12 col-sm-10 col-md-10">
	<?php
	$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
		'id'					 => 'rating-form', 'enableClientValidation' => true,
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
        <div class="col-xs-6 col-sm-4 col-md-5 col-lg-3">
            <div class="form-group">
                <label class="control-label">Region</label>
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
        </div>
		<div class="col-xs-6 col-sm-6 col-md-6 col-lg-3" style="">
			<div class="form-group">
				<?php
				$daterang			 = "Select Date Range";
				$createdate1		 = ($model->rtg_create_date1 == '') ? '' : $model->rtg_create_date1;
				$createdate2		 = ($model->rtg_create_date2 == '') ? '' : $model->rtg_create_date2;
				if ($createdate1 != '' && $createdate2 != '')
				{
					$daterang = date('F d, Y', strtotime($createdate1)) . " - " . date('F d, Y', strtotime($createdate2));
				}
				?>
				<label  class="control-label">Date</label>
				<div id="rtgCreateDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
					<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
					<span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
				</div>
				<?php
				echo $form->hiddenField($model, 'rtg_create_date1', ['class' => 'createDate1', 'value' => $model->rtg_create_date1]);
				echo $form->hiddenField($model, 'rtg_create_date2', ['class' => 'createDate2', 'value' => $model->rtg_create_date2]);
				?>
			</div>
		</div>
        <div class="col-xs-6 col-sm-4 col-md-5 col-lg-2">
            <div class="form-group">
                <label class="control-label">Channel Partner</label>
				<?php
				$this->widget('ext.yii-selectize.YiiSelectize', array(
					'model'				 => $model,
					'attribute'			 => 'channel_partner_id',
					'useWithBootstrap'	 => true,
					"placeholder"		 => "Select Channel Partner",
					'fullWidth'			 => false,
					'htmlOptions'		 => array('width' => '100%'),
					'defaultOptions'	 => $selectizeOptions + array(
				'onInitialize'	 => "js:function(){
									  populatePartner(this, '{$model->channel_partner_id}');
									}",
				'load'			 => "js:function(query, callback){
									loadPartner(query, callback);
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
        </div>

        <div class="col-xs-6 col-sm-4 col-md-5 col-lg-2">
            <div class="form-group">
                <label class="control-label">Vendor</label>
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
                                              populateVendor(this, '{$model->vnd_id}');
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
        </div>
		<div class="col-xs-6 col-sm-3 form-group">
			<?=$form->dropDownListGroup($model, 'category', ['label' => 'User Category', 'widgetOptions' => ['data' => ['-1' => 'select user category'] + UserCategoryMaster::catDropdownList(), 'htmlOptions' => []]]) ?>
		</div>
			<div class="col-xs-6 col-sm-3 form-group" >
					<label class="control-label">Tags</label>
					<?php
					$SubgroupArray2		 = Tags::getListByType(Tags::TYPE_USER);
					$this->widget('booster.widgets.TbSelect2', array(
						//'name'			 => 'bkg_tags',
						'attribute'		 => 'strTags',
						'model'			 => $model,
						'val'			 => explode(',',$model->strTags),
						'data'			 => $SubgroupArray2,
						'htmlOptions'	 => array(
							'multiple'		 => 'multiple',
							'placeholder'	 => 'Search tags keywords ',
							'style'			 => 'width:100%'
						),
					));
					?>
			</div>
        <div class="col-xs-12 text-center mt20 p5">
            <button class="btn btn-primary" type="submit"  name="bookingSearch">Search</button>
        </div>
    </div>
	<?php $this->endWidget(); ?>
</div>
<div class="col-xs-12 col-sm-2 mt20 p5">
	<?php
	$form1 = $this->beginWidget('booster.widgets.TbActiveForm', array(
		'id'					 => 'export-form', 'enableClientValidation' => true,
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
	<?= $form1->hiddenField($model, 'vnd_region', ['class' => '', 'id' => 'vnd_region']); ?>
	<?= $form1->hiddenField($model, 'channel_partner_id', ['class' => '', 'id' => 'channel_partner_id']); ?>
	<?= $form1->hiddenField($model, 'vnd_id', ['id' => 'vnd_id']); ?>
	<?= $form1->hiddenField($model, 'rtg_create_date1', ['class' => 'createDate1', 'id' => 'rtg_create_date1']); ?>
	<?= $form1->hiddenField($model, 'rtg_create_date2', ['class' => 'createDate2', 'id' => 'rtg_create_date2']); ?>
	<?= $form1->hiddenField($model, 'strTags', ['class' => '', 'id' => 'strTags']); ?>
	<?= $form1->hiddenField($model, 'category', ['class' => '', 'id' => 'category']); ?>


	<input type="hidden" id="export" name="export" value="true"/>
	<div class="col-xs-12 text-left "> 
		<?php echo CHtml::submitButton('Export', array('class' => 'btn btn-default')); ?>
	</div>
	<?php $this->endWidget(); ?>
</div>
<div class="col-xs-12">
	<?php
	if (!empty($dataProvider))
	{
		$params									 = array_filter($_REQUEST);
		$dataProvider->getPagination()->params	 = $params;
		$dataProvider->getSort()->params		 = $params;
		$this->widget('booster.widgets.TbGridView', array(
			'id'				 => 'ratingListGrid',
			'responsiveTable'	 => true,
			'dataProvider'		 => $dataProvider,
			'template'			 => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
			'itemsCssClass'		 => 'table table-striped table-bordered mb0',
			'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
			//    'ajaxType' => 'POST',
			'columns'			 => array(
				array('name'	 => 'rtg_booking_id', 'type'	 => 'raw', 'value'	 => function ($data) {

						echo CHtml::link($data[rtg_booking_id], Yii::app()->createUrl("admin/booking/view", ["id" => $data[rtg_booking_id]]), ["class" => "viewBooking", 'target' => '_blank']);
						echo ($data['agent_name'] != '') ? "<br>" . $data['agent_name'] : '';
					}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'Booking ID'),
				array('name'	 => 'vendorName', 'value'	 => function ($data) {
						echo ($data['rtg_booking_id'] != '') ? $data['vendor_name'] : '';
					}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'Vendor Name'),
				array('name'	 => 'driverName', 'value'	 => function ($data) {
						echo ($data['rtg_booking_id'] != '') ? $data['driver_name'] : '';
					}, 'sortable'								 => true, 'htmlOptions'							 => array('class' => 'text-center'), 'headerHtmlOptions'						 => array('class' => 'text-center'), 'header'								 => 'Driver Name'),
				array('name'				 => 'rtg_customer_overall',
					'value'				 => '$data[rtg_customer_overall]',
					'sortable'			 => true,
					'htmlOptions'		 => array('class' => 'text-center'),
					'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'Customer Overall Rating'),
				array('name'				 => 'rtg_customer_driver',
					'value'				 => '$data[rtg_customer_driver]',
					'sortable'			 => true,
					'htmlOptions'		 => array('class' => 'text-center'),
					'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'Customer Driver Rating'),
				array('name'				 => 'rtg_customer_csr',
					'value'				 => '$data[rtg_customer_csr]',
					'sortable'			 => true,
					'htmlOptions'		 => array('class' => 'text-center'),
					'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'Customer Csr Rating'),
				array('name'				 => 'rtg_customer_car',
					'value'				 => '$data[rtg_customer_car]',
					'sortable'			 => true,
					'htmlOptions'		 => array('class' => 'text-center'),
					'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'Customer Car Rating'),
				array('name'				 => 'rtg_customer_review',
					'value'				 => '$data[rtg_customer_review]',
					'sortable'			 => true,
					'htmlOptions'		 => array('class' => 'text-center', 'style' => 'min-width:350px'),
					'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'Customer Review'),
				array('name'	 => 'reviewDesc', 'value'	 => function ($data) {
						echo ($data['rtg_review_desc']);
					}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'Review Desc'),
				array('name'				 => 'rtg_vendor_customer',
					'value'				 => '$data[rtg_vendor_customer]',
					'sortable'			 => true,
					'htmlOptions'		 => array('class' => 'text-center'),
					'headerHtmlOptions'	 => array('class' => 'text-center'),
					'header'			 => 'Vendor Customer'),
				array('name'				 => 'rtg_vendor_csr',
					'value'				 => '$data[rtg_vendor_csr]',
					'sortable'			 => true,
					'htmlOptions'		 => array('class' => 'text-center'),
					'headerHtmlOptions'	 => array('class' => 'text-center'),
					'header'			 => 'Vendor Csr'),
				array('name'				 => 'rtg_vendor_review',
					'value'				 => '$data[rtg_vendor_review]',
					'sortable'			 => true,
					'htmlOptions'		 => array('class' => 'text-center'),
					'headerHtmlOptions'	 => array('class' => 'text-center'),
					'header'			 => 'Vendor Review'),
				array('name'	 => 'rtg_customer_date', 'value'	 => function ($data) {
						if ($data['rtg_customer_date'] != '')
						{
							echo DateTimeFormat::DateTimeToLocale($data['rtg_customer_date']);
						}
						else
						{
							echo '';
						}
					}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'Rating Datetime'),
				array(
					'header'			 => 'Action',
					'class'				 => 'CButtonColumn',
					'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
					'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
					'template'			 => '{replied_customer}{reply_customer}{replied_vendor}{reply_vendor}{rtg_inactive}{rtg_active}',
					'buttons'			 => array(
						'replied_customer'	 => array(
							'url'		 => '',
							'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\rating_list\replied_customer.png',
							'visible'	 => '$data[rtg_customer_reply_status]==1?true:false;',
							'label'		 => '<i class="fa fa-check"></i>',
							'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'class' => 'btn btn-xs p0 repliedCostomer', 'title' => 'Replied Customer'),
						),
						'reply_customer'	 => array(
							'click'		 => 'function(){
                                                        $href = $(this).attr(\'href\');
                                                        jQuery.ajax({type: \'GET\',
                                                        url: $href,
                                                        success: function (data)
                                                        {

                                                            var box = bootbox.dialog({
                                                                message: data,
                                                                title: \'Reply Customer\',
                                                                onEscape: function () {

                                                                    // user pressed escape
                                                                }
                                                            });
                                                        }
                                                    });
                                                    return false;
                                                    }',
							'url'		 => 'Yii::app()->createUrl("admin/rating/replycustomerpage", array("id" => $data[rtg_id]))',
							'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\rating_list\reply_customer.png',
							'visible'	 => '$data[rtg_customer_reply_status]==1?false:true;',
							'label'		 => '<i class="fa fa-reply"></i>',
							'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'class' => 'btn btn-xs p0 replyCustomer', 'title' => 'Reply Customer'),
						),
						'replied_vendor'	 => array(
							'url'		 => '',
							'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\rating_list\replied_vendor.png',
							'visible'	 => '$data[rtg_vendor_reply_status]==1?true:false;',
							'label'		 => '<i class="fa fa-check"></i>',
							'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'class' => 'btn btn-xs p0 repliedVendor', 'title' => 'Replied Vendor'),
						),
						'reply_vendor'		 => array(
							'click'		 => 'function(){
                                                        $href = $(this).attr(\'href\');
                                                        jQuery.ajax({type: \'GET\',
                                                        url: $href,
                                                        success: function (data)
                                                        {

                                                            var box = bootbox.dialog({
                                                                message: data,
                                                                title: \'Reply Vendor\',
                                                                onEscape: function () {

                                                                    // user pressed escape
                                                                }
                                                            });
                                                        }
                                                    });
                                                    return false;
                                                    }',
							'url'		 => 'Yii::app()->createUrl("admin/rating/replyvendorpage", array("id" => $data[rtg_id]))',
							'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\rating_list\reply_vendor.png',
							'visible'	 => '$data[rtg_vendor_reply_status]==1?false:true;',
							'label'		 => '<i class="fa fa-reply"></i>',
							'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'class' => 'btn btn-xs p0 replyVendor', 'title' => 'Reply Vendor'),
						),
						'rtg_active'		 => array(
							'click'		 => 'function(e){
                                                            var con = confirm("Do you want to turn off display for this rating?"); 
                                                            if(con){
                                                                $href = $(this).attr(\'href\');
                                                                $.ajax({
                                                                    url: $href,
                                                                    dataType: "json",
                                                                    className:"bootbox-sm",
                                                                    title:"Inactive Rating",
                                                                    success: function(result)
                                                                    {
                                                                        if(result.success)
                                                                        {
                                                                            refreshRatingGrid();
                                                                        }
                                                                        else
                                                                        {
                                                                            alert(\'Sorry error occured\');
                                                                        }
                                                                    },
                                                                    error: function(xhr, status, error){
                                                                        alert(\'Sorry error occured\');
                                                                    }
                                                                });
                                                            }
                                                            return false;    
                                                         }',
							'url'		 => 'Yii::app()->createUrl("aaohome/rating/changestatus", array("rtg_id" => $data[rtg_id],"rtg_active"=>1))',
							'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\vendor\active.png',
							'visible'	 => '($data[rtg_active]==1)',
							'label'		 => '<i class="fa fa-toggle-on"></i>',
							'options'	 => array('data-toggle'	 => 'ajaxModal',
								'id'			 => 'rtgActive',
								'style'			 => '',
								'rel'			 => 'popover',
								'data-placement' => 'left',
								'class'			 => 'btn btn-xs rtg_active p0',
								'title'			 => 'Tap to turn OFF display')
						),
						'rtg_inactive'		 => array(
							'click'		 => 'function(){
                                                     var con = confirm("Do you want to turn ON display for this rating?"); 
                                                        if(con){
                                                            $href = $(this).attr(\'href\');
                                                            $.ajax({
                                                                url: $href,
                                                                dataType: "json",
                                                                className:"bootbox-sm",
                                                                title:"Active Rating",
                                                                success: function(result)
                                                                {
                                                                    if(result.success)
                                                                    {
                                                                        refreshRatingGrid();
                                                                    }
                                                                    else
                                                                    {
                                                                        alert(\'Sorry error occured\');
                                                                    }
                                                                },
                                                                error: function(xhr, status, error){
                                                                    alert(\'Sorry error occured\');
                                                                }
                                                            });
                                                        }
                                                        return false;
                                                    }',
							'url'		 => 'Yii::app()->createUrl("aaohome/rating/changestatus", array("rtg_id" => $data[rtg_id],"rtg_active"=>0))',
							'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\vendor\inactive.png',
							'visible'	 => '($data[rtg_active]==0)',
							'label'		 => '<i class="fa fa-toggle-off"></i>',
							'options'	 => array('data-toggle'	 => 'ajaxModal',
								'id'			 => 'rtgInactive',
								'style'			 => '',
								'rel'			 => 'popover',
								'data-placement' => 'left',
								'class'			 => 'btn btn-xs rtg_inactive p0',
								'title'			 => 'Tap to turn ON display'),
						),
						'htmlOptions'		 => array('class' => 'center'),
					))
		)));
	}
	?>
</div>
</div>
<?php
$version = Yii::app()->params['customJsVersion'];
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/custom.js?v=' . $version, CClientScript::POS_HEAD);
?>
<script>
	function refreshRatingGrid()
	{
		$('#ratingListGrid').yiiGridView('update');
	}

	var start = '<?= date('d/m/Y', strtotime('-10 DAYS')); ?>';
	var end = '<?= date('d/m/Y'); ?>';
	$('#rtgCreateDate').daterangepicker(
			{
				locale: {
					format: 'DD/MM/YYYY',
					cancelLabel: 'Clear'
				},
				"showDropdowns": true,
				"alwaysShowCalendars": true,
				startDate: start,
				endDate: end,
				ranges: {
					'Today': [moment(), moment()],
					'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
					'Tommorow': [moment().add(1, 'days'), moment().add(1, 'days')],
					'Last 7 Days': [moment().subtract(6, 'days'), moment()],
					'Next 7 Days': [moment(), moment().add(6, 'days')],
					'Last 30 Days': [moment().subtract(29, 'days'), moment()],
					'This Month': [moment().startOf('month'), moment().endOf('month')],
					'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
				}
			}, function (start1, end1) {
		$('.createDate1').val(start1.format('YYYY-MM-DD'));
		$('.createDate2').val(end1.format('YYYY-MM-DD'));
		$('#rtgCreateDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
	});
	$('#rtgCreateDate').on('cancel.daterangepicker', function (ev, picker) {
		$('#rtgCreateDate span').html('Select Pickup Date Range');
		$('.createDate1').val('');
		$('.createDate2').val('');
	});
</script>