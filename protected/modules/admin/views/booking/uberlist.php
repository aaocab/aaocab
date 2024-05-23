
<script>
	 var cabBox;
</script>
<div class="col-xs-12">
	<?php
	$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
		'id'					 => 'booking-form', 'enableClientValidation' => true,
		'clientOptions'			 => array(
			'validateOnSubmit'	 => true,
			'errorCssClass'		 => 'has-error',
		),
		// Please note: When you enable ajax validation, make sure the corresponding
		// controller action is handling ajax validation correctly.
		// See class documentation of CActiveForm for details on this,
		// you need to use the performAjaxValidation()-method described there.
		'enableAjaxValidation'	 => false,
		'errorMessageCssClass'	 => 'help-block',
		'htmlOptions'			 => array('class' => '',),
	));
	/* @var $form TbActiveForm */
	?>

	<div class="col-xs-3">
		<label class="control-label">Select Status</label>

		<?=
		$form->select2Group($model, 'bkg_status', array('label'			 => '',
			'widgetOptions'	 => array('data' => [0 => 'All',2 => 'New',3 => 'Assigned',5 => 'Allocated'], 'options' => array('allowClear' => true), 'htmlOptions' => array('placeholder' => 'Select Status', 'class' => 'p0', 'style' => 'max-width: 100%'))));
		?>  
	</div>

	<div class="col-xs-12 text-center pb10">  
		<button class="btn btn-primary mt5" type="submit" style="width: 185px;"  name="bookingSearch">Search</button>
	</div>
	<?php $this->endWidget(); ?>
</div>
<div class="col-xs-12">
	<?php
	if (!empty($dataProvider))
	{
		$this->widget('booster.widgets.TbGridView', array(
			'responsiveTable'	 => true,
			'dataProvider'		 => $dataProvider,
			'responsiveTable'	 => true,
			'filter'			 => $model,
			'template'			 => "<div class='panel-heading'><div class='row m0'>
                                        <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                </div>
                                </div>
                                <div class='panel-body'>{items}</div>
                                <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
			'itemsCssClass'		 => 'table table-striped table-bordered mb0',
			'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
			//     'ajaxType' => 'POST',
			'columns'			 => array(
				array('name'	 => 'bkg_bcb_id',
					'value'	 => '$data["bkg_bcb_id"]','sortable'=> true, 'headerHtmlOptions'=> array(), 'header'=>'Trip Id', 'filter'=> false),
				array('name'	 => 'bkg_booking_id',
					'value'	 => function($data)
					{
						echo CHtml::link($data['bkg_booking_id'], Yii::app()->createUrl("admin/booking/view", ["id" => $data['bkg_id']]), ["class" => "viewBooking","target"=>"_BLANK", "onclick" => "return viewList(this)"]);
					},
					'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Booking Id', 'filter'			 => false),
				array('name' => 'bkg_user_name', 'value' => '$data["bkg_user_name"]." ".$data["bkg_user_lname"]', 'sortable' => true,'filter'=>false, 'headerHtmlOptions' => array(), 'header' => 'Name'),
				array('name' => 'bkg_contact_no', 'value' => '$data["bkg_contact_no"]', 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Phone', 'filter' => false),
				array('name' => 'bkg_user_email', 'value' => '$data["bkg_user_email"]', 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Email', 'filter' => false),
				array('name' => 'bkgFromCity.cty_name', 'value' => '$data["fromCities"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'From City'),
				array('name' => 'bkgToCity.cty_name', 'value' => '$data["toCities"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'To City'),
				array('name'	 => 'bkg_total_amount', 'value'	 => function($data)
					{
						echo '<i class="fa fa-inr"></i>' . round($data['bkg_total_amount']);
					}, 'sortable'			 => true,'filter'=>false, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'Amount'),
				array('name'	 => 'bkg_advance_amount', 'value'	 => function($data)
					{
						if ($data['bkg_advance_amount'] > 0)
						{
							echo '<i class="fa fa-inr"></i>' . round($data['bkg_advance_amount']);
						}
						else
						{
							echo '<i class="fa fa-inr"></i>' . '0';
						}
					}, 'sortable'			 => true,'filter'=>false, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'Advance Paid'),
				array('name'	 => 'bkg_create_date',
					'value'	 => function ($data)
					{
						return DateTimeFormat::DateTimeToLocale($data['bkg_create_date']);
					}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'Booking Date/Time', 'filter'			 => false),
				//   array('name' => 'bkg_pickup_address', 'value' => '$data->bkg_pickup_address', 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Pickup Address'),
				array('name'	 => 'bkg_pickup_date',
					'value'	 => function ($data)
					{
						return DateTimeFormat::DateTimeToLocale($data['bkg_pickup_date']);
					},
					'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'Pickup Date/Time', 'filter'			 => false),
				array('name' => 'bkg_vendor_name', 'value' => '$data["vendorName"]', 'sortable' => true,'filter'=>false, 'headerHtmlOptions' => array(), 'header' => 'Vendor Name'),
				array('name' => 'bkg_vehicle_type_id', 'value' => '$data["cabType"]', 'sortable' => false,'filter'=>false, 'headerHtmlOptions' => array(), 'header' => 'Cab Type'),
				array('name'	 => 'bkg_status',
					'value'	 => function ($data)
					{
						echo Booking::model()->getBookingStatus($data['bkg_status']);
					},
					'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Booking Status', 'filter'			 => false,'htmlOptions'		 => array('id' => 'bkgStat')),
				array(
					'header'			 => 'Action',
					'class'				 => 'CButtonColumn',
					'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
					'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
					'template'			 => '{assignVendor}{unassignVendor}{assignCabDriver}{unAssignCabDriverVendor}',
					'buttons'			 => array(
						'assignVendor'		 => array(
							'click'		 => 'function(){
																var self = this;
                                                                var href = $(self).attr(\'href\');
                                                                $.ajax({
                                                                       "type": "GET",
                                                                       "dataType": "html",
                                                                       "url": href,
                                                                       "success": function (data)
                                                                       {
                                                                           bootbox.dialog({
                                                                               message: data,
                                                                               className: "bootbox-xs",
                                                                               title: "Assign Vendor",
                                                                               size: "large",
                                                                               callback: function () {

                                                                               }
                                                                           }).on(\'shown.bs.modal\', function (e) {
																				$.each($(".uberAssignVendor"),function(key,value){
																					var href1 =$(value).attr(\'href\');
																					 href1 = href1+"&from_uberlist=1";
																					 $(value).attr(\'href\',href1);
																				 });           
                                                                           });
																		   
																		 
                                                                       }
                                                                   });
                                                                   return false;
                                                                 }',
							'url'		 => 'Yii::app()->createUrl("admin/booking/showvendor", array(\'booking_id\' => $data["bkg_id"]))',
							'imageUrl'	 => Yii::app()->request->baseUrl . '/images/icon/assign_vendor.png',
							'label'		 => '<i class="fa fa-edit"></i>',
							'visible'	 => '($data["bkg_status"]==2)',
							'options'	 => array('style' => '', 'class' => 'btn btn-xs assignVendor p0', 'title' => 'Assign Vendor'),
						),
						'unassignVendor'	 => array(
							'click'		 => 'function(){
															var href = $(this).attr(\'href\');
															$.ajax({
																   "type": "GET",
																   "dataType": "html",
																   "url": href,
																   "success": function (data)
																   {
																	   bootbox.dialog({
																		   message: data,
																		   className: "bootbox-xs",
																		   title: "Unassign Vendor",
																		   size: "large",
																		   callback: function () {

																		   }
																	   }).on(\'shown.bs.modal\', function (e) {		 
																						   $("#from_uberlist").attr(\'value\',1);
																	   });


																   }
															   });
															   return false;
															 }',
							'url'		 => 'Yii::app()->createUrl("admin/booking/canvendor", array(\'booking_id\' => $data["bkg_id"]))',
							'imageUrl'	 => Yii::app()->request->baseUrl . '/images/icon/unassign_vendor.png',
							'label'		 => '<i class="fa fa-edit"></i>',
							'visible'	 => '($data["bkg_status"]==3)',
							'options'	 => array('style' => '','class' => 'btn btn-xs unassignVendor p0', 'title' => 'Unassign Vendor'),
						),
						'assignCabDriver'	 => array(
							'click'		 => 'function(){
															var href = $(this).attr(\'href\');
															$.ajax({
																   "type": "GET",
																   "dataType": "html",
																   "url": href,
																   "success": function (data)
																   {
																	   cabBox = bootbox.dialog({
																		   message: data,
																		   className: "bootbox-xs",
																		   title: "Assign Cab And Driver",
																		   size: "large",
																		   callback: function () {

																		   }
																	   }).on(\'shown.bs.modal\', function (e) {
																			   var href1 = $("#vendors-register-form1").attr(\'action\')+"&from_uberlist=1";
																						   $("#vendors-register-form1").attr(\'action\',href1);
																	   });



																   }
															   });
															   return false;
															 }',
							'url'		 => 'Yii::app()->createUrl("admin/booking/assigncabdriver", array(\'booking_id\' => $data["bkg_id"]))',
							'imageUrl'	 => Yii::app()->request->baseUrl . '/images/icon/driver_details.png',
							'label'		 => '<i class="fa fa-edit"></i>',
							'visible'	 => '($data["bkg_status"]!=2)',
							'options'	 => array('style' => '','class' => 'btn btn-xs assignCabDriver p0', 'title' => 'Assign Cab & Driver', 'onmouseover' => 'cabDriverAssignOrChangeTitle(this)'),
						),
						'unAssignCabDriverVendor'	 => array(
							'click'		 => 'function(){
															var href = $(this).attr(\'href\');
															$.ajax({
																   "type": "GET",
																   "dataType": "html",
																   "url": href,
																   "success": function (data)
																   {
																	     bootbox.dialog({
																		   message: data,
																		   className: "bootbox-xs",
																		   title: "Vendor Cancel",
																		   size: "large",
																		   callback: function () {

																		   }
																	   }).on(\'shown.bs.modal\', function (e) {
																			     $("#from_uberlist").attr(\'value\',1);
																	   });


																   }
															   });
															   return false;
															 }',
							'url'		 => 'Yii::app()->createUrl("admin/booking/canvendor", array(\'booking_id\' => $data["bkg_id"]))',
							'imageUrl'	 => Yii::app()->request->baseUrl . '/images/icon/vendor_cancel.png',
							'label'		 => '<i class="fa fa-edit"></i>',
							'visible'	 => '($data["bkg_status"]==5)',
							'options'	 => array('style' => '','class' => 'btn btn-xs unAssignCabDriverVendor p0', 'title' => 'Vendor Cancel'),
						),
						'htmlOptions'		 => array('class' => 'center'),
					)),
		)));
	}
	?>
</div>
<script>
	function cabDriverAssignOrChangeTitle(obj)
	{
		if($(obj).parent().parent().find('#bkgStat').text().trim() == "Allocated")
		{
			$(obj).attr('title','Change Cab & Driver')
		}
	}

</script>