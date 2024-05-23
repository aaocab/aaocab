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
                                           <div class='panel-body table-responsive'>{items}</div>
                                           <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
							'itemsCssClass'		 => 'table table-striped table-bordered dataTable mb0',
							'htmlOptions'		 => array('class' => 'panel panel-primary  compact'),
							'columns'			 => array(
								array('name' => 'prm_desc', 'filter' => CHtml::activeTelField($model, 'prm_desc', array('class' => 'form-control', 'placeholder' => 'Search')), 'value' => '$data->prm_desc', 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Promo Description'),
								array('name'	 => 'prm_value_type', 'filter' => CHtml::activeCheckBoxList($model, 'prm_value_type', array('1' => 'Percentage', '2' => 'Amount')),
									'value'	 => function($data) {
										$valueType = $data["prm_value_type"];
										if ($valueType == 1)
										{
											$valueType = "Percentage";
										}
										else
										{
											$valueType = "Amount";
										}
										return $valueType;
									},
									'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Value Type'),
								array('name'	 => 'prm_code', 'filter' => CHtml::activeTelField($model, 'prm_code', array('class' => 'form-control', 'placeholder' => 'Search')), 'value'	 => function($data) {
										echo $data->prm_code;
									}, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Promo Code'),
								array('name'	 => 'prm_min', 'filter' => CHtml::activeTelField($model, 'prm_min', array('class' => 'form-control', 'placeholder' => 'Search')), 'value'	 => function($data) {
										echo '<i class="fa fa-inr"></i>' . $data->prm_min;
									}, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Minimum Amount'),
								array('name'	 => 'prm_max', 'filter' => CHtml::activeTelField($model, 'prm_max', array('class' => 'form-control', 'placeholder' => 'Search')), 'value'	 => function($data) {
										echo '<i class="fa fa-inr"></i>' . $data->prm_max;
									}, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Maximum Amount'),
								array('name'	 => 'prm_value', 'filter' => CHtml::activeTelField($model, 'prm_value', array('class' => 'form-control', 'placeholder' => 'Search')), 'value'	 => function($data) {
										echo '<i class="fa fa-inr"></i>' . $data->prm_value;
									}, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Promo Value'),
								array('name' => 'prm_valid_from', 'filter' => false, 'value' => 'date("d/m/Y H:i:s",strtotime($data->prm_valid_from))', 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Valid From'),
								array('name' => 'prm_valid_upto', 'filter' => false, 'value' => 'date("d/m/Y H:i:s",strtotime($data->prm_valid_upto))', 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Valid Upto'),
								array('name'	 => 'prm_validity', 'filter' => CHtml::activeCheckBoxList($model, 'prm_validity', array('1' => 'Show Expired')),
									'value'	 => function($data) {
										if ($data->prm_valid_upto < date('Y-m-d H:i:s'))
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
								array('name' => 'prm_source_type', 'filter' => CHtml::activeDropDownList($model, 'prm_source_type', array('0' => 'All', '2' => 'Admin', '3' => 'App', '1' => 'User'), array('class' => 'form-control', 'placeholder' => 'Search')), 'value' => '$data->getApplicableSources()', 'headerHtmlOptions' => array('style' => 'min-width: 100px'), 'header' => 'Source Type'),
								array('name'	 => 'prm_use_max', 'filter' => false,
									'value'	 => function($data) {
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
								array('name' => 'prm_used_counter', 'filter' => false, 'value' => '$data->prm_used_counter', 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Already Used'),
								array('name'	 => 'prm_applicable_type', 'filter' => CHtml::activeCheckBoxList($model, 'prm_applicable_type', array('1' => 'Auto', '0' => 'Manual')),
									'value'	 => function($data) {
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
								array('name'	 => 'prm_applicable_user_type', 'filter' => CHtml::activeCheckBoxList($model, 'prm_applicable_user_type', array('0' => 'All', '1' => 'Particular')),
									'value'	 => function($data) {
										$appUserType = $data["prm_applicable_user_type"];
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
								array('name'	 => 'prm_applicable_trip_type', 'filter' => CHtml::activeCheckBoxList($model, 'prm_applicable_trip_type', array('0' => 'All', '1' => 'Particular')),
									'value'	 => function($data) {
										$appTripType = $data["prm_applicable_trip_type"];
										if ($appTripType == 0)
										{
											$appTripType = "All Trip";
										}
										else
										{
											$appTripType = "Particular Trip";
										}
										return $appTripType;
									},
									'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Applicable Trip Type'),
								array('name'	 => 'prm_next_trip_apply', 'filter' => CHtml::activeCheckBoxList($model, 'prm_next_trip_apply', array('0' => 'No', '1' => 'Yes')),
									'value'	 => function($data) {
										$nextTrip = $data["prm_next_trip_apply"];
										if ($nextTrip == 0)
										{
											$nextTrip = "No";
										}
										else
										{
											$nextTrip = "Yes";
										}
										return $nextTrip;
									},
									'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Next Trip Apply'),
								array('name'	 => 'prm_active', 'filter' => CHtml::activeCheckBoxList($model, 'prm_active', array('1' => 'Active', '0' => 'Deleted')),
									'value'	 => function($data) {
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
								array('name' => 'prm_modified', 'filter' => false, 'value' => 'date("d/m/Y H:i:s",strtotime($data->prm_modified))', 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Modified At'),
								array('name' => 'prm_created', 'filter' => false, 'value' => 'date("d/m/Y H:i:s",strtotime($data->prm_created))', 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Created At'),
								array(
									'header'			 => 'Action',
									'class'				 => 'CButtonColumn',
									'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
									'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
									'template'			 => '{edit}{delete}',
									'buttons'			 => array(
										'edit'			 => array(
											'url'		 => 'Yii::app()->createUrl("admin/promo/add", array(\'promoid\' => $data->prm_id))',
											'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\promo_list\edit_booking.png',
											'label'		 => '<i class="fa fa-edit"></i>',
											'options'	 => array('style' => '', 'class' => 'btn btn-xs ignoreJob p0', 'title' => 'Edit'),
										),
										'delete'		 => array(
											'click'		 => 'function(){
                                                        var con = confirm("Are you sure you want to delete this promo?");
                                                        return con;
                                                    }',
											'url'		 => 'Yii::app()->createUrl("admin/promo/delpromo", array(\'pid\' => $data->prm_id))',
											'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\promo_list\customer_cancel.png',
											'label'		 => '<i class="fa fa-remove"></i>',
											'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'class' => 'btn btn-xs condelete p0', 'title' => 'Delete'),
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