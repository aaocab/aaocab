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
</style>
<div class="row m0">
    <div class="col-xs-12">
        <div class="text-right">
        </div>    
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row text-center h3">
                </div>
                <div class="row"> 
					<?php
					if (!empty($dataProvider))
					{
						$this->widget('booster.widgets.TbGridView', array(
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
								array('name' => 'prm_code', 'filter' => CHtml::activeTelField($model, 'prm_code', array('class' => 'form-control', 'placeholder' => 'Search')), 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Promo Code'),
								array('name' => 'prm_desc', 'filter' => CHtml::activeTelField($model, 'prm_desc', array('class' => 'form-control', 'placeholder' => 'Search')), 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Promo Description'),
								array('name' => 'pcn_min_cash', 'filter' => false, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Minimum Cash Amount'),
								array('name' => 'pcn_max_cash', 'filter' => false, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Maximum Cash Amount'),
								array('name' => 'pcn_value_cash', 'filter' => false, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Promo Cash Value'),
								array('name' => 'pcn_min_coins', 'filter' => false, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Minimum Coins Amount'),
								array('name' => 'pcn_max_coins', 'filter' => false, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Maximum Coins Amount'),
								array('name' => 'pcn_value_coins', 'filter' => false, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Promo Coins Value'),
								array('name' => 'prm_valid_from', 'filter' => false, 'value' => 'date("d/m/Y H:i:s",strtotime($data["prm_valid_from"]))', 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Valid From'),
								array('name' => 'prm_valid_upto', 'filter' => false, 'value' => 'date("d/m/Y H:i:s",strtotime($data["prm_valid_upto"]))', 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Valid Upto'),
								array('name'	 => 'prm_validity', 'filter' => CHtml::activeCheckBoxList($model, 'prm_validity', array('0' => 'Active','1' => 'Expired')),
									'value'	 => function($data)
									{
										if ($data["prm_valid_upto"] < date('Y-m-d H:i:s'))
										{
											$val = 'Expired';
										}
										else
										{
											$val = 'Active';
										}
										return $val;
									},
									'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Validity'),
								array('name' => 'prm_applicable_platform', 'filter' => CHtml::activeDropDownList($model, 'prm_applicable_platform', array('0' => 'All', '2' => 'Admin', '3' => 'App', '1' => 'User'), array('class' => 'form-control', 'placeholder' => 'Search')), 'value' => 'Promos::model()->getApplicableSources1($data["prm_applicable_platform"])', 'headerHtmlOptions' => array('style' => 'min-width: 100px'), 'header' => 'Source Type'),
								array('name'	 => 'prm_use_max', 'filter' => false,
									'value'	 => function($data)
									{
										$useMax = $data["prm_use_max"];
										if ($useMax == 0)
										{
											$useMax = "Unlimited";
										}
										else
										{
											$useMax = $data["prm_use_max"];
										}
										return $useMax;
									},
									'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Max Use'),
								array('name' => 'prm_used_counter', 'filter' => false, 'value' => '$data["prm_used_counter"]', 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Already Used'),
								array('name'	 => 'prm_applicable_type', 'filter' => CHtml::activeCheckBoxList($model, 'prm_applicable_type', array('1' => 'Auto', '0' => 'Manual')),
									'value'	 => function($data)
									{
										$appType = $data["prm_applicable_type"];
										if ($appType == 0)
										{
											$appType = "Manual Apply";
										}
										else
										{
											$appType = "Auto Apply";
										}
										return $appType;
									},
									'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Applicable Type'),
								array('name'	 => 'prm_applicable_user', 'filter' => CHtml::activeCheckBoxList($model, 'prm_applicable_user', array('0' => 'All', '1' => 'Particular')),
									'value'	 => function($data)
									{
										$appUserType = $data["prm_applicable_user"];
										if ($appUserType == 0)
										{
											$appUserType = "All User";
										}
										else
										{
											$appUserType = "Particular User";
										}
										return $appUserType;
									},
									'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Applicable User Type'),
								array('name'	 => 'prm_active', 'filter' => CHtml::activeCheckBoxList($model, 'prm_active', array('1' => 'Active', '0' => 'Deleted')),
									'value'	 => function($data)
									{
										$status = $data["prm_active"];
										if ($status == 0)
										{
											$status = "Deleted";
										}
										else
										{
											$status = "Active";
										}
										return $status;
									},
									'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Status'),
								array('name' => 'prm_modified', 'filter' => false, 'value' => 'date("d/m/Y H:i:s",strtotime($data["prm_modified"]))', 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Modified At'),
								array('name' => 'prm_created', 'filter' => false, 'value' => 'date("d/m/Y H:i:s",strtotime($data["prm_created"]))', 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Created At'),
								array(
									'header'			 => 'Action',
									'class'				 => 'CButtonColumn',
									'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
									'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
									'template'			 => '{view}{edit}{linkuser}{delete}{selectuser}',
									'buttons'			 => array(
										'edit'			 => array(
											'url'		 => 'Yii::app()->createUrl("admin/promos/add", array(\'promoid\' => $data["prm_id"]))',
											'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\promo_list\edit_booking.png',
											'label'		 => '<i class="fa fa-edit"></i>',
											'options'	 => array('style' => '', 'class' => 'btn btn-xs ignoreJob p0', 'title' => 'Edit'),
										),
										'view'			 => array(
											'url'		 => 'Yii::app()->createUrl("admin/promos/view", array(\'promoid\' => $data["prm_id"]))',
											'imageUrl'	 => Yii::app()->request->baseUrl . '\images\view.gif',
											'label'		 => '<i class="fa fa-edit"></i>',
											'options'	 => array('style' => '', 'class' => 'btn btn-xs ignoreJob p0', 'title' => 'View'),
										),
										'linkuser'		 => array(
											'url'		 => 'Yii::app()->createUrl("admin/user/list", array(\'promoId\' => $data["prm_id"]))',
											'imageUrl'	 => Yii::app()->request->baseUrl . '/images/icon/change_follow_up.png',
											'visible'    =>'($data["prm_applicable_user"]==1)?true:false',
											'label'		 => '<i class="fa fa-edit"></i>',
											'options'	 => array('style' => '', 'class' => 'btn btn-xs ignoreJob p0', 'title' => 'Link User'),
										),
										'delete'		 => array(
											'click'		 => 'function(){
                                                        var con = confirm("Are you sure you want to delete this promo?");
                                                        return con;
                                                    }',
											'url'		 => 'Yii::app()->createUrl("admin/promos/delpromo", array(\'pid\' => $data["prm_id"]))',
											'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\promo_list\customer_cancel.png',
											'label'		 => '<i class="fa fa-remove"></i>',
											'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'class' => 'btn btn-xs condelete p0', 'title' => 'Delete'),
										),
										'selectuser'		 => array(
											'visible'=>'(Yii::app()->request->getParam("ctt_id")==null) ? true : false',
						         'click'		 => 'function(e){
                                    try
                                        {
                                            $href = $(this).attr("href");
                                            jQuery.ajax({type:"GET","dataType":"html",url:$href,success:function(data)
                                            {
                                                var mergebox=bootbox.dialog({ 
                                                message: data, 
                                                className:"bootbox-lg",
                                                title:"Gift Card User",
                                                size: "large",
                                               
                                                onEscape: function(){                                                
                                                     location.reload();
                                                },
                                            });
                                            }}); 
                                            }
                                            catch(e)
                                            { alert(e); }
                                            return false;
                                         }',
											'url'		 => 'Yii::app()->createUrl("admin/promos/giftcarduser", array(\'promoId\' => $data["prm_id"]))',
											'imageUrl'	 => Yii::app()->request->baseUrl . '/images/icon/giftCard.png',
											'label'		 => '<i class="fa fa-edit"></i>',
											'visible'    =>'($data["prm_user_type"]==2)?true:false',
											'options'	 => array('data-toggle' => 'ajaxModal','style' => '', 'class' => 'btn btn-xs ignoreGiftUserView p0',  'title' => 'Gift Card User'),
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