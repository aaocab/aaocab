<div class="row">

    <div class="col-xs-12">
		<?php
		$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'sms-form', 'enableClientValidation' => true,
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
        <div class="well pb20">
            <div class="col-xs-6 col-md-4"> 
				<? //= $form->textFieldGroup($model, 'number', array('widgetOptions' => ['htmlOptions' => []])) ?>
            </div><div class="col-xs-6 col-md-4"> 
				<? //= $form->textFieldGroup($model, 'message', array('widgetOptions' => ['htmlOptions' => []])) ?>
            </div><div class="col-xs-6 col-md-4"> 
				<? //= $form->textFieldGroup($model, 'booking_id', array('widgetOptions' => ['htmlOptions' => []])) ?>
            </div>
            <div class="col-xs-12 text-center mb20">
                <button class="btn btn-primary" type="submit" style="width: 185px;"  name="bookingSearch">Search</button>
            </div>
        </div>


		<?php $this->endWidget(); ?>
    </div>
    <div class="col-xs-12">
		<?php
		if (!empty($dataProvider))
		{
			$this->widget('booster.widgets.TbGridView', array(
				'responsiveTable'	 => true,
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
					array('name' => 'trl_bkg_id', 'value' => '$data->trl_bkg_id', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Booking ID'),
					array('name' => 'trl_drv_id', 'value' => '$data->trlDrv->drv_name', 'headerHtmlOptions' => array(), 'header' => 'Driver'),
					array('name'	 => 'trl_platform',
						'value'	 => function($data) {
							echo $data->getPlatform();
						}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Platform'),
					array('name'				 => 'trl_phNumber',
						'value'				 => '$data->trl_phNumber', 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Number'),
//					array('name' => 'date_sent',
//						'value' => function ($data) {
//							return DateTimeFormat::DateTimeToLocale($data->date_sent);
//						},
//						'sortable' => true, 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Date Sent'),
			)));
		}
		?>

    </div></div>