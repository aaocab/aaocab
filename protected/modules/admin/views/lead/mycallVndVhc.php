<?php
$pageno			 = filter_input(INPUT_GET, 'page');
$vtypeList		 = VehicleTypes::model()->getVehicleTypeList1();
//$vendorListJson = Vendors::model()->getJSON();
$vtypeListJson	 = VehicleTypes::model()->getJSON($vtypeList);
$fueltype		 = VehicleTypes::model()->getFuelType();
$carType		 = VehicleTypes::model()->getCarType();
$carTypeJson	 = VehicleTypes::model()->getJSON($carType);

$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
?>
<div class="row">
     

    <div class="col-xs-12">
		<?php
		if (!empty($dataProvider))
		{
			$dataProvider->getPagination()->params	 = array_filter($_GET + $_POST);
			$dataProvider->getSort()->params		 = array_filter($_GET + $_POST);

			$this->widget('booster.widgets.TbGridView', array(
				'responsiveTable'	 => true,
				// 'filter' => $model1,
				'dataProvider'		 => $dataProvider,
				'id'				 => 'vehicleListGrid',
				'template'			 => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
				'itemsCssClass'		 => 'table table-striped table-bordered mb0',
				'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
				//    'ajaxType' => 'POST',
				'columns'			 => array(
//					array('name'				 => 'vnd_name',
//						'value'				 => '$data[vnd_name]', 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Vendor'),
					array('name'	 => 'vhc_number',
						//'value' => '$data->vhc_number',                         
						'value'	 => function ($data) {
							echo CHtml::link($data["vhc_number"], Yii::app()->createUrl("admin/vehicle/view", ["id" => $data['vhc_id']]), ["class" => "", "onclick" => "return viewDetail(this)"]) . "<br>";
							echo ($data['vhc_code'] != '') ? "<b>" . $data['vhc_code'] . "</b><br>" : '';
							if ($data['vhc_approved'] == 1)
							{
								echo ' <span class="label label-info ">Approved</span>';
							}
							if ($data['vhc_is_freeze'] == 1)
							{
								echo ' <span class="label label-danger ">Frozen</span>';
							}
							if ($data['vhs_boost_enabled'] == 1)
							{
								echo ' <span class="label label-success ">Boost Enabled</span>';
							}
						}
						, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'htmlOptions'		 => array('style' => 'word-break: break-all;min-width:80px'), 'header'			 => 'Number'),
					array('name'	 => 'model',
						'value'	 => function ($data) {
							return $data['vht_make'] . " " . $data['vht_model'];
						}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Model'),
					array('name'				 => 'year',
						'value'				 => '$data[vhc_year]',
						'sortable'			 => false,
						'headerHtmlOptions'	 => array(),
						'header'			 => 'Year'),
					array('name'				 => 'color',
						'value'				 => '$data[vhc_color]',
						'sortable'			 => true,
						'headerHtmlOptions'	 => array(),
						'header'			 => 'Color'),
					array('name'				 => 'vht_capacity',
						'value'				 => '$data[vht_capacity]',
						'sortable'			 => true,
						'headerHtmlOptions'	 => array(),
						'header'			 => 'Capacity'),
					array('name'				 => 'cartype',
						'type'				 => 'raw',
//						'value'	 => function($data) {
//							$ct = VehicleTypes::model()->getCarType();
//							echo $ct[$data['vht_car_type']];
//						},
						'value'				 => '$data[vct_label]',
						'sortable'			 => false,
						'headerHtmlOptions'	 => array(),
						'header'			 => 'Car Type'),
					array('name'	 => 'docScore',
						'value'	 => function ($data) {
							echo ($data["docScore"] > 0) ? CHtml::link($data["docScore"], Yii::app()->createUrl("admin/vehicle/view", ["id" => $data['vhc_id']]), ["class" => "", "onclick" => "return viewDetail(this)"]) . "<br>" : 'NA';
						},
						'sortable'			 => true,
						'headerHtmlOptions'	 => array(),
						'htmlOptions'		 => array('style' => 'text-align: center'),
						'header'			 => 'R4A(Ready for Approval) Score'),
					array('name'	 => 'vhc_tax_exp_date',
						'value'	 => function ($data) {
							if ($data['vhc_tax_exp_date'] != '')
							{

								echo DateTimeFormat::DateToLocale($data['vhc_tax_exp_date']);
							}
						}, 'sortable'			 => false, 'headerHtmlOptions'	 => array(), 'header'			 => 'Tax Expiry Date'),
//					array('name'	 => 'vhc_dop', 'value'	 => function ($data) {
//							if ($data->vhc_dop != '')
//							{
//								echo DateTimeFormat::DateTimeToDatePicker($data['vhc_dop']);
//							}
//						}, 'sortable'			 => false, 'headerHtmlOptions'	 => array(), 'header'			 => 'Date of Purchase'),
					// array('name' => 'driver', 'value' => '$data->drv_names', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Driver'),
					array('name' => 'vhc_mark_car_count', 'value' => '$data[vhc_mark_car_count]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Bad count'),
					//	array('name'	 => 'vhc_insurance_proof',
//						'value'	 => function($data) {
//							echo $data['vhc_insurance_proof_st'];
//						},
//						'sortable'			 => true,
//						'headerHtmlOptions'	 => array(),
//						'header'			 => 'Insurance Proof'),
					array('name'	 => 'vhc_insurance_exp_date', 'value'	 => function ($data) {
							if ($data['vhc_insurance_exp_date'] != '')
							{
								echo DateTimeFormat::DateToLocale($data['vhc_insurance_exp_date']);
							}
						}, 'sortable'			 => false, 'headerHtmlOptions'	 => array(), 'header'			 => 'Insurance Expiry Date'),
//					array('name'	 => 'vhc_reg_certificate',
//						'value'	 => function($data) {
//							echo $data['vhc_reg_certificate_st'];
//						},
//						'sortable'			 => true,
//						'headerHtmlOptions'	 => array(),
//						'header'			 => 'Registration Certificate'),
					array('name'	 => 'vhc_reg_exp_date', 'value'	 => function ($data) {
							if ($data['vhc_reg_exp_date'] != '')
							{
								echo DateTimeFormat::DateToLocale($data['vhc_reg_exp_date']);
							}
						}, 'sortable'			 => false,
						'headerHtmlOptions'	 => array(),
						'header'			 => 'Registration Expiry Date'),
					array('name'	 => 'totaltrips', 'value'	 => function ($data) {
							echo ($data['totaltrips'] > 0) ? $data['totaltrips'] : 0;
						}, 'sortable'			 => false, 'headerHtmlOptions'	 => array(), 'header'			 => 'Total Trips'),
					array('name'	 => 'vhs_last_trip_date', 'value'	 => function ($data) {
							if ($data['vhs_last_trip_date'] != '')
							{
								echo DateTimeFormat::DateToLocale($data['vhs_last_trip_date']);
							}
						}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Last Trip Date'),
					// array('name' => 'vhc_approved', 'value' => '($data->vhc_approved==1?Yes:No)', 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Approved'),
					// array('name' => 'vhc_is_freeze', 'value' => '(($data->vhc_is_freeze==1)?Yes:No)', 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Freeze'),
					array(
						'header'			 => 'Action',
						'class'				 => 'CButtonColumn',
						'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
						'template'			 => '{approvedoc}{edit}{delete}{markedbadlist}{resetmarkedbad}<br>{log}{uberapprove}{uberunapprove}{vehicleFreeze}{vehicleUnfreeze}{detail} {updateVehicleDetails}',
						'buttons'			 => array(
							'approvedoc'			 => array(
								'url'		 => 'Yii::app()->createUrl("admin/vehicle/docapprovallist", array("cabid"=>$data[vhc_id]))',
								'visible'	 => '($data[vhc_approved]!=1)',
								'imageUrl'	 => Yii::app()->request->baseUrl . '\images\approve.png',
								'label'		 => '<i class="fa fa-check"></i>',
								'options'	 => array('class'	 => 'btn   ignoreJob1 p0', 'target' => '_blank', 'style'	 => 'margin-right: 2px',
									'title'	 => 'Show and approve doc'),
							),
							'edit'					 => array(
								'url'		 => 'Yii::app()->createUrl("admin/vehicle/add", array(\'veditid\' => $data[vhc_id]))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\cab\edit_booking.png',
								'label'		 => '<i class="fa fa-edit"></i>',
								'options'	 => array('style' => '', 'class' => 'btn btn-xs ignoreJob p0', 'title' => 'Edit'),
							),
							'delete'				 => array(
								'click'		 => 'function(){
                                    var con = confirm("Are you sure you want to delete this vehicle?");
                                    return con;
                                }',
								'url'		 => 'Yii::app()->createUrl("admin/vehicle/delvehicle", array(\'vid\' => $data[vhc_id]))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\cab\customer_cancel.png',
								'label'		 => '<i class="fa fa-remove"></i>',
								'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'class' => 'btn btn-xs condelete p0', 'title' => 'Delete'),
							),
							'markedbadlist'			 => array(
								'click'		 => 'function(e){
                            try
                                {
                                $href = $(this).attr("href");
                                jQuery.ajax({type:"GET","dataType":"html",url:$href,success:function(data)
                                {
                                    bootbox.dialog({ 
                                    message: data, 
                                    className:"bootbox-lg",
                                    title:"Mark Bad Vehicle",
                                    size: "large",
                                    callback: function(){   }
                                });
                                }}); 
                                }
                                catch(e)
                                { alert(e); }
                                return false;
                             }',
								'url'		 => 'Yii::app()->createUrl("admin/vehicle/markedbadlist", array("vhc_id"=>$data[vhc_id]))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\cab\bad_car1.png',
								'label'		 => '<i class="fa fa-credit-card"></i>',
								'options'	 => array('data-toggle' => 'ajaxModal', 'id' => 'example2', 'style' => '', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs markBad p0', 'title' => 'Marked Bad Vehicle'),
							),
							'resetmarkedbad'		 => array(
								'click'		 => 'function(){
                                    $href = $(this).attr(\'href\');
                                    jQuery.ajax({type: \'GET\',
                                    url: $href,
                                    success: function (data){
                                        bootbox.dialog({
                                            message: data,
                                            title: \'Reset Bad Count For Vehicle\',
                                            onEscape: function () {

                                                // user pressed escape
                                            }
                                        });
                                    }
                                });
                                return false;
                                }',
								'url'		 => 'Yii::app()->createUrl("admin/vehicle/resetmarkedbad", array("refId" =>$data[vhc_id]))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\cab\reset_marked_bad_vehicle.png',
								'visible'	 => '($data[vhc_mark_car_count]>0)',
								'label'		 => '<i class="fa fa-refresh"></i>',
								'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'class' => 'btn btn-xs resetBad p0', 'title' => 'Reset Marked Bad Vehicle'),
							),
							'updateVehicleDetails'	 => array(
								'click'		 => 'function(){  
									 href = $(this).attr("href");
                                        $.ajax({
										"type": "GET",
										"url": href,
										"success": function (data)
										{
											data = JSON.parse(data);
											if(data.success)
											{
                                                bootbox.alert(data.message, function(){ 
													window.location.reload(true);
												});
											}
											else
											{
												bootbox.alert(data.message);
											}
										}
									});
									return false;

                                }',
								'url'		 => 'Yii::app()->createUrl("admpnl/vehicle/updatedetails", array("vhc_id" =>$data[vhc_id]))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '\images\approve.png',
								'label'		 => '<i class="fa fa-check"></i>',
								'options'	 => array('data-toggle' => 'ajaxModal', 'id' => 'vehicleUpdateDetails', 'style' => '', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs vehicleUpdateDetails p0', 'title' => 'Update Statistical Data ')
							),
							'uberapprove'			 => array(
								'click'		 => 'function(){  
									 href = $(this).attr("href");
                                        $.ajax({
										"type": "GET",
										"dataType":"json",
										"url": href,
										"success": function (data)
										{
											if(data.success)
											{
												alert("Uber Approved Successfully");
												window.location.reload(true);
											}
											else
											{
												alert(data.error);
											}
										}
									});
									return false;

                                }',
								'url'		 => 'Yii::app()->createUrl("admpnl/vehicle/uberapprove", array("vhc_id" =>$data[vhc_id],"vhc_is_uber_approved"=>1))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\inactive.png',
								'visible'	 => '($data[vhc_is_uber_approved] == 0)',
								'label'		 => '<i class="fa fa-toggle-on"></i>',
								'options'	 => array('data-toggle' => 'ajaxModal', 'id' => 'vehicleUberApprove', 'style' => '', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs vehicleUberApprove p0', 'title' => 'Uber Unapprove')
							),
							'uberunapprove'			 => array(
								'click'		 => 'function(){                                                        
                                        href = $(this).attr("href");
                                        $.ajax({
										"type": "GET",
										"dataType":"json",
										"url": href,
										"success": function (data)
										{
											if(data.success)
											{
												alert("Uber Unpproved Successfully");
												window.location.reload(true);
											}
											else
											{
												alert(data.error);
											}
										}
									});
									return false;

                                }',
								'url'		 => 'Yii::app()->createUrl("admpnl/vehicle/uberapprove", array("vhc_id" =>$data[vhc_id],"vhc_is_uber_approved"=>0))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\active.png',
								'visible'	 => '($data[vhc_is_uber_approved] == 1)',
								'label'		 => '<i class="fa fa-toggle-on"></i>',
								'options'	 => array('data-toggle' => 'ajaxModal', 'id' => 'vehicleUberUnapprove', 'style' => '', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs vehicleUberUnapprove p0', 'title' => 'Uber Approve')
							),
							'vehicleFreeze'			 => array(
								'click'		 => 'function(e){                                                        
                                    try
                                    {
                                        $href = $(this).attr("href");
                                        jQuery.ajax({type:"GET",url:$href,success:function(data)
                                        {
                                            bootbox.dialog({ 
                                            message: data, 
                                            className:"bootbox-sm",
                                            title:"UnFreeze Vehicle",
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
								'url'		 => 'Yii::app()->createUrl("admpnl/vehicle/freeze", array("vhc_id" =>$data[vhc_id],"vhc_is_freeze"=>$data[vhc_is_freeze]))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\inactive.png',
								'visible'	 => '($data[vhc_is_freeze] == 1)',
								'label'		 => '<i class="fa fa-toggle-on"></i>',
								'options'	 => array('data-toggle' => 'ajaxModal', 'id' => 'vehicleFreeze', 'style' => '', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs vehicleFreeze p0', 'title' => 'Unfreeze Vehicle')
							),
							'vehicleUnfreeze'		 => array(
								'click'		 => 'function(e){                                                        
                                    try
                                    {
                                        $href = $(this).attr("href");
                                        jQuery.ajax({type:"GET",url:$href,success:function(data)
                                        {
                                            bootbox.dialog({ 
                                            message: data, 
                                            className:"bootbox-sm",
                                            title:"Freeze Vehicle",
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
								'url'		 => 'Yii::app()->createUrl("admpnl/vehicle/freeze", array("vhc_id" =>$data[vhc_id],"vhc_is_freeze"=>$data[vhc_is_freeze]))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\active.png',
								'visible'	 => '($data[vhc_is_freeze] == 0)',
								'label'		 => '<i class="fa fa-toggle-on"></i>',
								'options'	 => array('data-toggle' => 'ajaxModal', 'id' => 'vehicleUnfreeze', 'style' => '', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs vehicleUnfreeze p0', 'title' => 'Freeze Vehicle')
							),
							'log'					 => array(
								'click'		 => 'function(){
                                            $href = $(this).attr(\'href\');
                                            jQuery.ajax({type: \'GET\',
                                            url: $href,
                                            success: function (data)
                                            {
                                                var box = bootbox.dialog({
                                                    message: data,
                                                    title: \'Vehicle Log\',
                                                    size: \'large\',
                                                    onEscape: function () {

                                                        // user pressed escape
                                                    }
                                                });
                                            }
                                        });
                                    return false;
                                }',
								'url'		 => 'Yii::app()->createUrl("admin/vehicle/showlog", array("vhcId" => $data[vhc_id]))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\vendor\show_log.png',
								'label'		 => '<i class="fa fa-list"></i>',
								'options'	 => array('data-toggle'	 => 'ajaxModal',
									'style'			 => '',
									'class'			 => 'btn btn-xs conshowlog p0',
									'title'			 => 'Show Log'),
							),
							'detail'				 => array(
								'click'		 => 'function(){
                                            $href = $(this).attr(\'href\');
                                            jQuery.ajax({type: \'GET\',
                                            url: $href,
                                            success: function (data)
                                            {
                                                var box = bootbox.dialog({
                                                    message: data,
                                                    title: \'Vehicle Details\',
                                                    size: \'large\',
                                                    onEscape: function () {

                                                        // user pressed escape
                                                    }
                                                });
                                            }
                                        });
                                    return false;
                                }',
								'url'		 => 'Yii::app()->createUrl("admin/vehicle/view", array("id" => $data[vhc_id]))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\driver\show_details.png',
								'label'		 => '<i class="fa fa-list"></i>',
								'options'	 => array('data-toggle'	 => 'ajaxModal',
									'style'			 => '',
									'class'			 => 'btn btn-xs detail p0',
									'title'			 => 'Car Details'),
							),
							'htmlOptions'			 => array('class' => 'center'),
						))
			)));
		}
		?>


    </div>
</div>
<script>
    function refreshVehicleGrid() {
        $('#vehicleListGrid').yiiGridView('update');
    }
</script>
<script>
    $(document).ready(function ()
    {
        var front_end_height = parseInt($(window).outerHeight(true));
        var footer_height = parseInt($("#footer").outerHeight(true));
        var header_height = parseInt($("#header").outerHeight(true));
        var ch = (front_end_height - (header_height + footer_height + 23));
        //console.log("wH: "+front_end_height+" HH : "+header_height+" FH: "+footer_height+"CH :"+ch);
        $("#content").attr("style", "height:" + ch + "px;");
    });

    function viewDetail(obj)
    {
        var href2 = $(obj).attr("href");
        $.ajax({
            "url": href2,
            "type": "GET",
            "dataType": "html",
            "success": function (data) {
                var box = bootbox.dialog({
                    message: data,
                    title: 'Vehicle Details',
                    size: 'large',
                    onEscape: function () {
                        // user pressed escape
                    },
                });
                if ($('body').hasClass("modal-open"))
                {
                    box.on('hidden.bs.modal', function (e) {
                        $('body').addClass('modal-open');
                    });
                }

            }
        });
        return false;
    }


    function confirmDelete() {
        if (confirm("Do you really want to delete this vehicle?")) {
            return true;
        } else {
            return false;
        }
    }
    function edit(obj)
    {
        var $drvid = $(obj).attr('drv_id');
        var href2 = '<?= Yii::app()->createUrl("admin/driver/add"); ?>';
        $.ajax({
            "url": href2,
            "type": "GET",
            "dataType": "json",
            "data": {"drvid": $drvid},
            "success": function (data) {
                alert(data);
            }
        });
    }
</script>