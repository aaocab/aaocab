<div class="panel-advancedoptions" >
    <div class="row">
        <div class="col-md-12">            
            <div class="panel" >
                <div class="panel-body p0">
                    <div class="">
                        <h3 class="mt0">Transactions [Wallet Balance : ₹<?= $totalWalletBalance ?> ]
                            <!--<span class="pull-right">Last Trip Date : 12 mar 2021</span>-->
                        </h3>
						<?php
						$checkaccess = Yii::app()->user->checkAccess('accountEdit');
						if ($checkaccess)
						{
							?>
							<a class = "btn btn-info btn-sm text-center mr50" id = "setFlag" onclick = "addRefund()" title = "Add Refund" style = "">Add Refund</a>
							<a class = "btn btn-primary btn-sm text-center mr50" id = "setFlag" onclick = "addBalance()" title = "Add Balance" style = "">Add Balance</a>
						<?php } ?>




                        <div style="width: 100%; overflow: auto;  border: 1px #aaa solid;color: #444;">
							<?php
							if (!empty($walletBalance))
							{
								/* @var $dataProvider2 TbGridView */
								$params1								 = array_filter($_REQUEST);
								$walletBalance->getPagination()->params	 = $params1;
								$walletBalance->getSort()->params		 = $params1;
								$this->widget('booster.widgets.TbGridView', array(
									'responsiveTable'	 => true,
									'dataProvider'		 => $walletBalance,
									'pager'				 => ['maxButtonCount' => 5, 'class' => 'booster.widgets.TbPager'],
									'id'				 => 'walletListGrid',
									'template'			 => "<div class='panel-heading bg-primary text-white border border-primary'>
                                        <div class='row '>
                                        <div class='col-xs-12 col-sm-6   '>{summary}</div>
                                        <div class='col-xs-12 col-sm-6 text-right'>{pager}</div>
                                        </div></div>
                                        <div class='panel-body table-responsive'>{items}</div><div></div>",
									'itemsCssClass'		 => 'table table-striped table-bordered dataTable  ',
									'htmlOptions'		 => array('class' => 'panel panel-primary  compact'),
									'columns'			 => array(
										//array('name' => 'apg_booking_id', 'value' => $data['apg_booking_id'], 'sortable' => false, 'htmlOptions' => array('class' => 'text-left'), 'headerHtmlOptions' => array('class' => 'col-xs-3 text-center bg-primary text-white'), 'header' => 'Booking ID'),
										//array('name' => 'bkg_pickup_date', 'value' => 'date("d/m/Y h:iA",strtotime($data["bkg_pickup_date"]))', 'sortable' => false, 'htmlOptions' => array('class' => 'text-left'), 'headerHtmlOptions' => array('class' => 'col-xs-3 text-center bg-primary text-white'), 'header' => 'Pickup Date'),
										array('name' => 'apg_amount', 'value' => $data['apg_amount'], 'sortable' => false, 'htmlOptions' => array('class' => 'text-left'), 'headerHtmlOptions' => array(), 'header' => 'Adv collected'),
										array('name' => 'created', 'value' => 'date("d/m/Y h:iA",strtotime($data["created"]))', 'sortable' => false, 'htmlOptions' => array('class' => 'text-left'), 'headerHtmlOptions' => array(), 'header' => 'Transaction Date'),
										//array('name' => 'created', 'value' => 'date("d/m/Y h:iA",strtotime($data["created"]))', 'sortable' => false, 'htmlOptions' => array('class' => 'text-left'), 'headerHtmlOptions' => array(), 'header' => 'Created Date'),
										array('name' => 'adt_amount', 'value' => '(-1*$data["adt_amount"])', 'sortable' => false, 'htmlOptions' => array('class' => 'text-right'), 'headerHtmlOptions' => array(), 'header' => 'Amount'),
										array('name' => 'act_remarks', 'value' => $data['act_remarks'], 'sortable' => false, 'htmlOptions' => array('class' => 'text-left'), 'headerHtmlOptions' => array(), 'header' => 'Notes'),
									//      array('name' => 'adt_amount', 'value' => '(-1*$data["adt_amount"])', 'sortable' => false, 'htmlOptions' => array('class' => 'text-right'), 'headerHtmlOptions' => array('class' => 'col-xs-3 text-center bg-primary text-white'), 'header' => 'Running Balance'),
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
<script>
	$('#tripdetails-grid-<?= $qry['booking_id'] ?> .tScore .a1').click(function (e) {
		e.preventDefault();
		return showReturnDetails(this);
	});

	function addRefund() {

		var user_id = '<?= $consumerId ?>';

		$href = "<?= Yii::app()->createUrl('admin/transaction/refundWalletToCustomer') ?>";
		jQuery.ajax({type: 'GET',
			url: $href,
			data: {"user_id": user_id},
			success: function (data) {
				refndbox = bootbox.dialog({
					message: data,
					title: 'Add Refund',
					onEscape: function () {

					}
				});
				refndbox.on('hidden.bs.modal', function (e) {
					$('body').addClass('modal-open');
				});
			}
		});
	}
	function addBalance() {

		var user_id = '<?= $consumerId ?>';

		$href = "<?= Yii::app()->createUrl('admin/transaction/addBalance') ?>";
		jQuery.ajax({type: 'GET',
			url: $href,
			data: {"user_id": user_id},
			success: function (data) {
				refndbox = bootbox.dialog({
					message: data,
					title: 'Add Balance',
					onEscape: function () {


					}
				});
				refndbox.on('hidden.bs.modal', function (e) {
					$('body').addClass('modal-open');
				});
			}
		});
	}
</script>