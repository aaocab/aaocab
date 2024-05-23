<?php

if (!empty($dataProvider))
{
	$this->widget('booster.widgets.TbGridView', array(
		'responsiveTable'	 => true,
		'dataProvider'		 => $dataProvider,
		'selectableRows'	 => 2,
		'id'				 => 'driverListGrid1',
		'template'			 => "<div class='panel-heading'><div class='row m0'>
            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
            </div></div>
            <div class='panel-body'>{items}</div>
            <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
		'itemsCssClass'		 => 'table table-striped table-bordered mb0',
		'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
		//'ajaxType' => 'POST',
		'columns'			 => array( 
			 array('name'	 => 'drv_name',
				// 'value' => '$data->drv_name', 
				'value'	 => function ($data)
				{
					$drvName = $data["drv_name"];
					if ($data["ctt_first_name"] != "" && $data["ctt_last_name"] != "")
					{
						$drvName = $data["ctt_first_name"] . $data["ctt_last_name"];
					}
					else if ($data["ctt_business_name"] != "")
					{
						$drvName = $data["ctt_business_name"];
					}

					echo CHtml::link($drvName, Yii::app()->createUrl("admin/driver/view", ["id" => $data['drv_id']]), ["class" => "", "onclick" => "return viewDetail(this)"]) . "<br>";
					if ($data['drv_is_name_dl_matched'] == 2)
					{
						echo ' <span class="label label-danger ">DL Mismatch</span><br><br>';
					}
					if ($data['drv_is_name_pan_matched'] == 2)
					{
						echo ' <span class="label label-danger ">Pan Mismatch</span><br>';
					}
					echo ($data['drv_code'] != '') ? '<b>' . $data['drv_code'] . "</b><br>" : '';
					if ($data['drv_approved'] == 1)
					{
						echo ' <span class="label label-info ">Approved</span>';
					}

					if ($data['drv_is_freeze'] == 1)
					{
						echo ' <span class="label label-danger ">Block</span>';
					}
					$icon = '<img src="/images/icon/eye.png"  style="cursor:pointer ;height:16px; width:16px;" title="Value">';
					echo CHtml::link($icon, Yii::app()->createUrl("admin/driver/profile", ["id" => $data['drv_id']]), ["class" => "", "onclick" => "", 'target' => '_blank']);
				}
				, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'htmlOptions'		 => array('style' => 'word-break: break-all;min-width:90px'), 'header'			 => 'Name'),
			array('name'	 => 'drv_phone',
				'filter' => CHtml::activeTextField($drvmodel, 'drv_phone', array('class' => 'form-control', 'placeholder' => 'Search by ' . $drvmodel->getAttributeLabel('drv_phone'))),
				'value'	 => '$data["drv_phone"]',
				'value'	 => function ($data)
				{
					echo CHtml::link("Show Contact", Yii::app()->createUrl("admin/contact/view", ["ctt_id" => $data['drv_contact_id'], 'viewType' => 'driver']), ["class" => "", "onclick" => "return viewContactDriver(this)"]);
				},
				'sortable'	 => true, 'header'	 => $drvmodel->getAttributeLabel('drv_phone')),
			array('name' => 'drv_email', 'value' => '$data[drv_email]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Email'),
			array('name'	 => 'R4Ascore', 'value'	 => function($data)
				{
					echo ($data['R4Ascore'] > 0) ? CHtml::link($data['R4Ascore'], Yii::app()->createUrl('admin/driver/view', array('id' => $data['drv_id'])), array('target' => '_blank')) : 'NA';
				}, 'sortable'			 => true,
				'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'),
				'htmlOptions'		 => array('class' => 'text-center'),
				'header'			 => 'R4A score(Ready for Approval Score)'),
			array('name' => 'drv_licence_path', 'value' => '$data[drv_licence_path]!=""?"Yes":"No"', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Licence Proof'),
			array('name'	 => 'drv_lic_exp_date', 'value'	 => function ($data)
				{
					$rdate = '';
					if ($data['drv_lic_exp_date'] != '')
					{
						$rdate = DateTimeFormat::DateToLocale($data['drv_lic_exp_date']);
					}
					return $rdate;
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Licence Expiry'),
			array('name'	 => 'drv_issue_auth',
				'value'	 => function($data)
				{

					echo States::model()->getNameById($data['drv_issue_auth']);
				},
				'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Licence Issue Auth'),
			array('name'	 => 'drv_issue_date',
				'value'	 => function ($data)
				{
					if ($data['drv_issue_date'] != '')
					{
						return DateTimeFormat::DateTimeToLocale($data['drv_issue_date']);
					}
					return '';
				},
				'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Licence Issue date'),
			array('name'	 => 'drv_doj',
				'value'	 => function ($data)
				{
					if ($data['drv_doj'] != '')
					{
						return DateTimeFormat::DateToLocale($data['drv_doj']);
					}
					return '';
				},
				'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Joining Date'),
			array('name'	 => 'drv_created',
				'value'	 => function ($data)
				{
					return DateTimeFormat::DateTimeToLocale($data['drv_created']);
				},
				'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Added On'),
			//  array('name' => 'usr_city', 'value' => '$data->vhc', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Assigned vehicles'),
			array('name' => 'drv_mark_driver_count', 'value' => '$data[drv_mark_driver_count]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Bad Count'),
			array('name'	 => 'drs_last_trip_date',
				'value'	 => function ($data)
				{
					if ($data['drs_last_trip_date'] != NULL)
					{
						echo DateTimeFormat::DateTimeToLocale($data['drs_last_trip_date']);
					}
					else
					{
						echo '';
					}
				},
				'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Last Trip'),
			array('name'	 => 'drs_last_logged_in',
				'value'	 => function ($data)
				{
					if ($data['drs_last_logged_in'] != NULL)
					{
						echo DateTimeFormat::DateTimeToLocale($data['drs_last_logged_in']);
					}
					else
					{
						echo '';
					}
				},
				'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Last Logged In'),
			array('name'	 => 'total_trips', 'value'	 => function ($data)
				{
					$totalTrips = ($data['total_trips'] > 0 ) ? $data['total_trips'] : 0;
					return $totalTrips;
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Total Trips'),
			array(
				'header'			 => 'Action',
				'class'				 => 'CButtonColumn',
				'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
				'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
				'template'			 => '{add}{docview}{view}{edit}{delete}<br>{detail}{markedbadlist}{resetmarkedbad}{log}{driverFreeze}{driverUnfreeze}{linkuser}',
				'buttons'			 => array(
					'merge'			 => array(
						'click'		 => 'function(e){
                                    try
                                        {
                                            $href = $(this).attr("href");
                                            jQuery.ajax({type:"GET","dataType":"html",url:$href,success:function(data)
                                            {
                                                var mergebox=bootbox.dialog({ 
                                                message: data, 
                                                className:"bootbox-lg",
                                                title:"Merge Drivers",
                                                size: "large",
                                                callback: function(){   
                                                },
                                                onEscape: function(){                                                
                                                   $(mergebox).modal("hide");
                                                },
                                            });
                                            }}); 
                                            }
                                            catch(e)
                                            { alert(e); }
                                            return false;
                                         }',
						'url'		 => 'Yii::app()->createUrl("admin/driver/merge", array("drv_id"=>$data[drv_id]))',
						'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\driver\merge.png',
						'label'		 => '<i class="fa fa-credit-card"></i>',
						'options'	 => array('data-toggle' => 'ajaxModal', 'id' => 'example2', 'style' => '', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs merge p0', 'title' => 'Merge Driver'),
					),
					'add'			 => array(
						'click'		 => 'function(e){
                                    try
                                        {
                                            $href = $(this).attr("href");
                                            jQuery.ajax({type:"GET","dataType":"html",url:$href,success:function(data)
                                            {
                                                bootbox.dialog({ 
                                                message: data, 
                                                title:"Add Transaction",
                                                size: "large",
                                                className:"bootbox-lg",    
                                                callback: function(){   }
                                            });
                                            }}); 
                                            }
                                            catch(e)
                                            { alert(e); }
                                            return false;
                                         }',
						'url'		 => 'Yii::app()->createUrl("admin/driver/addtransaction", array("id"=>$data[drv_id]))',
						'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\driver\add_transaction.png',
						'label'		 => '<i class="fa fa-file-text-o"></i>',
						'options'	 => array('data-toggle'	 => 'ajaxModal',
							'id'			 => 'example',
							'style'			 => '',
							'rel'			 => 'popover',
							'data-placement' => 'left',
							'class'			 => 'btn btn-xs jobDetail53 p0',
							'title'			 => 'Add Transaction'),
					),
					'docview'		 => array(
						'url'		 => 'Yii::app()->createUrl("admin/document/view", array(\'ctt_id\' => $data[drv_contact_id],\'viewType\' =>"driver"))',
						'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\uploads.png',
						'label'		 => '<i class="fa fa-email"></i>',
						'options'	 => array('target' => '_blank', 'style' => '', 'class' => 'btn btn-xs p0', 'title' => 'Document Upload'),
					),
					'view'			 => array(
						'click'		 => 'function(e){
                                    try
                                        {
                                            $href = $(this).attr("href");
                                            jQuery.ajax({type:"GET","dataType":"html",url:$href,success:function(data)
                                            {
                                                bootbox.dialog({ 
                                                message: data, 
                                                title:"View Transaction",
                                                size: "large",
                                                className:"bootbox-lg",    
                                                callback: function(){   }
                                            });
                                            }}); 
                                            }
                                            catch(e)
                                            { alert(e); }
                                            return false;
                                         }',
						'url'		 => 'Yii::app()->createUrl("admin/driver/viewtransaction", array("id"=>$data[drv_id]))',
						'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\driver\show_transactions.png',
						'label'		 => '<i class="fa fa-file-text-o"></i>',
						'options'	 => array('data-toggle'	 => 'ajaxModal',
							'id'			 => 'example',
							'style'			 => '',
							'rel'			 => 'popover',
							'data-placement' => 'left',
							'class'			 => 'btn btn-xs jobDetail5 p0',
							'title'			 => 'View Transaction'),
					),
					'linkuser'		 => array(
						'click'		 => 'function(){
                                                                    $href = $(this).attr(\'href\');
                                                                    $.ajax({
                                                                        url: $href,
                                                                        dataType: "html",
                                                                        success: function(data){
                                                                               var linkuserbootbox1 = bootbox.dialog({ 
                                                                                   message: data,  
                                                                                   title:"Link User",
                                                                                   size: "large",
                                                                                   callback: function(){   }
                                                                               });
                                                                                linkuserbootbox1.on("hidden.bs.modal", function () { $(this).data("bs.modal", null); });
                                                                        },
                                                                        error: function(xhr, status, error){
                                                                                alert(\'Sorry error occured\');
                                                                        }
                                                                    });
                                                            
                                                                    return false;
                                                    }',
						'url'		 => 'Yii::app()->createUrl("admpnl/driver/linkuser", array("drvId" => $data[drv_id]))',
						'label'		 => '<i class="fa fa-users"></i>',
						'options'	 => array('data-toggle' => 'ajaxModal', 'style' => 'padding: 4px ;margin-left: 4px', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs linkUser', 'title' => 'Link User')
					),
					'delete'		 => array(
						'click'		 => 'function(){
                                            var con = confirm("Are you sure you want to delete this Driver?");
                                            return con;
                                        }',
						'url'		 => 'Yii::app()->createUrl("admin/driver/del", array(\'drvid\' => $data[drv_id]))',
						'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\driver\customer_cancel.png',
						'label'		 => '<i class="fa fa-remove"></i>',
						'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'class' => 'btn btn-xs conDelete p0', 'title' => 'Delete Driver'),
					),
					'edit'			 => array(
						'url'		 => 'Yii::app()->createUrl("admin/driver/add", array(\'drvid\' => $data[drv_id]))',
						'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\driver\edit_booking.png',
						'label'		 => '<i class="fa fa-edit"></i>',
						'options'	 => array('style' => '', 'class' => 'btn btn-xs edit p0', 'title' => 'Edit Driver'),
					),
					'markedbadlist'	 => array(
						'click'		 => 'function(e){
                                    try
                                        {
                                            $href = $(this).attr("href");
                                            jQuery.ajax({type:"GET","dataType":"html",url:$href,success:function(data)
                                            {
                                                bootbox.dialog({ 
                                                message: data, 
                                                className:"bootbox-lg",
                                                title:"Mark Bad Drivers",
                                                size: "large",
                                                callback: function(){   }
                                            });
                                            }}); 
                                            }
                                            catch(e)
                                            { alert(e); }
                                            return false;
                                         }',
						'url'		 => 'Yii::app()->createUrl("admin/driver/markedbadlist", array("drv_id"=>$data[drv_id]))',
						'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\driver\bad_driver_1.png',
						'label'		 => '<i class="fa fa-credit-card"></i>',
						'options'	 => array('data-toggle' => 'ajaxModal', 'id' => 'example2', 'style' => '', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs markBad p0', 'title' => 'Marked Bad Driver'),
					),
					'detail'		 => array(
						'click'		 => 'function(e){
                                    try
                                        {
                                            $href = $(this).attr("href");
                                            jQuery.ajax({type:"GET","dataType":"html",url:$href,success:function(data)
                                            {
                                                bootbox.dialog({ 
                                                message: data, 
                                                title:"Driver Details",
                                                size: "large",
                                                className:"bootbox-lg",    
                                                callback: function(){   }
                                            });
                                            }}); 
                                            }
                                            catch(e)
                                            { alert(e); }
                                            return false;
                                         }',
						'url'		 => 'Yii::app()->createUrl("admin/driver/view", array("id"=>$data[drv_id]))',
						'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\driver\show_details.png',
						'label'		 => '<i class="fa fa-file-text-o"></i>',
						'options'	 => array('data-toggle'	 => 'ajaxModal',
							'id'			 => 'example',
							'style'			 => '',
							'rel'			 => 'popover',
							'data-placement' => 'left',
							'class'			 => 'btn btn-xs jobDetail p0',
							'title'			 => 'Show Details'),
					),
					'resetmarkedbad' => array(
						'click'		 => 'function(){
                                    $href = $(this).attr(\'href\');
                                    jQuery.ajax({type: \'GET\',
                                    url: $href,
                                    success: function (data){
                                        bootbox.dialog({
                                            message: data,
                                            title: \'Reset Bad Count For Driver\',
                                            onEscape: function () {

                                                // user pressed escape
                                            }
                                        });
                                    }
                                });
                                    return false;
                                    }',
						'url'		 => 'Yii::app()->createUrl("admin/driver/resetmarkedbad", array("refId" =>$data[drv_id]))',
						'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\driver\reset_marked_bad_driver.png',
						'visible'	 => '($data[drv_mark_driver_count]>0)',
						'label'		 => '<i class="fa fa-refresh"></i>',
						'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'class' => 'btn btn-xs resetMarkBad p0', 'title' => 'Reset Marked Bad Driver'),
					),
					'driverFreeze'	 => array(
						'click'		 => 'function(e){                                                        
                                    try
                                    {
                                        $href = $(this).attr("href");
                                        jQuery.ajax({type:"GET",url:$href,success:function(data)
                                        {
                                            bootbox.dialog({ 
                                            message: data, 
                                            className:"bootbox-sm",
                                            title:"UnBlock Driver",
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
						'url'		 => 'Yii::app()->createUrl("admpnl/driver/freeze", array("drv_id" => $data[drv_id],"drv_is_freeze"=>$data[drv_is_freeze]))',
						'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\inactive.png',
						'visible'	 => '($data[drv_is_freeze] == 1)',
						'label'		 => '<i class="fa fa-toggle-on"></i>',
						'options'	 => array('data-toggle' => 'ajaxModal', 'id' => 'admFreeze', 'style' => '', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs driverFreeze p0', 'title' => 'UnBlock Driver')
					),
					'driverUnfreeze' => array(
						'click'		 => 'function(e){                                                        
                                    try
                                    {
                                        $href = $(this).attr("href");
                                        jQuery.ajax({type:"GET",url:$href,success:function(data)
                                        {
                                            bootbox.dialog({ 
                                            message: data, 
                                            className:"bootbox-sm",
                                            title:"Block Driver",
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
						'url'		 => 'Yii::app()->createUrl("admpnl/driver/freeze", array("drv_id" => $data[drv_id],"drv_is_freeze"=>$data[drv_is_freeze]))',
						'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\active.png',
						'visible'	 => '($data[drv_is_freeze] == 0)',
						'label'		 => '<i class="fa fa-toggle-on"></i>',
						'options'	 => array('data-toggle' => 'ajaxModal', 'id' => 'admFreeze', 'style' => '', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs driverUnfreeze p0', 'title' => 'Block Driver')
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
                                                    title: \'Driver Log\',
                                                    size: \'large\',
                                                    onEscape: function () {

                                                        // user pressed escape
                                                    }
                                                });
                                            }
                                        });
                                    return false;
                                }',
						'url'		 => 'Yii::app()->createUrl("admin/driver/showlog", array("drvId" => $data[drv_id]))',
						'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\vendor\show_log.png',
						'label'		 => '<i class="fa fa-list"></i>',
						'options'	 => array('data-toggle'	 => 'ajaxModal',
							'style'			 => '',
							'class'			 => 'btn btn-xs conshowlog p0',
							'title'			 => 'Show Log'),
					),
					'htmlOptions'	 => array('class' => 'center'),
				)
			)
		)
			)
	);
}
?>