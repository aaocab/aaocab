
<?php

if ($dataProvider != "")
{
	$this->widget('booster.widgets.TbGridView', [
		'id'				 => 'credits-grid',
		'dataProvider'		 => $dataProvider,
		'responsiveTable'	 => true,
		'filter'			 => $model,
		'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
		'itemsCssClass'		 => 'table table-striped table-bordered mb0',
		'template'			 => "<div class='panel-heading'><div class='row m0'>
            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
            </div></div>
            <div class='panel-body'>{items}</div>
            <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
		'columns'			 => [
			['name' => 'user_name', 'value' => '$data->ucrUsers->usr_name', 'headerHtmlOptions' => ['class' => 'col-xs-2']],
			array('name'	 => 'user_mobile',
				'value'	 => function ($data) {
					if ($data->ucrUsers->usr_mobile != '')
					{
						return '+' . $data->ucrUsers->usr_country_code . $data->ucrUsers->usr_mobile;
					}
				},
				'headerHtmlOptions'	 => array('class' => 'col-xs-2')),
			['name' => 'user_email', 'value' => '$data->ucrUsers->usr_email', 'headerHtmlOptions' => ['class' => 'col-xs-2']],
			['name' => 'ucr_value', 'value' => '$data->ucr_value', 'headerHtmlOptions' => ['class' => 'col-xs-1'], 'htmlOptions' => ['style' => 'text-align:center']],
			['name' => 'ucr_desc', 'headerHtmlOptions' => ['class' => 'col-xs-2']],
			['name' => 'ucr_type', 'value' => '$data->getTypes($data->ucr_type)', 'headerHtmlOptions' => ['class' => 'col-xs-1']],
			['name'	 => 'ucr_ref_id', 'value'	 => function($data) {
					switch ($data->ucr_type)
					{
						case 1:
							//echo $data->ucrPromo->prm_code;
							echo $data->ucrBooking->bkg_booking_id;
							break;
						case 2:
							//echo $data->ucrRefund->trans_code;
							echo $data->ucrBooking->bkg_booking_id;
							break;
						case 3:
							echo $data->ucrReferral->usr_name . " " . $data->ucrReferral->usr_lname;
							break;
						case 5:
							echo $data->ucrAdmin->adm_fname . " " . $data->ucrAdmin->adm_lname;
							break;
						case 4:
							echo $data->ucrBooking->bkg_booking_id;
							break;
						case 6:
							echo $data->ucrReferral->usr_name . " " . $data->ucrReferral->usr_lname;
							break;
						default :
							echo 'NA';
					}
				}, 'headerHtmlOptions' => ['class' => 'col-xs-1']],
			['name' => 'ucr_used', 'headerHtmlOptions' => ['class' => 'col-xs-1'], 'filter' => false, 'htmlOptions' => ['style' => 'text-align:center']],
			['name' => 'ucr_validity', 'headerHtmlOptions' => ['class' => 'col-xs-1'], 'filter' => false],
			['name' => 'ucr_max_use', 'headerHtmlOptions' => ['class' => 'col-xs-1'], 'filter' => false, 'htmlOptions' => ['style' => 'text-align:center']],
			['name' => 'ucr_created', 'headerHtmlOptions' => ['class' => 'col-xs-1'], 'filter' => false],
			['class'				 => 'CButtonColumn', 'header'			 => 'Action', 'template'			 => '{activate}{deactivate}', 'headerHtmlOptions'	 => ['class' => 'col-xs-2'],
				'buttons'			 => [
					'detail'	 => [
						'label'		 => '<i class="fa fa-file-text-o"></i>',
						'options'	 => ['style' => 'margin-right: 8px', 'class' => 'btn btn-xs btn-primary credit-detail', 'title' => 'show details']
					],
					'activate'	 => [
						'click'		 => 'function(){
                                             $href = $(this).attr(\'href\');
                                            bootbox.confirm("Are you sure to activate Gozo Coins for this user?",function(result){
                                            if(result){                                                     
                                                            $.ajax({
                                                                url: $href,
                                                                dataType: "json",
                                                                success: function(result){
                                                                        if(result.success){
                                                                                $(\'#credits-grid\').yiiGridView(\'update\');
                                                                        }else{
                                                                                alert(\'Sorry error occured\');
                                                                        }

                                                                },
                                                                error: function(xhr, status, error){
                                                                        alert(\'Sorry error occured\');
                                                                }
                                                            });                                                                  
                                                }                                            
                                            });
                                            return false;
                                  }',
						'url'		 => 'Yii::app()->createUrl("aaohome/credit/deactivate", array("id" => $data->ucr_id,"status"=>1))',
						'label'		 => '<i class="fa fa-toggle-off"></i>',
						'visible'	 => '$data->ucr_status==2?true:false',
						'options'	 => ['style' => 'margin-right: 8px', 'class' => 'btn btn-xs btn-danger credit-detail', 'title' => 'Activate']
					],
					'deactivate' => [
						'click'		 => 'function(){
                                             $href = $(this).attr(\'href\');
                                            bootbox.confirm("Are you sure to deactivate Gozo Coins for this user?",function(result){
                                            if(result){                                                     
                                                            $.ajax({
                                                                url: $href,
                                                                dataType: "json",
                                                                success: function(result){
                                                                        if(result.success){
                                                                                $(\'#credits-grid\').yiiGridView(\'update\');
                                                                        }else{
                                                                                alert(\'Sorry error occured\');
                                                                        }

                                                                },
                                                                error: function(xhr, status, error){
                                                                        alert(\'Sorry error occured\');
                                                                }
                                                            });                                                                  
                                                }                                            
                                            });
                                            return false;
                                  }',
						'url'		 => 'Yii::app()->createUrl("aaohome/credit/deactivate", array("id" => $data->ucr_id,"status"=>2))',
						'label'		 => '<i class="fa fa-toggle-on"></i>',
						'visible'	 => '$data->ucr_status==1?true:false',
						'options'	 => ['style' => 'margin-right: 8px', 'class' => 'btn btn-xs btn-success credit-deactivate', 'title' => 'Deactivate']
					]
				]
			]
		]
	]);
}
?>


