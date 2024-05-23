<style>
	.table td, .table th{
		padding: 7px
	}
	.pagination {
		padding: 0;margin: 0;
	}
	.summary {
		padding: 7px
	}
</style>
<?php
$redeemUrl	 = Yii::app()->createAbsoluteUrl('/', ['credit' => '1']);
$walletUrl	 = Yii::app()->createAbsoluteUrl('/', ['wallet' => '1']);
?>
<div class="bg-white-box">
    <div class="row">
        <div class="col-12 col-lg-10 offset-lg-1" id="count_booking_row">
            <div class="row">
                <div class="col-6 font-18 border-widget-1">Total Gozo Coins:</div>
                <div class="col-6 font-18 text-right border-widget-1"><?= $totalAmount; ?></div>
            </div>
            <div class="row">
                <div class="col-6 font-18 pt10">Wallet Balance:</div>
                <div class="col-6 font-18 text-right pt10"><?= $walletBalance | 0; ?></div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-7"> 
			<?php
			if ($totalAmount != '' && $totalAmount > 0)
			{
				?>
				<a href="<?= $redeemUrl ?>" class="btn btn-primary text-uppercase gradient-green-blue font-12 border-none mt15" target="_blank" title="Redeem Now">Redeem Gozo Coins</a>
			<?php }
			?>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-7">    
			<?php
			if ($walletBalance != '' && $walletBalance > 0)
			{
				?>
				<a href="<?= $walletUrl ?>" class="btn btn-primary text-uppercase bg-yellow font-12 border-none mt15" target="_blank" title="Redeem Now">Redeem Wallet Balance</a>
				<?php
				/* wallet refund button
				<a href="javascript:void(0)" class="btn btn-success gozo_green font-12 border-none mt10  text-uppercase  walletBtn" id="walletBtn">Transfer Wallet Balance to my bank</a>
				<?php */
			}
			?>
        </div>
    </div>
</div>

<div class="bg-white-box mt30 mb30">
    <div id="tabs">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item active" id="otrip">
                <a href="#menu4" data-toggle="tab" class="nav-link active">Wallet Balance</a>
            </li>
            <li class="nav-item" id='rtrip'>
                <a href="#menu5" data-toggle="tab" class="nav-link">Gozo Coins</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane   active in pt5 pb5" id="menu4">			
                <div class="row">
                    <div class="col-12">
						<?php
						if (!empty($dataProvider3))
						{
							/* @var $dataProvider2 TbGridView */
							$params1								 = array_filter($_REQUEST);
							$dataProvider3->getPagination()->params	 = $params1;
							$dataProvider3->getSort()->params		 = $params1;
							$this->widget('booster.widgets.TbGridView', array(
								'responsiveTable'	 => true,
								'dataProvider'		 => $dataProvider3,
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
									array('name' => 'created', 'value' => 'date("d/m/Y h:iA",strtotime($data["created"]))', 'sortable' => false, 'htmlOptions' => array('class' => 'text-left'), 'headerHtmlOptions' => array('class' => 'col-xs-3 text-center bg-primary text-white'), 'header' => 'Date'),
									array('name' => 'adt_amount', 'value' => '(-1*$data["adt_amount"])', 'sortable' => false, 'htmlOptions' => array('class' => 'text-right'), 'headerHtmlOptions' => array('class' => 'col-xs-3 text-center bg-primary text-white'), 'header' => 'Amount'),
									array('name' => 'act_remarks', 'value' => $data['act_remarks'], 'sortable' => false, 'htmlOptions' => array('class' => 'text-left'), 'headerHtmlOptions' => array('class' => 'col-xs-3 text-center bg-primary text-white'), 'header' => 'Description'),
							)));
						}
						?>
                    </div>
                </div>
            </div>
            <div class="tab-pane in   pt5 pb5" id="menu5">
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
						'template'			 => "<div class='panel-heading border-primary bg-primary text-white'>
                            <div class='row m0'>
                            <div class='col-xs-12 col-sm-4 pt5'>Active Gozo Coins</div>
                            <div class='col-xs-12 col-sm-4 pr0'>{summary}</div>
                            <div class='col-xs-12 col-sm-4 pr0'>{pager}</div>
                        </div></div>
                        <div class='panel-body table-responsive'>{items}</div>",
						'itemsCssClass'		 => 'table table-striped table-bordered dataTable mb0',
						'htmlOptions'		 => array('class' => 'panel panel-primary  compact'),
						'columns'			 => array(
							array('name'	 => 'created', 'value'	 => function($data)
								{
									echo $data['created'];
								}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'), 'header'			 => 'Date'),
							array('name'	 => 'amount', 'value'	 => function($data)
								{
									if ($data['ptp_id'] == '5')
									{
										echo "-" . round($data['amount'], 1);
									}
									else if ($data['ptp_id'] == '0')
									{
										echo "+" . round($data['amount'], 1);
									}
								}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'), 'header'			 => 'Debit/Credit'),
							array('name'	 => 'ucr_type', 'value'	 => function($data)
								{
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
										if ($data['ptp_id'] == '5' && $data['ucr_type'] == 4)
										{
											echo " Credits Used in Booking";
										}
										else
										{
											echo $data['ucr_type'];
										}
									}
								}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'), 'header'			 => 'Type'),
							array('name'	 => 'ucr_maxuse_type', 'value'	 => function($data)
								{
									$maxStr = UserCredits::model()->getMaxUseTypes($data['ucr_maxuse_type']);
									if ($data['ucr_max_use'] > 0)
									{
										//  $maxStr.=" (Max use: ".$data['ucr_max_use'].")";
									}
									return $maxStr;
								}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'), 'header'			 => 'Max Use'),
							array('name' => 'description', 'value' => $data['description'], 'sortable' => false, 'htmlOptions' => array('class' => 'text-left'), 'headerHtmlOptions' => array('class' => 'col-xs-6 text-center'), 'header' => 'Description'),
							array('name'	 => 'ucr_validity', 'value'	 => function($data)
								{
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
				<?php
				if (!empty($dataProvider2))
				{
					/* @var $dataProvider2 TbGridView */
					$params									 = array_filter($_REQUEST);
					$dataProvider2->getPagination()->params	 = $params;
					$dataProvider2->getSort()->params		 = $params;
					$this->widget('booster.widgets.TbGridView', array(
						'responsiveTable'	 => true,
						'dataProvider'		 => $dataProvider2,
						'pager'				 => ['maxButtonCount' => 5, 'class' => 'booster.widgets.TbPager'],
						'id'				 => 'pendingListGrid',
						'template'			 => "<div class='panel-heading border-primary bg-primary text-white'><div class='row m0'>
                <div class='col-xs-12 col-sm-4 pt5'>Pending Gozo Coins</div>
                <div class='col-xs-12 col-sm-4 pr0'>{summary}</div>
                <div class='col-xs-12 col-sm-4 pr0'>{pager}</div>
                </div></div>
                <div class='panel-body table-responsive'>{items}</div><div></div>",
						'itemsCssClass'		 => 'table table-striped table-bordered dataTable mb0',
						'htmlOptions'		 => array('class' => 'panel panel-primary  compact'),
						'columns'			 => array(
							array('name'	 => 'created', 'value'	 => function($data)
								{
									echo $data['created'];
								}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'), 'header'			 => 'Date'),
							array('name'	 => 'amount', 'value'	 => function($data)
								{
									if ($data['ptp_id'] == '5')
									{
										echo "-" . round($data['amount'], 1);
									}
									else if ($data['ptp_id'] == '0')
									{
										echo "+" . round($data['amount'], 1);
									}
								}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'), 'header'			 => 'Debit/Credit'),
							array('name'	 => 'ucr_type', 'value'	 => function($data)
								{
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
										}
									}
									else
									{
										echo $data['ucr_type'];
									}
								}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'), 'header'			 => 'Type'),
							array('name'	 => 'ucr_maxuse_type', 'value'	 => function($data)
								{
									$maxStr = UserCredits::model()->getMaxUseTypes($data['ucr_maxuse_type']);
									if ($data['ucr_max_use'] > 0)
									{
										//  $maxStr.=" (Max use: ".$data['ucr_max_use'].")";
									}
									return $maxStr;
								}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'), 'header'			 => 'Max Use'),
							array('name' => 'description', 'value' => $data['description'], 'sortable' => false, 'htmlOptions' => array('class' => 'text-left'), 'headerHtmlOptions' => array('class' => 'col-xs-6 text-center'), 'header' => 'Description'),
					)));
				}
				?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="transform" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-xsm" role="document"  >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><span class="float-left" id="modal_title"></span>
					<button type="button" class="close float-right" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button></h5>
            </div>
            <div class="modal-body" id="transformbody">

            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

	$('#walletBtn').click(function () {

		var href2 = "<?php echo Yii::app()->createUrl('users/transfer') ?>";
		$.ajax({
			"url": href2,
			"data": {"desktheme": 1},
			"type": "GET",
			"dataType": "html",
			"success": function (data) {
				$('#transform').removeClass('fade');
				$('#transform').css("display", "block");
				$('#transformbody').html(data);
				$('#transform').modal('show');
			}
		});
		return false;
	});
	$('#bankBtn').click(function () {

		$.ajax({
			"type": "GET",

			"url": "<?php echo Yii::app()->createUrl('users/transfer') ?>",
			"data": {"desktheme": 1,"showbankDetails": 1},
			"dataType": "html",
			"success": function (data) {

				$('#transform').removeClass('fade');
				$('#transform').css("display", "block");
				$('#transformbody').html(data);
				$('#transform').modal('show');
			}

		});
	});
</script>