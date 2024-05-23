<?php
if ($invoiceList)
{
	?>
	<style>
		.no-border{border:0}.header{font-family:verdana;font-size:8pt}.header table td{padding:5px}.header .leftHeader{min-width:260px;width:40%;padding-right:20px}.header .leftHeader table,.header .rightHeader table{border-collapse:collapse}.header .leftHeader table tr td,.header .rightHeader table tr td{border:solid 1px #444}.header .leftHeader table tr td.label,.header .rightHeader table tr td.label{font-weight:700}.header .rightHeader{min-width:400px;width:60%}table.borderless tbody tr td{border:0}.invoice_box{width:100%;margin-top:10px;font-family:verdana;font-size:8pt;border-collapse:collapse}.invoice_box tr td{padding:5px;border:solid 1px #ddd}.invoice_box tr:nth-child(odd){background-color:#e5e5e5}
	</style>
	<style type="text/css">
		body {
			color: #636363;
			font-family: 'Open Sans', sans-serif;
			font-size: 13px;
			font-weight: 400;
			line-height: 22px;
			position: relative;
			background: #fff;
		}
		.table-responsive td,.table-responsive .table td{ font-size: 12px!important; line-height: 1.3em  } 
		.invoice_box{ border: 1px solid #e4e4e4;}
		.invoice_white { background-color: #fff; }
		.invoice_box tr:nth-child(odd) {
			background-color: none;
		}
		.invoice_box{ padding: 15px;}
		.text-right{ text-align: right;}
	</style>
	<div class="container invoice_white ">
		<div class="row">
			<div class="col-xs-12 pt10 pb10 invoice_box">
				<?php
				$gst	 = Filter::getGstin($invoiceList['bkg_pickup_date']);
				$address = Config::getGozoAddress();

				if ($isCommand == true)
				{
					$this->renderFile(Yii::getPathOfAlias("application.modules.admin.views.agent.invoice.header") . ".php", array('invoiceList' => $invoiceList, 'isPDF' => $isPDF), false);

					$this->renderFile(Yii::getPathOfAlias("application.modules.admin.views.agent.invoice.content") . ".php", ['invoiceList'		 => $invoiceList,
						'totPartnerCredit'	 => $totPartnerCredit,
						'totAdvance'		 => $totAdvance,
						'totAdvanceOnline'	 => $totAdvanceOnline,
						'column'			 => 2,
						'isPDF'				 => $isPDF], false);

					$this->renderFile(Yii::getPathOfAlias("application.modules.admin.views.agent.invoice.footer") . ".php", ['gst' => $gst], false);
				}
				else
				{

					$this->renderPartial("invoice/header", ['invoiceList'	 => $invoiceList,
						'isPDF'			 => $isPDF], false);
					$this->renderPartial("invoice/content", ['invoiceList'		 => $invoiceList,
						'totPartnerCredit'	 => $totPartnerCredit,
						'totAdvance'		 => $totAdvance,
						'totAdvanceOnline'	 => $totAdvanceOnline,
						'column'			 => 2,
						'isPDF'				 => $isPDF], false);
					$this->renderPartial("invoice/footer", ['gst' => $gst, 'address' => $address], false);
				}
				?>
			</div>
			<?
			if ($isPDF)
			{
				?>
				<div class="col-xs-12 pt10 pb10 ">
					<?php
					if ($isCommand == true)
					{
						$this->renderFile(Yii::getPathOfAlias("application.modules.admin.views.agent.invoice.paydocs") . ".php", ['bkgId' => $invoiceList['bkg_id']], false);
					}
					else
					{
						$this->renderPartial("invoice/paydocs", ['bkgId' => $invoiceList['bkg_id']], false);
					}
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
	The link is no longer active.<br>The invoice may be generated only after your trip is successfully completed with us. You can also view or download it from your booking history  by signing in your user account.<br>For any help please contact our customer care.
<? } ?>