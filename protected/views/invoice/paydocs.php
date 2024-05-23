<?
$httpStatus	 = $_SERVER['HTTP_X_FORWARDED_PROTO'] . ($_SERVER['HTTPS'] == 'on') ? 'https' : 'http';
$baseUrl	 = $httpStatus . "://" . $_SERVER['HTTP_HOST'];

$recordset	 = BookingPayDocs::model()->getDutySlipBybookingId($bkgId, 1);
$doctypeList = BookingPayDocs::model()->getVoucherListType();
if (count($recordset) > 0)
{
	?>
	<div  class="row" >
		<?
		foreach ($recordset as $docimg)
		{
			$imgUrl		 = $baseUrl . $docimg['bpay_image'];
			list($width, $height) = getimagesize($imgUrl);
			$widthDiv	 = '100%';
			if ($width < $height)
			{
				$widthDiv = '50%';
			}
			?>
			<div  style="width:<?= $widthDiv ?> ;float: left;text-align: center"> 
				<img id="frLicApprove"  src="<?= $imgUrl ?>" style="  padding: 10px"><br>
				<span><?= $doctypeList[$docimg['bpay_type']] ?></span>
			</div>	
			<?
		}
		?>
	</div>	
	<?
}
?>
