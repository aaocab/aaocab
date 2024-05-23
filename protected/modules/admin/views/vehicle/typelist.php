<div id="content">
    <div class="row">
        <div id="userView1">
            <div class=" col-xs-12 pt30">
                <div class="projects ">

                    <a class="btn btn-primary mb10 ml20" href="<?= Yii::app()->createUrl('admin/vehicle/addtype') ?>" style="text-decoration: none">Add new</a>
                    <div class="panel panel-default">
                        <div class="panel-body">
							<?
							if (!empty($dataProvider))
							{
								
								$this->widget('booster.widgets.TbGridView', array(
									'id'				 => 'vehicletype-grid',
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
									//    'ajaxType' => 'POST',
									'columns'			 => array(
										array('name' => 'vht_make', 'filter' => CHtml::activeTextField($model, 'vht_make', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->getAttributeLabel('vht_make'))), 'value' => '$data["vht_make"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Vehicle make'),
										array('name' => 'vht_model', 'filter' => CHtml::activeTextField($model, 'vht_model', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->getAttributeLabel('vht_model'))), 'value' => '$data["vht_model"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Vehicle model'),
										array('name' => 'vht_average_mileage', 'filter' => CHtml::activeTextField($model, 'vht_average_mileage', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->getAttributeLabel('vht_average_mileage'))), 'value' => '$data["vht_average_mileage"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Mileage'),
										array('name' => 'vht_capacity', 'filter' => CHtml::activeTextField($model, 'vht_capacity', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->getAttributeLabel('vht_capacity'))), 'value' => '$data["vht_capacity"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Seat capacity'),
										array('name' => 'vht_car_type', 'filter' => false, 'value' => '$data["vct_label"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Car Type'),
										array('name' => 'vht_fuel_type', 'filter' => false, 'value' => '$data["vht_fuel_type"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Fuel Type'),
										array(
											'header'			 => 'Action',
											'class'				 => 'CButtonColumn',
											'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
											'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
											'template'			 => '{edit}{delete}',
											'buttons'			 => array(
												'edit'			 => array(
													'url'		 => 'Yii::app()->createUrl("admin/vehicle/addtype", array("vhtid" => $data["vht_id"]))',
													'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\vehicle_type\edit_booking.png',
													'label'		 => '<i class="fa fa-edit"></i>',
													'options'	 => array('style' => '', 'class' => 'btn btn-xs editModel p0', 'title' => 'Edit Model'),
												),
												'delete'		 => array(
													'click'		 => 'function(){
							var con = confirm("Are you sure you want to delete this model?"); 
							if(con){
							$href = $(this).attr(\'href\');
							$.ajax({
							url: $href,
							success: function(result){
							if(result != null && result!="")
							{
							if(result.trim() == "true"){
							$(\'#vehicletype-grid\').yiiGridView(\'update\');
							}else{
							alert(\'Sorry error occured\');
							}
							}

							},
							error: function(xhr, status, error){
							alert(\'Sorry error occured\');
							}
							});
							}
							return false;
							}',
													'url'		 => 'Yii::app()->createUrl("admin/vehicle/delvehicletype", array("vid" => $data["vht_id"]))',
													'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\vehicle_type\customer_cancel.png',
													'label'		 => '<i class="fa fa-remove"></i>',
													'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'class' => 'btn btn-xs conDelete p0', 'title' => 'Delete Model'),
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
    </div>
</div>