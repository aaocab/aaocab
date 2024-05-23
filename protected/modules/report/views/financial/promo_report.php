<style>
	.table-flex {
		display: flex;
		flex-direction: column;
	}
	.tr-flex {
		display: flex;
	}
	.th-flex, .td-flex{
		flex-basis: 35%;
	}
	.thead-flex, .tbody-flex {
		overflow-y: scroll;
	}
	.tbody-flex {
		max-height: 250px;
	}
</style>
<?php
$form			 = $this->beginWidget('booster.widgets.TbActiveForm', array(
	'id'					 => 'promo-report', 'enableClientValidation' => true,
	'clientOptions'			 => array(
		'validateOnSubmit'	 => true,
		'errorCssClass'		 => 'has-error'
	),
	'enableAjaxValidation'	 => false,
	'errorMessageCssClass'	 => 'help-block',
	'htmlOptions'			 => array(
		'class' => '',
	),
		));
/* @var $form TbActiveForm */
?>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="col-xs-6 col-sm-4 col-lg-3">
			<?
			$daterangcreate	 = "Select Booking Create Date Range";
			$createdate1	 = ($model->from_date_create == '') ? '' : $model->from_date_create;
			$createdate2	 = ($model->to_date_create == '') ? '' : $model->to_date_create;
			if ($createdate1 != '' && $createdate2 != '')
			{
				$daterangcreate = date('F d, Y', strtotime($createdate1)) . " - " . date('F d, Y', strtotime($createdate2));
			}
			?>
			<label  class="control-label">Booking Create Date Range</label>
			<div id="bkgCreateDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
				<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
				<span><?= $daterangcreate ?></span> <b class="caret"></b>
			</div>
			<?
			echo $form->hiddenField($model, 'from_date_create');
			echo $form->hiddenField($model, 'to_date_create');
			?>
		</div>
		<div class="col-xs-6 col-sm-4 col-lg-3">
			<?
			$daterangpickup	 = "Select Booking Pickup Date Range";
			$pickupdate1	 = ($model->from_date_pickup == '') ? '' : $model->from_date_pickup;
			$pickupdate2	 = ($model->to_date_pickup == '') ? '' : $model->to_date_pickup;
			if ($pickupdate1 != '' && $pickupdate2 != '')
			{
				$daterangpickup = date('F d, Y', strtotime($pickupdate1)) . " - " . date('F d, Y', strtotime($pickupdate2));
			}
			?>
			<label  class="control-label">Booking Pickup Date Range</label>
			<div id="bkgPickupDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
				<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
				<span><?= $daterangpickup ?></span> <b class="caret"></b>
			</div>
			<?
			echo $form->hiddenField($model, 'from_date_pickup');
			echo $form->hiddenField($model, 'to_date_pickup');
			?>
		</div>
		<div class="col-xs-12 col-md-4">
			<div class="form-group">
				<label class="control-label">Promos</label>
				<?php
				$promoList = Promos::model()->getPromoList();
				$this->widget('booster.widgets.TbSelect2', array(
					'model'			 => $model,
					'attribute'		 => 'prm_id',
					'val'			 => $model->prm_id,
					//'data'			 => $promoList,
					'asDropDownList' => FALSE,
					'options'		 => array('data' => new CJavaScriptExpression($promoList)),
					'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => '',
						'placeholder'	 => 'Select Promo')
				));
				?>
			</div>
		</div>
        <div class="col-xs-12 text-center">
			<?php echo CHtml::submitButton('Search', array('class' => 'btn btn-primary btn-5x pr30 pl30')); ?>
        </div>

    </div>
</div>
<div class="panel">
	<div class="panel-body">
		<div class="row" style="margin-top: 10px">  
			<div class="col-xs-12 col-sm-12 col-md-12">       
				<table class="table table-bordered table-flex">
					<thead class="thead-flex">
						<tr style="color: black;background: whitesmoke" class="tr-flex">
							<th class="th-flex"><u>Promo Code</u></th>
							<th class="th-flex"><u>B2C</u></th>
							<th class="th-flex"><u>B2B</u></th>
							<th class="th-flex"><u>Confirmed</u></th>
							<th class="th-flex"><u>Total</u></th>
						</tr>
					</thead>
					<tbody class="tbody-flex">                         

						<?php
						foreach ($countPromos as $countPromo)
						{
							?>
							<tr class="tr-flex">
								<td class="td-flex"><?= $countPromo['promoCode'] ?></td>
								<td class="td-flex"><?= $countPromo['BtoC'] ?></td>
								<td class="td-flex"><?= $countPromo['BtoB'] ?></td>
								<td class="td-flex"><?= $countPromo['ConfirmTot'] ?></td>
								<td class="td-flex"><?= $countPromo['BtoC'] + $countPromo['BtoB'] ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<?php $this->endWidget(); ?>
<?php
$checkExportAccess = false;
if ($roles['rpt_export_roles'] != null)
{
	$checkExportAccess = Filter::checkACL($roles['rpt_export_roles']);
}
if ($checkExportAccess)
{
	?>
	<?= CHtml::beginForm(Yii::app()->createUrl('report/financial/promoReport'), "post", ['style' => "margin-bottom: 10px;"]); ?>
	<input type="hidden" id="export1" name="export1" value="true"/>
	<input type="hidden" id="from_date_create" name="from_date_create" value="<?= $model->from_date_create ?>"/>
	<input type="hidden" id="to_date_create" name="to_date_create" value="<?= $model->to_date_create ?>"/>
	<input type="hidden" id="from_date_pickup" name="from_date_pickup" value="<?= $model->from_date_pickup ?>"/>
	<input type="hidden" id="to_date_pickup" name="to_date_pickup" value="<?= $model->to_date_pickup ?>"/>
	<input type="hidden" id="prm_id" name="prm_id" value="<?= $model->prm_id ?>"/>
	<button class="btn btn-default" type="submit" style="width: 185px;">Export Below Table</button>

	<?php
	echo CHtml::endForm();
}
?>
<br/>
<?php
if ($dataProvider != "")
{
	$this->widget('booster.widgets.TbGridView', [
		'id'				 => 'promo-grid',
		'dataProvider'		 => $dataProvider,
		'responsiveTable'	 => true,
		'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
		'itemsCssClass'		 => 'table table-striped table-bordered mb0',
		'template'			 => "<div class='panel-heading'><div class='row m0'>
            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
            </div></div>
            <div class='panel-body'>{items}</div>
            <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
		'columns'			 => [
			['name' => 'promoCode', 'filter' => false, 'headerHtmlOptions' => ['class' => 'col-xs-2'], 'header' => 'Promo Code'],
			['name' => 'bookingId', 'filter' => false, 'headerHtmlOptions' => ['class' => 'col-xs-2'], 'header' => 'Booking Id'],
			['name' => 'UserName', 'filter' => false, 'headerHtmlOptions' => ['class' => 'col-xs-2'], 'header' => 'User Name'],
			['name' => 'UserType', 'filter' => false, 'headerHtmlOptions' => ['class' => 'col-xs-1'], 'header' => 'User Type'],
			['name' => 'status', 'filter' => false, 'headerHtmlOptions' => ['class' => 'col-xs-1'], 'header' => 'Booking Status'],
			['name' => 'pickupDate', 'filter' => false, 'headerHtmlOptions' => ['class' => 'col-xs-2'], 'header' => 'Pickup Date'],
			['name' => 'createDate', 'filter' => false, 'headerHtmlOptions' => ['class' => 'col-xs-2'], 'header' => 'Create Date'],
		]
	]);
}
?>




<script type="text/javascript">
    var startCreate = '<?= date('d/m/Y', strtotime('-1 month')); ?>';
    var endCreate = '<?= date('d/m/Y'); ?>';
    var startPickup = '<?= date('d/m/Y', strtotime('-1 month')); ?>';
    var endPickup = '<?= date('d/m/Y'); ?>';

    $('#bkgCreateDate').daterangepicker(
            {
                locale: {
                    format: 'DD/MM/YYYY',
                    cancelLabel: 'Clear'
                },
                "showDropdowns": true,
                "alwaysShowCalendars": true,
                startDate: startCreate,
                endDate: endCreate,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                }
            }, function (start1, end1)
    {
        $('#Promos_from_date_create').val(start1.format('YYYY-MM-DD'));
        $('#Promos_to_date_create').val(end1.format('YYYY-MM-DD'));
        $('#bkgCreateDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
    });
    $('#bkgCreateDate').on('cancel.daterangepicker', function (ev, picker)
    {
        $('#bkgCreateDate span').html('Select Booking Create Date Range');
        $('#Promos_from_date_create').val('');
        $('#Promos_to_date_create').val('');
    });

    $('#bkgPickupDate').daterangepicker(
            {
                locale: {
                    format: 'DD/MM/YYYY',
                    cancelLabel: 'Clear'
                },
                "showDropdowns": true,
                "alwaysShowCalendars": true,
                startDate: startPickup,
                endDate: endPickup,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                }
            }, function (start1, end1)
    {
        $('#Promos_from_date_pickup').val(start1.format('YYYY-MM-DD'));
        $('#Promos_to_date_pickup').val(end1.format('YYYY-MM-DD'));
        $('#bkgPickupDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
    });
    $('#bkgPickupDate').on('cancel.daterangepicker', function (ev, picker)
    {
        $('#bkgPickupDate span').html('Select Booking Pickup Date Range');
        $('#Promos_from_date_pickup').val('');
        $('#Promos_to_date_pickup').val('');
    });


</script>