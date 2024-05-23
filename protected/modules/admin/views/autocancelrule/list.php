<div class="panel-advancedoptions" >
	<div class="row">
		<?php
		$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'autocanelform',
			'enableClientValidation' => true,
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


		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
			<?=
			$form->numberFieldGroup($model, 'acr_time_create', array('label'			 => "Create Time",
				'class'			 => "form-control",
				'widgetOptions'	 => array('htmlOptions' => array('placeholder' => 'Enter Create Time', 'min' => 0))))
			?>



		</div>

		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
			<?=
			$form->numberFieldGroup($model, 'acr_time_to_pickup', array('label'			 => "Pickup Time",
				'class'			 => "form-control",
				'widgetOptions'	 => array('htmlOptions' => array('placeholder' => 'Enter Pickup Time', 'min' => 0))))
			?>

		</div>

		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
			<?=
			$form->numberFieldGroup($model, 'acr_time_confirm', array('label'			 => "Confirm Time",
				'class'			 => "form-control",
				'widgetOptions'	 => array('htmlOptions' => array('placeholder' => 'Enter Confirm Time', 'min' => 0))))
			?>

		</div>

		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">

			<?=
			$form->numberFieldGroup($model, 'acr_time_bidstarted', array('label'			 => "Bid Start Time",
				'class'			 => "form-control",
				'widgetOptions'	 => array('htmlOptions' => array('placeholder' => 'Enter Bid Start Time', 'min' => 0))))
			?>
		</div>

		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
			<div class="row">
				<div class="col-xs-12 "><label>Booking Type</label></div>
				<div class="col-xs-12">  <?php
					$booking_type		 = Booking::model()->getBookingType();
					$this->widget('booster.widgets.TbSelect2', array(
						'name'			 => 'acr_bkg_type',
						'model'			 => $model,
						'data'			 => $booking_type,
						'value'			 => explode(',', $model->acr_bkg_type),
						'htmlOptions'	 => array(
							'multiple'		 => 'multiple',
							'placeholder'	 => 'Booking Type',
							'width'			 => '100%',
							'style'			 => 'width:100%',
						),
					));
					?>
				</div>
			</div>
		</div>



		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
			<div class="row">
				<div class="col-xs-12 "><label>Service Tier</label>
				</div>
				<div class="col-xs-12"> <?php
					$serviceTierArr		 = ServiceClass::model()->getJSON(ServiceClass::model()->getList('array'));
					$this->widget('booster.widgets.TbSelect2', array(
						'name'			 => 'acr_service_tier',
						'model'			 => $model,
						'data'			 => $serviceTierArr,
						'value'			 => explode(',', $model->acr_service_tier),
						'htmlOptions'	 => array(
							'multiple'		 => 'multiple',
							'placeholder'	 => 'Service Tier',
							'width'			 => '100%',
							'style'			 => 'width:100%',
						),
					));
					?>
				</div>
			</div>
		</div>

		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
			<div class="row">
				<div class="col-xs-12 "><label>Cancel Value</label>
				</div>
				<div class="col-xs-12"> <?php
					$cancelValueList	 = AutoCancelRule::model()->getCancelType();
					$cancelValueListJson = AutoCancelRule::model()->getJSON($cancelValueList);
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'acr_auto_cancel_value',
						'val'			 => "{$model->acr_auto_cancel_value}",
						'asDropDownList' => FALSE,
						'options'		 => array('data' => new CJavaScriptExpression($cancelValueListJson), 'allowClear' => true),
						'htmlOptions'	 => array('style' => 'width:100%;', 'placeholder' => 'Cancel Value')
					));
					?>

				</div>
			</div>
		</div>

		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
			<div class="row">
				<div class="col-xs-12 "><label>Cancel Reason</label>
				</div>
				<div class="col-xs-12"> <?php
					$cancelList			 = CHtml::listData(CancelReasons::model()->findAll(array('order'		 => 'cnr_id',
										'condition'	 => 'cnr_active=:cnr_active and  cnr_show_admin=:cnr_show_admin and cnr_show_user=:cnr_show_user ',
										'params'	 => array(':cnr_active' => 1, ':cnr_show_user' => 0, ':cnr_show_admin' => 0))), 'cnr_id', 'cnr_reason');

					$cancelListJson = AutoCancelRule::model()->getJSON($cancelList);
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'acr_auto_cancel_code',
						'val'			 => "{$model->acr_auto_cancel_code}",
						'asDropDownList' => FALSE,
						'options'		 => array('data' => new CJavaScriptExpression($cancelListJson), 'allowClear' => true),
						'htmlOptions'	 => array('style' => 'width:100%;', 'placeholder' => 'Cancel Reason')
					));
					?>

				</div>
			</div>
		</div>

        <div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 text-center mt20 p5">   
			<?php echo CHtml::submitButton('Search', array('class' => 'btn btn-primary full-width')); ?></div>
		<?php $this->endWidget(); ?>
    </div>

    <div class="row">
        <div class="col-md-12 pull-left"> <a href="<?= Yii::app()->createUrl('admin/AutoCancelRule/Add') ?>"><div class="btn btn-info"><i class="fa fa-plus"></i> Add</div></a></div>
        <div class="col-md-12">
            <div class="panel" >
                <div class="panel-body panel-no-padding p0 pt10">
                    <div class="panel-scroll1">
                        <div style="width: 100%; overflow: auto;  border: 1px #aaa solid;color: #444;">
							<?php
							if (!empty($dataProvider))
							{
								$params									 = array_filter($_REQUEST);
								$dataProvider->getPagination()->params	 = $params;
								$dataProvider->getSort()->params		 = $params;
								$this->widget('booster.widgets.TbGridView', array(
									'id'				 => 'autocancelrule',
									'responsiveTable'	 => true,
									'dataProvider'		 => $dataProvider,
									'template'			 => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
									'itemsCssClass'		 => 'table table-striped table-bordered mb0',
									'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
									'columns'			 => array(
										array('name' => 'acr_time_to_pickup', 'filter' => false, 'value' => '$data[acr_time_to_pickup]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Pickup time'),
										array('name' => 'acr_time_confirm', 'filter' => false, 'value' => '$data[acr_time_confirm]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Confirm time'),
										array('name' => 'acr_time_create', 'filter' => false, 'value' => '$data[acr_time_create]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Create time'),
										array('name' => 'acr_time_bidstarted', 'filter' => false, 'value' => '$data[acr_time_bidstarted]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Bid Start time '),
										array('name' => 'acr_demsupmisfire', 'filter' => false, 'value' => '$data[acr_demsupmisfire]==1?Yes:No', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => ' DemsupMisfire'),
										array('name' => 'acr_cs', 'filter' => false, 'value' => '$data[acr_cs]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Critical Score'),
										array('name' => 'acr_rule_rank', 'filter' => false, 'value' => '$data[acr_rule_rank]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Rank'),
										array('name' => 'acr_is_assigned', 'filter' => false, 'value' => '$data[acr_is_assigned]==1?Yes:No', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Is Assigned '),
										array('name' => 'acr_is_allocated', 'filter' => false, 'value' => '$data[acr_is_allocated]==1?Yes:No', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Is Allocated'),
										array('name'	 => 'acr_bkg_type', 'filter' => false, 'value'	 => function($data) {
												if ($data['acr_bkg_type'] != null)
												{
													$bookingTypeArr	 = explode(',', $data['acr_bkg_type']);
													$booking_type	 = "";
													foreach ($bookingTypeArr as $arr)
													{
														$booking_type .= Booking::model()->getBookingType($arr) . ",";
													}
													echo trim($booking_type, ",");
												}
											},
											'sortable'								 => true, 'headerHtmlOptions'						 => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions'							 => array('style' => 'text-align: center;'), 'header'								 => 'Booking Type'),
										array('name' => 'acr_addresses_given', 'filter' => false, 'value' => '$data[acr_addresses_given]==1?Yes:No', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Is Address Given'),
										array('name'	 => 'acr_service_tier', 'filter' => false, 'value'	 => function($data) {
												if ($data['acr_service_tier'] != null)
												{
													$serviceTierTypeArr	 = explode(',', $data['acr_service_tier']);
													$serviceTierDetails	 = ServiceClass::model()->getList('array');
													$serviceTierType	 = "";
													foreach ($serviceTierDetails as $arr)
													{
														if (in_array($arr['scc_id'], $serviceTierTypeArr))
														{
															$serviceTierType .= $arr['scc_label'] . ",";
														}
													}
													echo trim($serviceTierType, ",");
												}
											},
											'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions'		 => array('style' => 'text-align: center;'), 'header'			 => 'Service Tier'),
										array('name' => 'acr_status', 'value' => '$data[acr_status]==1?Active:Inactive', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Status'),
										array(
											'header'			 => 'Action',
											'class'				 => 'CButtonColumn',
											'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => ''),
											'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
											'template'			 => '{edit}{delete}',
											'buttons'			 => array(
												'edit'			 => array(
													'url'		 => 'Yii::app()->createUrl("admin/AutoCancelRule/Add", array(\'id\' => $data[acr_id]))',
													'imageUrl'	 => Yii::app()->request->baseUrl . '/images/icon/city/edit_booking.png',
													'options'	 => array('style' => 'margin-right: 4px', 'class' => 'btn btn-xs surgeedit p0', 'title' => 'Edit'),
												),
												'delete'		 => array(
													'click'		 => 'function(){
																		$href = $(this).attr(\'href\');
																		bootbox.confirm({
																				message: "Are you sure.You want to delete this?",
																				buttons: {
																					confirm: {
																						label: "Yes",
																						className: "btn-success"
																					},
																					cancel: {
																						label: "No",
																						className: "btn-danger"
																					}
																				},
																				callback: function (result) {
																				   if(result)
																					{
																						 jQuery.ajax({type: \'GET\',
																						 url: $href,
																						 "dataType": "json",
																						 success: function (data1)
																							{
																							 if (data1.success) 
																							  {
																								 refreshApprovalList();
																								 return false;
																							  } 
																							else 
																							 {
																								 alert(data1.errors);
																							 }
																						   }
																					   });
																					}																		   
																				}
																	   });
														 return false;
                                                        }',
													'url'		 => 'Yii::app()->createUrl("admin/AutoCancelRule/ChangeStatus", array(\'id\' => $data[acr_id]))',
													'imageUrl'	 => Yii::app()->request->baseUrl . '/images/icon/delete_booking.png',
													'label'		 => '<i class="fa fa-refresh"></i>',
													'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'class' => 'btn btn-xs surgedelete p0', 'title' => 'Delete'),
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


<script type="text/javascript">
    function refreshApprovalList() {
        $('#autocancelrule').yiiGridView('update');
    }

</script>