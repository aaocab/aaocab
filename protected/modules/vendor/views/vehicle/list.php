
<div class="row">
    <div class="col-xs-12 pull-right mb20">
        <a href="<?= Yii::app()->createUrl('vendor/vehicle/edit', ['id' => $_REQUEST['id'], 'code' => $_REQUEST['code']]) ?>" target="_blank"><button class="btn btn-primary pull-right "><i class="fa fa-plus pr5"></i>New Vehicle</button></a>
    </div>
	<?php
	$vtypeList = VehicleTypes::model()->getVehicleTypeList1();
	$vtypeListJson = VehicleTypes::model()->getJSON($vtypeList);
	$form = $this->beginWidget('booster.widgets.TbActiveForm', array('id' => 'vehicletype-form', 'enableClientValidation' => true,
		'clientOptions' => array(
			'validateOnSubmit' => true,
			'errorCssClass' => 'has-error'
		),
		// Please note: When you enable ajax validation, make sure the corresponding
		// controller action is handling ajax validation correctly.
		// See class documentation of CActiveForm for details on this,
		// you need to use the performAjaxValidation()-method described there.
		'enableAjaxValidation' => false,
		'errorMessageCssClass' => 'help-block',
		'htmlOptions' => array(
			'class' => '',
		),
	));
	/* @var $form TbActiveForm */
	?>
    <div class="col-xs-6"> <?= $form->textFieldGroup($model, 'vhc_number'); ?></div><div class="col-xs-6">
        <label>Select Vehicle Model</label>
        <?
        $this->widget('booster.widgets.TbSelect2', array(
            'model' => $model,
            'attribute' => 'vhc_type_id', 'val' => $model->vhc_type_id,
            'asDropDownList' => FALSE,
            'options' => array('data' => new CJavaScriptExpression($vtypeListJson), 'allowClear' => true),
            'htmlOptions' => array('style' => 'width:100%', 'placeholder' => 'Select Vehicle Model')
        ));
        ?></div>
	<div class="col-xs-12 col-sm-12 text-center mb20">
		<button class="btn btn-info" type="submit" style="width: 185px;"  name="vehicleSearch">Search</button>
	</div>
	<?php $this->endWidget(); ?>
    <div class="col-xs-12">
		<?php
        if (!empty($dataProvider)) {
			$params = array_filter($_REQUEST);
			$dataProvider->getPagination()->params = $params;
			$dataProvider->getSort()->params = $params;
			$this->widget('booster.widgets.TbGridView', array(
				'responsiveTable' => true,
				// 'filter' => $model1,
				'dataProvider' => $dataProvider,
				'id' => 'vehicleListGrid', 'template' => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
				'itemsCssClass' => 'table table-striped table-bordered mb0',
				'htmlOptions' => array('class' => 'table-responsive panel panel-primary  compact'),
				//
				'ajaxType' => 'GET',
				'columns' => array(
					array('name' => 'model',
                        'type'=>'raw',
						'value' => function($data) {
                            if ($data->vhc_approved == 1) {
                                $info = '  <span class="text-success" title="vehicle approved"><i class="fa fa-check-circle fa-lg" aria-hidden="true"></i></span>';
						}
                            if ($data->vhc_approved == 3) {
                                $info = '  <span class="text-danger" title="vehicle rejected"><i class="fa fa-times-circle-o fa-lg" aria-hidden="true"></i></span>';
                            }
                            return $data->vhcType->vht_make . " " . $data->vhcType->vht_model.$info;
                        }
						,
						'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Vehicle Model'),
					array('name' => 'year', 'value' => '$data->vhc_year', 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Year'),
					array('name' => 'vhc_number', 'value' => '$data->vhc_number', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Number'),
					array('name' => 'vendor', 'value' => '$data->vnd_names', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Vendor'),
					array('name' => 'color', 'value' => '$data->vhc_color', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Color'),
					array('name' => 'vhcType.vht_capacity', 'value' => '$data->vhcType->vht_capacity', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Capacity'),
					array('name' => 'cartype', 'type' => 'raw', 'value' => '', 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Car Type'),
					array('name' => 'vhc_dop', 'value' => function($data) {
                            if ($data->vhc_dop != '') {

								return DateTimeFormat::DateToLocale($data->vhc_dop);
							}
						}, 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Date of Purchase'),
					array('name' => 'driver', 'value' => '$data->drv_names', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Driver'),
					array('name' => 'vhc_mark_car_count', 'value' => '$data->vhc_mark_car_count', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Remark Bad'),
					array(
						'header' => 'Action',
						'class' => 'CButtonColumn',
						'htmlOptions' => array('style' => 'white-space:nowrap;text-align: center'),
						'headerHtmlOptions' => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
						'template' => '{verify}{verified}',
						'buttons' => array(
							'verify' => array(
								'url' => 'Yii::app()->createUrl("vendor/vehicle/edit", array(\'vhcid\' => $data->vhc_id,\'id\'=>$data->vhc_vendor_id1,\'code\'=>$data->getCode($data->vhc_vendor_id1)))',
								'imageUrl' => false,
								'label' => '<i class="fa fa-edit"></i>',
                                'visible' => '$data->vhc_approved==0?true:false;',
								'options' => array('style' => 'margin-right: 2px', 'class' => 'btn btn-xs btn-danger ignoreJob', 'title' => 'Edit and verify', 'target' => '_blank'),
							),
							'verified' => array(
								'url' => 'Yii::app()->createUrl("vendor/vehicle/edit", array(\'vhcid\' => $data->vhc_id,\'id\'=>$data->vhc_vendor_id1,\'code\'=>$data->getCode($data->vhc_vendor_id1)))',
								'imageUrl' => false,
								'label' => '<i class="fa fa-edit"></i>',
                                'visible' => '($data->vhc_approved==2 || $data->vhc_approved==1 || $data->vhc_approved==3)?true:false;',
								'options' => array('style' => 'margin-right: 2px', 'class' => 'btn btn-xs btn-success verified', 'title' => 'Verified', 'target' => '_blank'),
							),
							'htmlOptions' => array('class' => 'center'),
						))
			)));
		}
		?>


    </div>
    <div class="col-xs-12">
        <h2> Driver List</h2>
    </div>
    <div class="col-xs-12">
           <div class="col-xs-12 pull-right mb20">
            <a href="<?= Yii::app()->createUrl('vendor/vehicle/adddriver', ['id' => $_REQUEST['id'], 'code' => $_REQUEST['code']]) ?>" target="_blank"><button class="btn btn-primary pull-right "><i class="fa fa-plus pr5"></i>New Driver</button></a>
        </div>
		<?php
		$form = $this->beginWidget('booster.widgets.TbActiveForm', array('id' => 'vehicletype-form', 'enableClientValidation' => true,
			'clientOptions' => array(
				'validateOnSubmit' => true,
				'errorCssClass' => 'has-error'
			),
			// Please note: When you enable ajax validation, make sure the corresponding
			// controller action is handling ajax validation correctly.
			// See class documentation of CActiveForm for details on this,
			// you need to use the performAjaxValidation()-method described there.
			'enableAjaxValidation' => false,
			'errorMessageCssClass' => 'help-block',
			'htmlOptions' => array(
				'class' => '',
			),
		));
		/* @var $form TbActiveForm */
		?>
		<div class="col-xs-6"> <label>Driver Name</label><?= $form->textFieldGroup($modelDriver, 'drv_name', ['label' => '']); ?></div>
		<div class="col-xs-6"> <label> Driver Phone</label>
			<?= $form->textFieldGroup($modelDriver, 'drv_phone', ['label' => '']); ?>
		</div>
		<div class="col-xs-6"> <label> Driver Email</label>
			<?= $form->textFieldGroup($modelDriver, 'drv_email', ['label' => '']); ?>
		</div>
		<div class="col-xs-12 mb20" style="text-align: center">     
			<button class="btn btn-info" type="submit" style="width: 185px;"  name="driverSearch">Search</button>
		</div>
		<?php $this->endWidget(); ?>
    </div>

    <div class="colo-xs-12">
		<?php
        if (!empty($driverDataProvider)) {
			$params = array_filter($_REQUEST);
			$driverDataProvider->getPagination()->params = $params;
			$driverDataProvider->getSort()->params = $params;
			$this->widget('booster.widgets.TbGridView', array(
				'responsiveTable' => true,
				'dataProvider' => $driverDataProvider,
				'id' => 'driverList',
				'template' => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
				'itemsCssClass' => 'table table-striped table-bordered mb0',
				'htmlOptions' => array('class' => 'panel panel-primary compact'),
                            'ajaxType' => 'GET',
				'columns' => array(
//                    array('name' => 'drv_photo_path', 'type' => 'html', 'value' => function ($data) {
//                            if ($data->drv_photo_path != '') {
//                                $path = Yii::app()->getBaseUrl(true) . '/' . $data->drv_photo_path;
//                            } else {
//                                $path = "/images/noimg.gif";
//                            }
//                            return CHtml::image($path, $data->drv_name, [ 'style' => 'width: 50px']);
//                        }, 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Photo'),
                            array('name' => 'drv_name','type'=>'raw',
                                'value' => function($data) {
                            if ($data->drv_approved == 1) {
                                $info = '  <span class="text-success" title="driver approved"><i class="fa fa-check-circle fa-lg" aria-hidden="true"></i></span>';
							}
                            if ($data->drv_approved == 3) {
                                $info = '  <span class="text-danger" title="driver rejected"><i class="fa fa-times-circle-o fa-lg" aria-hidden="true"></i></span>';
                            }
                            return $data->drv_name.$info;
                        }
                        , 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Name'),
							array('name' => 'vnd_name', 'value' => '$data->agt', 'headerHtmlOptions' => array(), 'header' => 'Vender'),
							array('name' => 'drv_phone',
								'value' => function($data) {
                                    if ($data->drv_phone != '') {
										return '+' .$data->drv_country_code. $data->drv_phone;
									}
								},
								'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Phone'),
							array('name' => 'drv_email', 'value' => '$data->drv_email', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Email'),
							array('name' => 'drv_doj',
								'value' => function($data) {
									return DateTimeFormat::DateTimeToLocale($data->drv_doj);
								},
								'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Joining Date'),
							array('name' => 'usr_city', 'value' => '$data->cty_name', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'City'),
							array('name' => 'drv_created',
								'value' => function ($data) {
									return DateTimeFormat::DateTimeToLocale($data->drv_created);
								},
								'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Added On'),
							array('name' => 'usr_city', 'value' => '$data->vhc', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Assigned vehicles'),
							array('name' => 'drv_mark_driver_count', 'value' => '$data->drv_mark_driver_count', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Remark Bad'),
							array(
								'header' => 'Action',
								'class' => 'CButtonColumn',
								'htmlOptions' => array('style' => 'white-space:nowrap;text-align: center'),
								'headerHtmlOptions' => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
								'template' => '{verify}{verified}',
								'buttons' => array(
									'verify' => array(
										'url' => 'Yii::app()->createUrl("vendor/vehicle/editdriver", array(\'drvid\' => $data->drv_id,\'id\'=>$data->drv_vendor_id1,\'code\'=>$data->getCode($data->drv_vendor_id1)))',
										'imageUrl' => false,
										'label' => '<i class="fa fa-edit"></i>',
										'visible' => '$data->checkVerifiedByvendor($data->drv_id)==true?false:true;',
										'options' => array('data-toggle' => 'ajaxModal', 'style' => 'margin-right: 8px', 'class' => 'btn btn-xs btn-danger verifydriver', 'title' => 'Verify And Edit', 'target' => '_blank'),
									),
									'verified' => array(
										'url' => 'Yii::app()->createUrl("vendor/vehicle/editdriver", array(\'drvid\' => $data->drv_id,\'id\'=>$data->drv_vendor_id1,\'code\'=>$data->getCode($data->drv_vendor_id1)))',
										'imageUrl' => false,
										'label' => '<i class="fa fa-edit"></i>',
										'visible' => '$data->checkVerifiedByvendor($data->drv_id)==true?true:false;',
										'options' => array('data-toggle' => 'ajaxModal', 'style' => 'margin-right: 8px', 'class' => 'btn btn-xs btn-success driververified', 'title' => 'Verified', 'target' => '_blank'),
									),
									'htmlOptions' => array('class' => 'center'),
								)
							)
					)));
				}
				?>
    </div>
</div>