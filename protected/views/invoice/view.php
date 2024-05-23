
<?php
if ($invoiceList)
{
	if (date($invoiceList['bkg_pickup_date']) < date('Y-m-d H:i:s') && in_array($invoiceList['bkg_status'], [5, 6, 7, 9]))
	{
		$address	 = Config::getGozoAddress()
		?>
		<link rel="stylesheet" href="/css/font-awesome/css/font-awesome.css" type="text/css">
		<link rel="stylesheet" href="/css/site.min.css" type="text/css">
		<link rel="stylesheet" href="/css/component.css" type="text/css"/>
		<link rel="stylesheet" href="/css/hover.css" media="all" type="text/css">
		<link rel="stylesheet" href="/css/site.css?v=<?= Yii::app()->params['sitecssVersion']; ?>">
		<style type="text/css">
			.table-responsive td,.table-responsive .table td{ font-size: 12px!important; line-height: 1.3em  } 
			/*.table-responsive .table td{ font-size: 12px!important; padding: 8px 10px;}*/
			.invoice_box{ border: 1px solid #e4e4e4;}
			.invoice_white { background-color: #fff; }
		</style>
		<div class="container invoice_white ">
			<div class="row">
				<div class="col-xs-12 pt10 pb10 invoice_box">
					<?php
					$this->renderPartial("//invoice/header", ['invoiceList' => $invoiceList,
						'isPDF' => $isPDF], false);
					$patSetdata	 = PartnerSettings::model()->getValueById($invoiceList['bkg_agent_id']);
					if($patSetdata['pts_send_invoice_to'] == 1)//22311
					{
					   $this->renderPartial("//invoice/partnerContent", ['invoiceList' => $invoiceList,
						'totPartnerCredit' => $totPartnerCredit,
						'totAdvance'       => $totAdvance,
						'totAdvanceOnline' => $totAdvanceOnline,
						'column'           => 2,
						'isPDF'            => $isPDF], false);
					}
					else if ($invoiceList['bkg_status'] == 9)
					{

						$this->renderPartial("//invoice/cancelledView", ['invoiceList' => $invoiceList,
							'totPartnerCredit' => $totPartnerCredit,
							'totAdvance'       => $totAdvance,
							'totAdvanceOnline' => $totAdvanceOnline,
							'column'           => 2,
							'isPDF'            => $isPDF], false);
					}
					else
					{
					   $this->renderPartial("//invoice/content", ['invoiceList' => $invoiceList,
						'totPartnerCredit' => $totPartnerCredit,
						'totAdvance'       => $totAdvance,
						'totAdvanceOnline' => $totAdvanceOnline,
						'column'           => 2,
						'isPDF'            => $isPDF], false);
					}
					$gst = Filter::getGstin($invoiceList['bkg_pickup_date']);
					$this->renderPartial("//invoice/footer", ['gst' => $gst, 'address' => $address], false);
					?>
				</div>
				<?
				if ($isPDF)
				{
					?>
					<div class="col-xs-12 pt10 pb10 ">

						<?php
						$this->renderPartial("//invoice/paydocs", ['bkgId' => $invoiceList['bkg_id']], false);
						?>
					</div>
				<? } ?>

			</div>

		</div>

		<?
	}
	else
	{
		?> 
		Your invoice will be generated after your trip is successfully completed with us. You can also view or download it from your booking history by signing in your user account.
		<?
	}
}
else
{
	?> 
	The link is no longer active.<br>The invoice may be generated only after your trip is successfully completed with us. You can also view or download it from your booking history  by signing in your user account.<br>For any help please contact our customer care.
<? } ?>

