<?
$useInvoiceLogo	 = 0;
$agtLogo		 = '';
if ($invoiceList['bkg_agent_id'] > 0)
{
	$isCorporate	 = true;
	$useInvoiceLogo	 = $invoiceList['agt_use_invoice_logo'];
	$agtLogo		 = $invoiceList['agt_invoice_logo_path'];
}
?>
<div class="row" style="border-bottom: #888888 1px solid; margin-right: 0; margin-left: 0; padding-bottom: 10px;">	 
	<div  style="width: 33%;float: left">
		<?
		$httpStatus	 = $_SERVER['HTTP_X_FORWARDED_PROTO'] . ($_SERVER['HTTPS'] == 'on') ? 'https' : 'http';
		$baseUrl	 = $httpStatus . "://" . $_SERVER['HTTP_HOST'];
		$gzLogoUrl	 = $baseUrl . "/images/logo2.png";
		if ($useInvoiceLogo == 1 && $agtLogo != '')
		{
			$agtLogoUrl = $baseUrl . $agtLogo;
			?>
			<div class=" col-xs-12" style="text-align: center;border: 0; ">
				<img src="<?= $agtLogoUrl ?>" alt="" style="height:90px; ">
			</div>
			<?
		}
		else
		{
			?>
			<div class="col-xs-12 " style="text-align: center;border: 0;height:40px; ">
				<img src="<?= $gzLogoUrl ?>" alt="" <?= $widthSet ?>>
			</div>
		<? }
		?>
		

	</div>
	<div class="text-uppercase    text-center"  style="width: 34%;float: left;padding-top: 20px ">
		<span style="font-size: 20px;">I N V O I C E</span>
	</div>
	<div class="text-center" style="width: 33% ; float: left">
		<?
		$widthSet = '';

		if ($useInvoiceLogo == 1 && $agtLogo != '')
		{
			?>
			<div  style="font-size: 13px;" >
				Powered by</div> 
			<div class="  ">
				<img src="<?= $gzLogoUrl ?>" alt="" <?= $widthSet ?>>
			</div>
			<?
		}
		/* else
		  {
		  if (!$isCorporate)
		  {
		  ?>
		  <div class="text-left" style="font-size: 11px;line-height: 1.3em" >
		  <strong>Mailing Address:</strong><br>
		  H-215, Block H, Upper Ground Floor,<br>
		  Sushant Shopping Arcade,<br>
		  Sushant Lok phase -1,<br>
		  Gurugram , Haryana Pin - 122001
		  </div><?
		  }
		  } */
		?>		 
	</div>
</div>