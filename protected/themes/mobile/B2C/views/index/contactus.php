<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/fontawesome-web/css/all.min.css?v0.6');
?>

<?php
if (isset($organisationSchema) && trim($organisationSchema) != '')
{
	?>
	<script type="application/ld+json">
	<?php
	echo $organisationSchema;
	?>
	</script>
<?php } ?>
<div class="content-boxed-widget">
	<div class="content p0">
		<h3 class="mt5 text-uppercase"><i class="fa fa-map fa-x color-gray-dark"></i> Address</h3>
		<?= Config::getGozoAddress(); ?>
	</div>
	<div class="content p0">
		<h3 class="mt5 text-uppercase"><i class="fa fa-phone-square fa-rotate-90 fa-x color-gray-dark"></i> Our Phones</h3>
		For corporate enquiries:<br>
		<b>(+91) 124-670-7941 (24x7)</b><br><br>
		For Booking:<br>
		<a href="#" class="btn-green helpline">Request a call back</a>

	</div>
	<div class="content p0">
		<h3 class="mt5 text-uppercase"><i class="fa fa-envelope fa-x color-gray-dark"></i> E-mail</h3>
		<a href="mailto:info@aaocab.com" style="text-decoration: none; color: #282828;">info@aaocab.com</a>
	</div>
</div>
<div class="content-boxed-widget">
	<iframe src="http://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3507.607500067027!2d77.07596461445239!3d28.46124599856468!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390d18e7dd99c029%3A0xb23ee5532279fb!2sBestech%20Chambers!5e0!3m2!1sen!2sin!4v1576841653951!5m2!1sen!2sin" width="100%" height="400" frameborder="0" style="border:0" allowfullscreen></iframe>
</div>

