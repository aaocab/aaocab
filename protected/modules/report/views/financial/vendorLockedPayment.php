<style type="text/css">
    .cityinput > .selectize-control>.selectize-input{
        width:100% !important;
    }
</style>
<div class="row">
	<div class="panel" >
		<div class="panel-body">	
			<?php
			$checkExportAccess = false;
			if ($roles['rpt_export_roles'] != null)
			{
				$checkExportAccess = Filter::checkACL($roles['rpt_export_roles']);
			}
			if ($checkExportAccess)
			{
				echo CHtml::beginForm(Yii::app()->createUrl('report/financial/vendorLockedPayment'), "post", ['style' => "margin-bottom: 10px;margin-top: 10px;"]);
				?>
				<input type="hidden" id="export" name="export" value="true"/>
				<button class="btn btn-default" type="submit" style="width: 185px;">Export</button>
				<?php
				echo CHtml::endForm();
			}

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


			<div class="col-xs-12 col-sm-6 col-md-6">
				<div class="form-group">
					<div class="col-xs-12 col-sm-6">
						<?= $form->textFieldGroup($model, 'vnd_code', array('label' => 'Vendor code', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Vendor code', 'class' => 'form-control', 'title' => '')))) ?>
					</div>			
				</div>
			</div>
			<div class="col-xs-12 col-sm-3 mt5"><br>
				<button class="btn btn-primary full-width" type="submit"  name="accountReport">Search</button>
			</div>

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
							array('name' => 'bkg_ids', 'value' => $data['bkg_ids'], 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Booking Ids'),
							array('name' => 'bcb_id', 'value' => '$data["bcb_id"]', 'headerHtmlOptions' => array(), 'header' => 'Trip Id'),
							array('name' => 'agt_company_names', 'value' => '$data["agt_company_names"]', 'headerHtmlOptions' => array(), 'header' => 'Agent'),
							array('name' => 'bkg_pickup_dates', 'value' => '$data["bkg_pickup_dates"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Pickup Date'),
							array('name' => 'vnd_id', 'value' => '$data["vnd_id"]', 'headerHtmlOptions' => array(), 'header' => 'Vendor Id'),
							array('name' => 'vnd_name', 'value' => '$data["vnd_name"]', 'headerHtmlOptions' => array(), 'header' => 'Vendor Name'),
							array('name' => 'vnd_code', 'value' => '$data["vnd_code"]', 'headerHtmlOptions' => array(), 'header' => 'Vendor Code'),
							array('name' => 'bcb_vendor_amount', 'value' => '$data["bcb_vendor_amount"]', 'headerHtmlOptions' => array(), 'header' => 'Trip Vendor Amount'),
							array('name' => 'blg_desc', 'value' => '$data["blg_desc"]', 'headerHtmlOptions' => array(), 'header' => 'Reason'),
					)));
				}
				?>
			</div>

			<?php $this->endWidget(); ?>
		</div>
	</div>
</div>