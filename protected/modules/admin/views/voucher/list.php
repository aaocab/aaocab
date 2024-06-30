<style>
    .panel-body{
        padding-top: 0 ;
        padding-bottom: 0;
    }
    .table>tbody>tr>th
    {
        vertical-align: middle
    }

    .table>tbody>tr>td, .table>tbody>tr>th{
        padding: 7px;
        line-height: 1.5em;
    }
	.select2-container.select2-container--default.select2-container--open  
	{
		z-index: 5000;
	}	
</style>
<div class="row m0">
    <div class="col-xs-12">
        <div class="text-right">		
        </div>    
        <div class="panel panel-default">
            <div class="panel-body">
				<a class="btn btn-primary mb10" href="/aaohome/voucher/add" style="text-decoration: none">Add new</a>
                <div class="row text-center h3">				
                </div>	
                <div class="row"> 
					<?php
					if (!empty($dataProvider))
					{
						$this->widget('booster.widgets.TbGridView', array(
							'id'				 => 'voucherListGrid',
							'responsiveTable'	 => true,
							'filter'			 => $model,
							'dataProvider'		 => $dataProvider,
							'template'			 => "<div class='panel-heading'><div class='row m0'>
                                           <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                           </div></div>
                                           <div class='panel-body table-responsive' style='max-width: 100% !important;overflow-x: scroll;'>{items}</div>
                                           <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
							'itemsCssClass'		 => 'table table-striped table-bordered dataTable mb0',
							'htmlOptions'		 => array('class' => 'panel panel-primary  compact'),
							'columns'			 => array(
								array('name' => 'vch_code', 'filter' => CHtml::activeTelField($model, 'vch_code', array('class' => 'form-control', 'placeholder' => 'Search')), 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Voucher Code'),
								array('name' => 'vch_title', 'filter' => CHtml::activeTelField($model, 'vch_title', array('class' => 'form-control', 'placeholder' => 'Search')), 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Voucher Title'),								
								array('name'	 => 'vch_type', 'filter' => CHtml::activeCheckBoxList($model, 'vch_type', array('1' => 'Promo', '2' => 'Wallet')),
									'value'	 => function($data)
									{
										$type = $data["vch_type"];
										if ($type == 1)
										{
											$type = "Promo";
										}
										else if ($type == 2)
										{
											$type = "Wallet";
										}										
										else
										{
											$type = "";
										}
										return $type;
									},									
									'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Type'),
									array('name' => 'vch_title', 'filter' => false, 'value' => $data['vch_title'], 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Description'),
									array('name' => 'vch_selling_price', 'filter' => false, 'value' => $data['vch_selling_price'], 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Selling Price'),
									array('name' => 'vch_max_allowed_limit', 'filter' => false, 'value' => $data['vch_max_allowed_limit	'], 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Max Allowed Limit'),
									array(
									'header'			 => 'Action',
									'class'				 => 'CButtonColumn',
									'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
									'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
									'template'			 => '{view}{edit}{addpartner}{listpartner}{disable}{enable}',
									'buttons'			 => array(
										'edit'			 => array(
											'url'		 => 'Yii::app()->createUrl("admin/voucher/add", array(\'voucherid\' => $data["vch_id"]))',
											'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\promo_list\edit_booking.png',
											'label'		 => '<i class="fa fa-edit"></i>',
											'options'	 => array('style' => '', 'class' => 'btn btn-xs ignoreJob p0', 'title' => 'Edit Voucher'),
										),										
										'view'		 => array(
											'click'		 => 'function(e){
														try
															{
																$href = $(this).attr("href");
																jQuery.ajax({type:"GET","dataType":"html",url:$href,success:function(data)
																{
																	bootbox.dialog({ 
																	message: data, 
																	title:"Add Partner",
																	size: "large",
																	className:"bootbox-lg",    
																	callback: function(){  alert("fff"); }
																});
																}}); 
																}
																catch(e)
																{ alert(e); }
																return false;
															 }',
											'url'		 => 'Yii::app()->createUrl("admin/voucher/view", array("voucherid"=>$data[vch_id]))',
											'imageUrl'	 => Yii::app()->request->baseUrl . '\images\view.gif',
											'label'		 => '<i class="fa fa-file-text-o"></i>',
											'options'	 => array('data-toggle'	 => 'ajaxModal',
												'id'			 => 'example',
												'style'			 => '',
												'rel'			 => 'popover',
												'data-placement' => 'left',
												'class'			 => 'btn btn-xs jobDetail5 p0',
												'title'			 => 'View Voucher Details'),
										), 
										'addpartner'		 => array(
											'click'		 => 'function(e){
														try
															{
																$href = $(this).attr("href");
																	jQuery.ajax({
																		type:"GET",
																		"dataType":"html",
																		url:$href,
																		success:function(data)
																		{
																			
																			bootbox.dialog({ 
																				message: data, 
																				title:"Add Partner To Voucher",
																				size: "large",
																				className:"bootbox-lg9",    
																				callback: function(){}
																			});																			
																			jQuery(".bootbox").removeAttr("tabindex");
																		}
																	}); 
																}
																catch(e)
																{ alert(e); }
																return false;
															 }',
											'url'		 => 'Yii::app()->createUrl("admin/voucher/addpartner", array("voucherid"=>$data[vch_id]))',
											'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\assign_vendor.png',
											'visible'	 => '($data[vch_is_all_partner] == 0 )',
											'label'		 => '<i class="fa fa-file-text-o"></i>',
											'options'	 => array('data-toggle'	 => 'ajaxModal',
												'id'			 => 'example8',
												'style'			 => '',
												'rel'			 => 'popover',
												'data-placement' => 'left',												
												'class'			 => 'btn btn-xs jobDetail6 p0',
												'title'			 => 'Add Partner To Voucher'),
										),										
										
										'listpartner'		 => array(
											'click'		 => 'function(e){
														try
															{
																$href = $(this).attr("href");
																	jQuery.ajax({
																		type:"GET",
																		"dataType":"html",
																		url:$href,
																		success:function(data)
																		{
																			
																			bootbox.dialog({ 
																				message: data, 
																				title:"Partner List",
																				size: "large",
																				className:"bootbox-lg9",    
																				callback: function(){}
																			});
																		}
																	}); 
																}
																catch(e)
																{ alert(e); }
																return false;
															 }',
											'url'		 => 'Yii::app()->createUrl("admin/voucher/listpartner", array("voucherid"=>$data[vch_id]))',
											'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\affiliates.png',
											'visible'	 => '($data[vch_is_all_partner] == 0 )',
											'label'		 => '<i class="fa fa-file-text-o"></i>',
											'options'	 => array('data-toggle'	 => 'ajaxModal',
												'id'			 => 'example9',
												'style'			 => '',
												'rel'			 => 'popover',
												'data-placement' => 'left',												
												'class'			 => 'btn btn-xs jobDetail7 p0',
												'title'			 => "Partner List"
												),
										),
										'disable'			 => array(
											'click'		 => 'function(){
											  var con = confirm("Are you sure you want to disable this voucher?"); 
											  if(con){
												$href = $(this).attr("href");												
												$.ajax({
													url: $href,
													success: function(result){
														if(result == "true"){
															$("#voucherListGrid").yiiGridView("update");
														}else{
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
											'url'		 => 'Yii::app()->createUrl("admin/voucher/changestatus", array("activateid"=>$data["vch_id"]))',
											'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\route_list\active.png',
											'visible'	 => '($data[vch_active] == 1 )',
											'label'		 => '<i class="fa fa-toggle-off"></i>',
											'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'class' => 'btn btn-xs conDelete p0', 'title' => 'Disable'),
															),
										'enable'			 => array(
											'click'		 => 'function(){
											var con = confirm("Are you sure you want to enable this voucher?");
											if(con){
											$href = $(this).attr("href");
											$.ajax({
												url: $href,
												success: function(result){
													if(result == "true"){
														$("#voucherListGrid").yiiGridView("update");
													}else{
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
										'url'		 => 'Yii::app()->createUrl("admin/voucher/changestatus", array("disableid" => $data["vch_id"]))',
										'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\route_list\inactive.png',
										'visible'	 => '($data[vch_active] == 2 )',
										'label'		 => '<i class="fa fa-toggle-on"></i>',
										'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'class' => 'btn btn-xs conÉnable p0', 'title' => 'Enable'),				
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
<script type="text/javascript">
    $(document).ready(function () {
        $('.bootbox').removeAttr('tabindex');
    });	
</script>