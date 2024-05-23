
<style>
    .zindex .panel-body{ z-index: 9999!important;}   

    .modal-title{
        display: none;
    }
</style>

<div class="panel panel-default zindex m0">
    <div class="panel-heading">ADD GOZO COINS<span class="pull-right">Booking ID:  <?= Booking::model()->getBookingIds($bookingId) ?></span></div>
	<div class="panel-body">
		<?php
		$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'credit-add-form',
			'enableClientValidation' => true,
			'clientOptions'			 => array(
				'validateOnSubmit'	 => true,
				'errorCssClass'		 => 'has-error',
				'afterValidate'		 => 'js:function(form,data,hasError){
                        if(!hasError){
                            $.ajax({
                            "type":"POST",
                            "dataType":"json",
                            "url":"' . CHtml::normalizeUrl(Yii::app()->request->url) . '",
                            "data":form.serialize(),
                            "success":function(data1){
                                 if(data1.success)
                                    {
                                          window.location.reload();
                                    }
                                    else{
                                        var errors = data1.errors;
                                        settings=form.data(\'settings\');
                                      $.each (settings.attributes, function (i) {
                                        $.fn.yiiactiveform.updateInput (settings.attributes[i], errors, form);
                                      });
                                      $.fn.yiiactiveform.updateSummary(form, errors);
                                  }},
                            });
                            }
                            

                        }'
			),
			// Please note: When you enable ajax validation, make sure the corresponding
			// controller action is handling ajax validation correctly.
			// See class documentation of CActiveForm for details on this,
			// you need to use the performAjaxValidation()-method described there.
			'enableAjaxValidation'	 => false,
			'errorMessageCssClass'	 => 'help-block',
			'htmlOptions'			 => array(
				'class' => 'form-horizontal'
			),
		));

		/* @var $form TbActiveForm */
		?>
		<div class="row p10">
			<div class="row">
				<div class="col-xs-5">
					<?= $form->numberFieldGroup($model, 'ucr_value', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Coins Value', 'min' => 0)))); ?>
					<input type="hidden" value="<?= $bkgAmt ?>" id="bookingAmt" name="bookingAmt">
				</div>
				<div class="col-xs-5  col-xs-offset-1">
					<?=
					$form->datePickerGroup($model, 'ucr_validity', array('label'			 => '',
						'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'Expire Date', 'value' => date('d/m/Y', strtotime('+1 years')))), 'prepend'		 => '<i class="fa fa-calendar"></i>'));
					?>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-5">
					<?= $form->numberFieldGroup($model, 'ucr_max_use', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Coins Max Use', 'min' => 0)))); ?>
				</div>
				<div class="col-xs-5  col-xs-offset-1">
					<?
					$arrcrdTypes = $model->getMaxUseTypes();
					foreach ($arrcrdTypes as $key => $val)
					{
						$jsoncrdTypes[] = array("id" => $key, "text" => $val);
					}
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'ucr_maxuse_type',
						'val'			 => $model->ucr_maxuse_type,
						'asDropDownList' => FALSE,
						'options'		 => array('data' => new CJavaScriptExpression(CJSON::encode($jsoncrdTypes)), 'allowClear' => true),
						'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Coins Max Use Type', 'value' => $model->ucr_maxuse_type)
					));
					?>
					<span class="has-error"><? echo $form->error($model, 'ucr_maxuse_type'); ?></span>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-5">
					<?
					$ucrType			 = UserCredits::$bookingCreditType;
					unset($ucrType[2]);
					?>
					<?= $form->radioButtonListGroup($model, 'ucr_type', array('label' => $model->getAttributeLabel('ucr_type'), 'widgetOptions' => array('data' => $ucrType), 'inline' => true)) ?>
					<span class="has-error"><? echo $form->error($model, 'ucr_type'); ?></span>
				</div>
				<div class="col-xs-5  col-xs-offset-1">
					<?
					$model->activateType = 1;
					?>
					<?= $form->radioButtonListGroup($model, 'activateType', array('label' => 'Activate', 'widgetOptions' => array('data' => [1 => "Immediate", 2 => "On Booking Completed"]), 'inline' => true)); ?>

				</div>

				<div class="col-xs-5  col-xs-offset-1" id="divrefId" style="display: none">
					<?= $form->textFieldGroup($model, 'ucr_ref_id', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Booking ID')))); ?>
				</div>

			</div>
			<div class="row">
				<div class="col-xs-11">
					<?= $form->textAreaGroup($model, 'ucr_desc', array('label' => '')); ?>
				</div>
			</div>
			<div class="col-xs-12 text-center"> <?= CHtml::submitButton('Save', array('class' => 'btn btn-primary pl30 pr30')); ?></div>
			<? $this->endWidget(); ?>
		</div>
	</div>
</div>
<div class="panel panel-default panel-border">
	<div class="panel panel-body">
		<?php
		if (!empty($dataProvider))
		{
			/* @var $dataProvider TbGridView */
			$params									 = array_filter($_REQUEST);
			$dataProvider->getPagination()->params	 = $params;
			$dataProvider->getSort()->params		 = $params;
			$this->widget('booster.widgets.TbGridView', array(
				'responsiveTable'	 => true,
				'dataProvider'		 => $dataProvider,
				'pager'				 => ['maxButtonCount' => 5, 'class' => 'booster.widgets.TbPager'],
				'id'				 => 'creditListGrid',
				'template'			 => "<div class='panel-heading'>
                                        <div class='row m0'>
                                        <div class='col-xs-12 col-sm-4 pt5'>Active Gozo Coins</div>
                                        <div class='col-xs-12 col-sm-4 pr0'>{summary}</div>
                                        <div class='col-xs-12 col-sm-4 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body table-responsive'>{items}</div>",
				'itemsCssClass'		 => 'table table-striped table-bordered dataTable mb0',
				'htmlOptions'		 => array('class' => 'panel panel-primary  compact'),
				'columns'			 => array(
					array('name'	 => 'created', 'value'	 => function($data) {
							echo $data['created'];
						}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'), 'header'			 => 'Date'),
					array('name'	 => 'amount', 'value'	 => function($data) {
							if ($data['ptp_id'] == '5')
							{
								echo "-" . round($data['amount'], 1);
							}
							else if ($data['ptp_id'] == '0')
							{
								echo "+" . round($data['amount'], 1);
							}
						}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'), 'header'			 => 'Debit/Credit'),
					array('name'	 => 'ucr_type', 'value'	 => function($data) {
							if ($data['ptp_id'] == '0')
							{
								// 1:promo,2:refund,3:referral,4:booking,5:others   
								switch ($data['ucr_type'])
								{
									case '1':
										echo "Promo";
										break;
									case '2':
										echo "Refund";
										break;
									case '3':
										echo "Referral";
										break;
									case '4':
										echo "Booking";
										break;
									case '5':
										echo "Others(Admin)";
										break;
									case '6':
										echo "Referred";
										break;
									case '7':
										echo "booking(CREDITS PER KM RIDDEN)";
										break;
									case '8':
										echo "booking(CREDITS EQUALS COD AMOUNT)";
										break;
									case '9':
										echo "Notification";
										break;
								}
							}
							else
							{
								echo $data['ucr_type'];
							}
						}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'), 'header'			 => 'Type'),
					array('name'	 => 'ucr_maxuse_type', 'value'	 => function($data) {
							$maxStr = UserCredits::model()->getMaxUseTypes($data['ucr_maxuse_type']);
							if ($data['ucr_max_use'] > 0)
							{
								//  $maxStr.=" (Max use: ".$data['ucr_max_use'].")";
							}
							return $maxStr;
						}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'), 'header'			 => 'Max Use'),
					array('name' => 'description', 'value' => $data['description'], 'sortable' => false, 'htmlOptions' => array('class' => 'text-left'), 'headerHtmlOptions' => array('class' => 'col-xs-6 text-center'), 'header' => 'Description'),
					array('name'	 => 'ucr_validity', 'value'	 => function($data) {
							if ($data['ucr_validity'] > date("Y-m-d H:i:s"))
							{
								echo date("d/m/Y H:i:s", strtotime($data['ucr_validity']));
							}
							else if ($data['ucr_validity'] < date("Y-m-d H:i:s"))
							{
								echo '<span class="text-danger"><i class="fa fa-close"></i>Expired</span>';
							}
							else
							{
								echo 'NA';
							}
						}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'), 'header'			 => 'Valid Upto'),
			)));
		}
		?>
	</div>
</div>

