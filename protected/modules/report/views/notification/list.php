<div class="row m0">
    <div class="col-xs-12">        
        <div class="panel panel-default">
            <div class="panel-body">

				<?php
				$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
					'id'					 => 'otpreport-form', 'enableClientValidation' => true,
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
				// @var $form TbActiveForm 
				?>
				<div class="row"> 

                    <div class="col-xs-12 col-sm-3" style="">
                        <div class="form-group">
                            <label class="control-label">Date Range</label>
							<?php
							$daterang			 = "Select Date Range";
							$ntl_created_on1	 = ($model->ntl_created_on1 == '') ? '' : $model->ntl_created_on1;
							$ntl_created_on2	 = ($model->ntl_created_on2 == '') ? '' : $model->ntl_created_on2;
							//echo $ntl_created_on1."===".$ntl_created_on2;
							if ($ntl_created_on1 != '' && $ntl_created_on2 != '')
							{
								$daterang = date('F d, Y', strtotime($ntl_created_on1)) . " - " . date('F d, Y', strtotime($ntl_created_on2));
							}
							?>
                            <div id="ntlCreatedOn" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                <span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
                            </div>
							<?= $form->hiddenField($model, 'ntl_created_on1'); ?>
							<?= $form->hiddenField($model, 'ntl_created_on2'); ?>

                        </div>
                    </div>  
					<div class="col-xs-12 col-sm-2" style="">
                        <div class="form-group">
                            <label class="control-label">Entity Type</label>
							<?php
							$data = NotificationLog::model()->getJSONAllEntityType();

//							$this->widget('booster.widgets.TbSelect2', array(
//								'attribute'		 => 'ntl_entity_type',
//								'model'			 => $model,
//								'value'			 => $model->ntl_entity_type,
//								'asDropDownList' => FALSE,
//								'options'		 => array('data'		 => new CJavaScriptExpression($data),
//									'multiple'	 => true),
//								'htmlOptions'	 => array(
//									'multiple'		 => 'multiple',
//									'style'			 => 'width:100%', 'placeholder'	 => 'Select Entity Type')
//							));
							$this->widget('booster.widgets.TbSelect2', array(
								'attribute'		 => 'ntl_entity_type',
								'model'			 => $model,
								'value'			 => $model->ntl_entity_type,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($data)),
								'htmlOptions'	 => array('id'			 => 'ntl_entity_types',
									'style'			 => 'width:100%', 'placeholder'	 => 'Select Entity Type')
							));
							?>
                        </div>
                    </div>

					<div class="col-xs-12 col-sm-2" id='entityId' style="display: none"> 
						<? //= $form->textFieldGroup($model, 'ntl_entity_id', array('widgetOptions' => ['htmlOptions' => []])) ?>
						<div id="followVnd" style="display:none">
							<div class="form-group">
								<?php $vndmodel	 = new Vendors(); 
								 echo $form->textFieldGroup($model, 'vndid', array('label' => "Vendor Id Or Code", 'widgetOptions' => ['htmlOptions' => ['placeholder' => 'Enter Vendor Id Or Code']])) ?>
								
							</div>
						</div>

						<div id="followDrv" style="display:none">
							<div class="form-group">
								<?php $drvmodel	 = new Drivers(); 
								 echo $form->textFieldGroup($model, 'drvid', array('label' => "Driver Id Or Code", 'widgetOptions' => ['htmlOptions' => ['placeholder' => 'Enter Driver Id Or Code']])) ?>
							</div>
                        </div>

						<div id="followCust" style="display:none">
							<div class="form-group">
								<?php $usrmodel	 = new Users(); 
								 echo $form->textFieldGroup($model, 'userid', array('label' => "Customer Id", 'widgetOptions' => ['htmlOptions' => ['placeholder' => 'Enter Customer Id']])) ?>
							</div>
						</div>

						<div id="followAdm" style="display:none">
							<div class="form-group">
								<?php $admmodel	 = new Admins(); 
								 echo $form->textFieldGroup($model, 'admid', array('label' => "Admin Id", 'widgetOptions' => ['htmlOptions' => ['placeholder' => 'Enter Admin Id']])) ?>
							
							</div>
						</div>
                    </div>

					<!--					<div class="col-xs-12 col-md-3" id="ntl_entity_id" style="display:none">
											<input type="text" name="ntl_entity_id" id="ntl_entity_id" class="form-control" placeholder="">
										</div>-->

					<div class="col-xs-12 col-sm-2" style="">
                        <div class="form-group">
                            <label class="control-label">Ref Type</label>
							<?php
							$data		 = NotificationLog::model()->getJSONAllRefType();

							$this->widget('booster.widgets.TbSelect2', array(
								'attribute'		 => 'ntl_ref_type',
								'model'			 => $model,
								'value'			 => $model->ntl_ref_type,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($data)),
								'htmlOptions'	 => array(
									'style'			 => 'width:100%', 'placeholder'	 => 'Select Ref Type')
							));
							?>
                        </div>
                    </div> 
                    <div class="col-xs-12 col-sm-2"> 
						<?= $form->textFieldGroup($model, 'ntl_ref_id', array('widgetOptions' => ['htmlOptions' => []])) ?>
                    </div>
				</div>
				<div class="row"><div class="col-xs-12 col-sm-3 ">   
						<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary')); ?></div>
				</div></div>				
			<?php $this->endWidget(); ?>
			<BR>
			<?php
			if (!empty($dataProvider))
			{
				$params									 = array_filter($_REQUEST);
				$dataProvider->getPagination()->params	 = $params;
				$dataProvider->getSort()->params		 = $params;
				$this->widget('booster.widgets.TbGridView', array(
					'responsiveTable'	 => true,
					'dataProvider'		 => $dataProvider,
					'template'			 => "<div class='panel-heading'><div class='row m0'>
                                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                                    </div></div>
                                                    <div class='panel-body table-responsive table-bordered'>{items}</div>
                                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
					'itemsCssClass'		 => 'table table-striped table-bordered dataTable mb0',
					'htmlOptions'		 => array('class' => 'panel panel-primary  compact'),
					'columns'			 => array(
						array('name'	 => 'ntl_entity_type', 'value'	 => function ($data) {
								return $data['ntl_entity_type'];
							}, 'sortable'			 => true,
							'headerHtmlOptions'	 => array('class' => ''),
							'header'			 => 'Entity Type'),
						array('name'	 => 'ntl_title', 'value'	 => function ($data) {
								echo $data['ntl_title'];
							}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => ''),
							'header'			 => 'Title'),
						array('name'	 => 'ntl_message', 'value'	 => function ($data) {
								echo $data['ntl_message'];
							}, 'sortable'								 => true, 'headerHtmlOptions'						 => array(),
							'header'								 => 'Message'),
						array('name'	 => 'ntl_event_code', 'value'	 => function ($data) {
								echo $data['ntl_event_code'];
							}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => ''),
							'header'			 => 'Event Code'),
						array('name'	 => 'ntl_ref_type', 'value'	 => function ($data) {
								echo $data['ntl_ref_type'];
							}, 'sortable'			 => true,
							'headerHtmlOptions'	 => array('class' => ''),
							'header'			 => 'Ref Type'),
						array('name'	 => 'ntl_ref_id', 'value'	 => function ($data) {
								echo $data['ntl_ref_id'];
							}, 'sortable'			 => true,
							'headerHtmlOptions'	 => array('class' => ''),
							'header'			 => 'Ref Id'),
						array('name'	 => 'ntl_entity_id', 'value'	 =>
						function ($data)
						{
							echo $data['ntlentity'];
						},
						'sortable'	=> true, 'headerHtmlOptions' => array('class' => ''), 'htmlOptions'	=> array('class' => 'text-center'), 'header' => 'Entity Id / Code'),	



						array('name'	 => 'ntl_status', 'value'	 => function ($data) {
								echo $data['ntl_status'];
							}, 'sortable'			 => true,
							'headerHtmlOptions'	 => array('class' => ''),
							'header'			 => 'Status'),
						array('name'	 => 'ntl_created_on', 'value'	 => function ($data) {
								if (!empty($data['ntl_created_on']))
								{
									echo DateTimeFormat::DateTimeToLocale($data['ntl_created_on']);
								}
							}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => ''),
							'header'			 => 'Created On'),
						array('name'	 => 'ntl_sent_on', 'value'	 => function ($data) {
								if (!empty($data['ntl_sent_on']))
								{
									echo DateTimeFormat::DateTimeToLocale($data['ntl_sent_on']);
								}
							}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => ''),
							'header'			 => 'Sent On'),
				)));
			}
			?> 

		</div>  
	</div>  
</div>
</div>
<script type="text/javascript">
    $(document).ready(function ()
    {
        var start = '<?= date('d/m/Y'); ?>';
        var end = '<?= date('d/m/Y'); ?>';
        $('#ntlCreatedOn').daterangepicker(
                {
                    locale: {
                        format: 'DD/MM/YYYY',
                        cancelLabel: 'Clear'
                    },
                    "showDropdowns": true,
                    "alwaysShowCalendars": true,
                    startDate: start,
                    endDate: end,
                    maxDate: moment(),
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Previous 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Previous 15 Days': [moment().subtract(15, 'days'), moment()]
                    }
                }, function (start1, end1) {
            $('#NotificationLog_ntl_created_on1').val(start1.format('YYYY-MM-DD'));
            $('#NotificationLog_ntl_created_on2').val(end1.format('YYYY-MM-DD'));
            $('#ntlCreatedOn span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
        });
        $('#ntlCreatedOn').on('cancel.daterangepicker', function (ev, picker) {
            $('#ntlCreatedOn span').html('Select Date Range');
            $('#NotificationLog_ntl_created_on1').val('');
            $('#NotificationLog_ntl_created_on2').val('');
        });
		var type = $('#ntl_entity_types').val();
		if (type == 1)
        {
			$("#entityId").show("slow");
            $("#followCust").show("slow");
        }
        if (type == 2)
        {
			$("#entityId").show("slow");
            $("#followVnd").show("slow");
        }
        if (type == 3)
        {
			$("#entityId").show("slow");
            $("#followDrv").show("slow");
        }
        if (type == 4)
        {
			$("#entityId").show("slow");
            $("#followAdm").show("slow");
        }
    });

    $('#ntl_entity_types').change(function () {
        $("#entityId").show("slow");
        var type = $('#ntl_entity_types').val();
        if (type == 1)
        {
            $("#followVnd").hide("slow");
            $("#followDrv").hide("slow");
            $("#followAdm").hide("slow");
            $("#followCust").show("slow");
        }
        if (type == 2)
        {
            $("#followVnd").show("slow");
            $("#followDrv").hide("slow");
            $("#followAdm").hide("slow");
            $("#followCust").hide("slow");
        }
        if (type == 3)
        {
            $("#followVnd").hide("slow");
            $("#followDrv").show("slow");
            $("#followAdm").hide("slow");
            $("#followCust").hide("slow");
        }
        if (type == 4)
        {
            $("#followVnd").hide("slow");
            $("#followDrv").hide("slow");
            $("#followAdm").show("slow");
            $("#followCust").hide("slow");
        }
    });
	
    $('#ServiceCallQueue_scqType_1').change(function () {
        $("#followupPerson").show("slow");
    });
</script>