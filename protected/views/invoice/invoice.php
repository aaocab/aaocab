<?php
if ($invoiceList)
{
	
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
				$patSetdata	 = PartnerSettings::model()->getValueById($invoiceList['bkg_agent_id']);
				$gst = Filter::getGstin($invoiceList['bkg_pickup_date']);
				
				$this->renderPartial("//invoice/header", ['invoiceList' => $invoiceList,
						'isPDF' => $isPDF], false);
				if($patSetdata['pts_generate_invoice_to'] == 2 && $invoiceList['bkg_agent_id']>0) // partner header
				{
					$this->renderPartial("//invoice/partnerHeader", ['invoiceList' => $invoiceList,
						'totPartnerCredit' => $totPartnerCredit,
						'totAdvance'       => $totAdvance,
						'totAdvanceOnline' => $totAdvanceOnline,
						'column'           => 2,
						'isPDF'            => $isPDF], false);
				}
				else   // traveller header
				{
					$this->renderPartial("//invoice/travellerHeader", ['invoiceList' => $invoiceList,
						'totPartnerCredit' => $totPartnerCredit,
						'totAdvance'       => $totAdvance,
						'totAdvanceOnline' => $totAdvanceOnline,
						'column'           => 2,
						'isPDF'            => $isPDF], false);
					
				}
				
				if (in_array($invoiceList['bkg_status'], [5, 6, 7])) 
				{
					 $this->renderPartial("//invoice/completedView", ['invoiceList' => $invoiceList,
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
				$this->renderPartial("//invoice/footer", ['gst' => $gst], false);	
				?>	</div>
		</div>
	</div>	
	<?php
}
else
{
	?>
	The link is no longer active.
	<br>The invoice may be generated only after your trip is cancelled.
	<br>For any help please contact our customer care.
	<?php
}?>