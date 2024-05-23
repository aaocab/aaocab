<style type="text/css">
    .cityinput > .selectize-control>.selectize-input{
        width:100% !important;
    }
</style>
<div class="row">
	<div class="panel" >
		<div class="panel-body">
			<?php
			$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
				'id'					 => 'accountReport-form', 'enableClientValidation' => true,
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

			<div class="col-xs-12">
				<?php
				if (!empty($dataProvider))
				{
					$this->widget('booster.widgets.TbGridView', array(
						'id'				 => 'trip-grid',
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
							array('name' => 'bkg_id', 'value' => $data['bkg_id'], 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Booking Id'),
							array('name' => 'bcb_vendor_id', 'value' => '$data["bcb_vendor_id"]', 'headerHtmlOptions' => array(), 'header' => 'Vendor Id'),
							array('name' => 'bkg_agent_id', 'value' => '$data["bkg_agent_id"]', 'headerHtmlOptions' => array(), 'header' => 'Agent Id'),
							array('name' => 'bkg_pickup_date', 'value' => '$data["bkg_pickup_date"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Pickup Date'),
							array('name' => 'bkg_total_amount', 'value' => '$data["bkg_total_amount"]', 'headerHtmlOptions' => array(), 'header' => 'Total Bkg Amount'),
							array('name' => 'bkg_net_advance_amount', 'value' => '$data["bkg_net_advance_amount"]', 'headerHtmlOptions' => array(), 'header' => 'Advance'),
							array('name' => 'bkg_vendor_collected', 'value' => '$data["bkg_vendor_collected"]', 'headerHtmlOptions' => array(), 'header' => 'Driver To Collect'),
							array('name' => 'driverCollectAccountEntryAmt', 'value' => '$data["driverCollectAccountEntryAmt"]', 'headerHtmlOptions' => array(), 'header' => 'Driver Collected'),
							array('name' => 'DebitIds', 'value' => '$data["DebitIds"]', 'headerHtmlOptions' => array(), 'header' => 'Act Ids'),
					)));
				}
				?>
			</div>

			<?php $this->endWidget(); ?>
		</div>
	</div>
</div>