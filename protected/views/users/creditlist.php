<style>
    a {
        color: #000;
        text-decoration: none;
    }
	.nav-tabs  a {
        font-size:11.3px !important;       
    }
    .nav-tabs>li>a {
        padding: 10px 8px;
    }
	.arrow_box2 > .nav-tabs > li.active > a, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus{
		background-color: #337ab7 !important;
		color: #fdfdfd !important;
	}
	.modal-dialog.modal-xsm {
		width: 500px !important;
	}
</style>
<?php
$redeemUrl	 = Yii::app()->createAbsoluteUrl('/', ['credit' => '1']);
$walletUrl	 = Yii::app()->createAbsoluteUrl('/', ['wallet' => '1']);
$transferUrl = Yii::app()->createUrl('users/transfer');
?>
<div class="row" style="margin-top: 10px">
    <div class="col-xs-12 col-sm-6 col-md-5">       
        <table class="table table-bordered" style="">
            <tbody id="count_booking_row">                         
                <tr style="color: black;background: whitesmoke">
                    <td style="font-style: italic; text-align: center; width: 50%"><b>Total Gozo Coins :</b> </td>
                    <td style=""><?= $totalAmount; ?></td>
                </tr>
				<tr style="color: black;background: whitesmoke">
                    <td style="font-style: italic; text-align: center; width: 50%"><b>Wallet Balance :</b> </td>
                    <td style=""><?= $walletBalance | 0; ?></td>
                </tr>
            </tbody>
        </table>
    </div>
	<div class="col-xs-12 col-sm-6 col-md-7"> 
		<?php
		if ($totalAmount != '' && $totalAmount > 0)
		{
			?>
			<button type="button" class="btn btn-success gozo_green border-none text-uppercase mb10" id="redeemBtn" name="redeemBtn" title="Redeem Now"><a href="<?= $redeemUrl ?>" class="white-color" target="_blank">Redeem Gozo Coins</a></button>
		<?php }
		?>
	</div>
    <div class="col-xs-12 col-sm-6 col-md-7">    
		<?php
		if ($walletBalance != '' && $walletBalance > 0)
		{
			?>
			<button type="button" class="btn btn-success gozo_green border-none text-uppercase mb10" id="redeemBtn" name="redeemBtn" title="Redeem Now"><a href="<?= $walletUrl ?>" class="white-color" target="_blank">Redeem Wallet Balance</a></button>

			<a href="javascript:void(0)" class="btn btn-success gozo_green border-none text-uppercase mb10 walletBtn" id="walletBtn">Transfer Wallet Balance to my bank</a>
		<?php }
		?>
    </div>
</div>
<div class="row" style="margin-top: 10px">
	<div id="tabs">
		<ul class="nav nav-tabs">
			<li class="active" id="otrip"><a href="#menu4" data-toggle="tab">Wallet Balance</a></li>
			<li class="" id='rtrip'><a href="#menu5" data-toggle="tab">Gozo Coins</a></li>
		</ul>
		<div class="tab-content col-xs-12" style="height: 100%">
			<div class="tab-pane fade active in home-search" id="menu4">			
				<div class="row">
					<div class="col-xs-12">
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
								'template'			 => "<div class='panel-heading'><div class='row m0'>
									<div class='col-xs-12 col-sm-4 pr0'>{summary}</div>
									<div class='col-xs-12 col-sm-4 pr0'>{pager}</div>
									</div></div>
									<div class='panel-body table-responsive'>{items}</div><div></div>",
								'itemsCssClass'		 => 'table table-striped table-bordered dataTable mb0',
								'htmlOptions'		 => array('class' => 'panel panel-primary  compact'),
								'columns'			 => array(
									array('name' => 'created', 'value' => 'date("d/m/Y H:i:s",strtotime($data["created"]))', 'sortable' => false, 'htmlOptions' => array('class' => 'text-left'), 'headerHtmlOptions' => array('class' => 'col-xs-3 text-center'), 'header' => 'Date'),
									array('name' => 'adt_amount', 'value' => '(-1*$data["adt_amount"])', 'sortable' => false, 'htmlOptions' => array('class' => 'text-left'), 'headerHtmlOptions' => array('class' => 'col-xs-3 text-center'), 'header' => 'Amount'),
									array('name' => 'act_remarks', 'value' => $data['act_remarks'], 'sortable' => false, 'htmlOptions' => array('class' => 'text-left'), 'headerHtmlOptions' => array('class' => 'col-xs-3 text-center'), 'header' => 'Description'),
							)));
						}
						?>
					</div>
				</div>
			</div>
			<div class="tab-pane fade in  home-search1 pt5 pb5" id="menu5">
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
						'template'			 => "<div class='panel-heading'><div class='row m0'>
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
</script>