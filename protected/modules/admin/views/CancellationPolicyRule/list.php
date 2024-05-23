<div class="panel-advancedoptions" >
	<div class="row">
		<?php
		$form			 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'cancellationpolicyrule',
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
			$form->numberFieldGroup($model, 'local_cpr_charge', array('label'			 => "Cancellation Charge",
				'class'			 => "form-control",
				'widgetOptions'	 => array('htmlOptions' => array('placeholder' => 'Cancellation Charge'))))
			?>


		</div>

		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">

			<?=
			$form->numberFieldGroup($model, 'local_cpr_hours', array('label'			 => "Cancellation Hours",
				'class'			 => "form-control",
				'widgetOptions'	 => array('htmlOptions' => array('placeholder' => 'Cancellation Hours'))))
			?>


		</div>



		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
			<div class="row">
				<div class="col-xs-12 "><label>Service Tier</label>
				</div>
				<div class="col-xs-12"> <?php
					$serviceTierArr	 = ServiceClass::model()->getJSON(ServiceClass::model()->getList('array'));
					$this->widget('booster.widgets.TbSelect2', array(
						'name'			 => 'cpr_service_tier',
						'model'			 => $model,
						'data'			 => $serviceTierArr,
						'value'			 => explode(',', $model->cpr_service_tier),
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
				<div class="col-xs-12 "><label>Initiator</label></div>
				<div class="col-xs-12">  <?php
					$initiator_type	 = CancellationPolicyRule::model()->getinitiatorType();
					$this->widget('booster.widgets.TbSelect2', array(
						'name'			 => 'cpr_mark_initiator',
						'model'			 => $model,
						'data'			 => $initiator_type,
						'value'			 => explode(',', $model->cpr_mark_initiator),
						'htmlOptions'	 => array(
							'multiple'		 => 'multiple',
							'placeholder'	 => 'Mark Initiator',
							'width'			 => '100%',
							'style'			 => 'width:100%',
						),
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
        <div class="col-md-12 pull-left"> <a href="<?= Yii::app()->createUrl('admin/CancellationPolicyRule/Add') ?>"><div class="btn btn-info"><i class="fa fa-plus"></i> Add</div></a></div>
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
									'id'				 => 'cancellationpolicyrule',
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
										array('name' => 'cpr_charge', 'filter' => false, 'value' => '$data[cpr_charge]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Cancellation Charge'),
										array('name' => 'cpr_hours', 'filter' => false, 'value' => '$data[cpr_hours]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Cancellation Hours'),
										array('name' => 'cpr_is_working_hour', 'filter' => false, 'value' => '$data[cpr_is_working_hour]==1?Yes:No', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => ' Is Working Hour'),
										array('name'	 => 'cpr_mark_initiator', 'filter' => false, 'value'	 => function($data) {
												if ($data['cpr_mark_initiator'] != null)
												{
													$markInitiatorArr	 = explode(',', $data['cpr_mark_initiator']);
													$initiator_type		 = "";
													foreach ($markInitiatorArr as $arr)
													{
														$initiator_type .= CancellationPolicyRule::model()->getinitiatorType($arr) . ",";
													}
													echo trim($initiator_type, ",");
												}
											},
											'sortable'								 => true, 'headerHtmlOptions'						 => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions'							 => array('style' => 'text-align: center;'), 'header'								 => 'Initiator'),
										array('name'	 => 'cpr_service_tier', 'filter' => false, 'value'	 => function($data) {
												if ($data['cpr_service_tier'] != null)
												{
													$serviceTierTypeArr	 = explode(',', $data['cpr_service_tier']);
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
										array('name' => 'cpr_status', 'value' => '$data[cpr_status]==1?Active:Inactive', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Status'),
										array(
											'header'			 => 'Action',
											'class'				 => 'CButtonColumn',
											'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => ''),
											'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
											'template'			 => '{edit}{delete}',
											'buttons'			 => array(
												'edit'			 => array(
													'url'		 => 'Yii::app()->createUrl("admin/CancellationPolicyRule/Add", array(\'id\' => $data[cpr_id]))',
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
													'url'		 => 'Yii::app()->createUrl("admin/CancellationPolicyRule/ChangeStatus", array(\'id\' => $data[cpr_id]))',
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
        $('#cancellationpolicyrule').yiiGridView('update');
    }

</script>