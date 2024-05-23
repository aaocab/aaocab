
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
<div class="panel panel-default m0">
	<div class="panel-heading">Gozo Coins List</div>
	<div class="panel-body">
		<?php
		if ($dataProvider != "")
		{
			$this->widget('booster.widgets.TbGridView', [
				'id'				 => 'credits-grid',
				'dataProvider'		 => $dataProvider,
				'responsiveTable'	 => true,
				'filter'			 => $model,
				'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact mt20'),
				'itemsCssClass'		 => 'table table-striped table-bordered mb0 ',
				'template'			 => "<div class='panel-heading' style='padding: 0px'><div class='row m0'>
            <div class='col-xs-12 col-sm-6 pt20'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
            </div></div>
            <div class='panel-body'>{items}</div>
            <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
				'columns'			 => [
					['name' => 'user_name', 'value' => '$data->ucrUsers->usr_name', 'headerHtmlOptions' => ['class' => 'col-xs-2']],
					['name' => 'ucr_value', 'value' => '$data->ucr_value', 'headerHtmlOptions' => ['class' => 'col-xs-1'], 'htmlOptions' => ['style' => 'text-align:center']],
					['name' => 'ucr_desc', 'headerHtmlOptions' => ['class' => 'col-xs-2']],
					['name' => 'ucr_status', 'value' => '($data->ucr_status==1)?"Active":"Inactive"', 'headerHtmlOptions' => ['class' => 'col-xs-1'], 'htmlOptions' => ['style' => 'text-align:center']],
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
								case 4:
									echo $data->ucrBooking->bkg_booking_id;
									break;
								case 5:
									echo $data->ucrAdmin->adm_fname . " " . $data->ucrAdmin->adm_lname;
									break;
								case 6:
									echo $data->ucrReferral->usr_name . " " . $data->ucrReferral->usr_lname;
									break;
								default :
									echo 'NA';
							}
						}, 'headerHtmlOptions'	 => ['class' => 'col-xs-1']],
					['name' => 'ucr_used', 'headerHtmlOptions' => ['class' => 'col-xs-1'], 'filter' => false, 'htmlOptions' => ['style' => 'text-align:center']],
					['name' => 'ucr_validity', 'headerHtmlOptions' => ['class' => 'col-xs-1'], 'filter' => false],
					['name' => 'ucr_max_use', 'headerHtmlOptions' => ['class' => 'col-xs-1'], 'filter' => false, 'htmlOptions' => ['style' => 'text-align:center']],
					['name' => 'ucr_created', 'headerHtmlOptions' => ['class' => 'col-xs-1'], 'filter' => false],
				]
			]);
		}
		?>
	</div>
</div>

