<section id="section7">
    <div class="container">
        <div class="row">
			<?php
			$form	 = $this->beginWidget('booster.widgets.TbActiveForm', array(
				'id'					 => 'vendorListForm', 'enableClientValidation' => true,
				'clientOptions'			 => array(
					'validateOnSubmit'	 => false,
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
            <div class="row">
                <div class="col-xs-12 col-sm-4 col-md-3">
					<?=
					$form->datePickerGroup($model, 'ven_from_date', array('label' => '', 'widgetOptions' => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'From Date', 'value' => $fromDate)), 'prepend' => '<i class="fa fa-calendar"></i>'));
					?>
                </div>
                <div class="col-xs-12 col-sm-4 col-md-3">
					<?=
					$form->datePickerGroup($model, 'ven_to_date', array('label'			 => '',
						'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'To Date', 'value' => $toDate)), 'prepend'		 => '<i class="fa fa-calendar"></i>'));
					?>
                </div>
                <div class="col-xs-12 col-sm-4 col-md-3">
                    <button type="button" class="btn btn-success" onclick="sendVendorPdf();">Bulk Invoices</button>
                </div>
            </div>

            <div class="profile-right-panel p20">
                <div class="row table table-bordered">
                    <div class="row">
						<?php
						$ctr	 = 1;
						if (!empty($dataProvider))
						{
							$checkContactAccess						 = Yii::app()->user->checkAccess("bookingContactAccess");
							$params									 = array_filter($_REQUEST);
							$dataProvider->getPagination()->params	 = $params;
							$dataProvider->getSort()->params		 = $params;
							$this->widget('booster.widgets.TbGridView', array(
								'responsiveTable'	 => true,
								'dataProvider'		 => $dataProvider,
								'id'				 => 'vendorListGrid',
								'template'			 => "<div class='panel-heading'><div class='row m0'>
                                                        <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                                    </div></div>
                                                    <div class='panel-body table-responsive'>{items}</div>
                                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
								'itemsCssClass'		 => 'table table-striped table-bordered dataTable mb0',
								'htmlOptions'		 => array('class' => 'panel panel-primary  compact'),
								'columns'			 => array(
									array(
										'class'				 => 'CCheckBoxColumn',
										'header'			 => 'html',
										'id'				 => 'vendor_id',
										'selectableRows'	 => '{items}',
										'selectableRows'	 => 2,
										'value'				 => '$data["vnd_id"]',
										'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
										'headerTemplate'	 => '<label>{item}<span></span></label>',
										'htmlOptions'		 => array('style' => 'width: 5px', 'class' => 'text-center'),
									),
									array('name'	 => 'vendor_name', 'value'	 => function($data) {
											echo CHtml::link($data['vendor_name'], Yii::app()->createUrl('admin/vendor/vendoraccount/', ['vnd_id' => $data['vnd_id'], 'ven_from_date' => $GLOBALS['venFromDate'], 'ven_to_date' => $GLOBALS['venToDate']]));
										}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-4'), 'header'			 => 'Vendor Name'),
									array('name'	 => 'vendorTotalTrips', 'value'	 => function($data) {
											echo $data['vendorTotalTrips'];
										}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => '# Trips'),
									array('name'	 => 'vendor_amount', 'value'	 => function($data) {
											if ($data['vendor_amount'] > 0)
											{
												echo '<i class="fa fa-inr"></i>' . round($data['vendor_amount']);
											}
										}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Payable'),
									array('name'	 => 'vendor_amount', 'value'	 => function($data) {
											if ($data['vendor_amount'] < 0)
											{
												echo '<i class="fa fa-inr"></i>' . round($data['vendor_amount']);
											}
										}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Receivable'),
									array('name'	 => 'vnd_credit_limit', 'value'	 => function($data) {
											if ($data['vnd_credit_limit'] > 0)
											{
												echo '<i class="fa fa-inr"></i>' . round($data['vnd_credit_limit']);
											}
											else
											{
												echo '<i class="fa fa-inr"></i>0';
											}
										}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Credit Limit'),
							)));
							$ctr = ($ctr + 1);
						}
						?>
					</div>
				</div>
			</div>
			<?php $this->endWidget(); ?>
		</div>
	</div>
</section>
<script type="text/javascript">
    $("#vendor_id_all").click(function () {
        if (this.checked) {
            $('#vendorListGrid .checker span').addClass('checked');
            $('#vendorListGrid input[name="vendor_id[]"]').attr('checked', 'true');
        } else {
            $('#vendorListGrid .checker span').removeClass('checked');
            $('#vendorListGrid input[name="vendor_id[]"]').attr('checked', 'false');
        }
    });
    var checkCounter = 0;
    var checked = [];
    function sendVendorPdf() {
        var fromDate = $('#VendorTransactions_ven_from_date').val();
        var toDate = $('#VendorTransactions_ven_to_date').val();
        checked = [];
        $('#vendorListGrid input[name="vendor_id[]"]').each(function (i) {
            if (this.checked) {
                checked.push(this.value);
            }
        });
        if (checked.length == 0) {
            bootbox.alert("Please select a vendor for generate Pdf.");
            return false;
        }
        if (checked.length > 0) {
            var j = 0;
            var checked1 = [];
            while (j < 5 && checkCounter < checked.length) {
                checked1.push(checked[checkCounter]);
                j++;
                checkCounter++;
            }
            markCompleteAjax(checked1, fromDate, toDate);
        }
    }


    function markCompleteAjax(checkedIds, fromDate, toDate) {
        ajaxindicatorstart("Processing " + checkCounter.toString() + " of " + checked.length.toString() + "");
        var href = '<?= Yii::app()->createUrl("admin/vendor/generatevendorpdf"); ?>';
        $.ajax({
            'type': 'GET',
            'url': href,
            'dataType': 'json',
            global: false,
            data: {"vndIds": checkedIds.toString(), "vndFromDate": fromDate, "vndToDate": toDate},
            success: function (data) {
                if (data.success) {
                    if (checkCounter >= checked.length)
                    {
                        ajaxindicatorstop();
                        checkCounter = 0;
                    } else
                    {
                        sendVendorPdf();
                    }
                    refreshVendorListGrid();
                } else {
                    ajaxindicatorstop();
                    checkCounter = 0;
                    alert("Sorry error occured");
                }
            },
            error: function (xhr, status, error) {
                ajaxindicatorstop();
                checkCounter = 0;
                alert(xhr.error);
            }
        });
    }


    function refreshVendorListGrid() {
        $('#vendorListGrid').yiiGridView('update');
    }
</script>
