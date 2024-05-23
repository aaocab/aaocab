<?php /* @var $this Controller */ ?>
<?php $this->beginContent('/layouts/admin1');
?>




<div class="row   ">
	<div class="col-xs-12 col-sm-4 col-md-3   ">

		<ul class="menu side-bar pt10 ">
			<li class="menu-item mb10"><a href="<?= Yii::app()->createUrl('admin/pay/register'); ?>"><i class="fa fa-newspaper-o bg-primary p10 mr5"></i> Registration</a></li>

			<li class="menu-item mb10"><a href="<?= Yii::app()->createUrl('admin/pay/regstatus'); ?>"><i class="fa fa-search  bg-primary  p10 mr5"></i> Registration Status</a></li>
			<li class="menu-item mb10"><a href="<?= Yii::app()->createUrl('admin/pay/transaction'); ?>"><i class="fa fa-money   bg-primary  p10 mr5"></i> New Transaction</a></li>
			<li class="menu-item mb10"><a href="<?= Yii::app()->createUrl('admin/pay/transtatus'); ?>"><i class="fa fa-check-square-o   bg-primary  p10 mr5"></i> Transaction Inquiry</a></li>

			<li class="menu-item mb10"><a href="<?= Yii::app()->createUrl('admin/pay/accstatement'); ?>"><i class="fa fa-list   bg-primary  p10 mr5"></i> Account Statement</a></li>
			<li class="menu-item mb10"><a href="<?= Yii::app()->createUrl('admin/pay/balanceinq'); ?>"><i class="fa fa-gift   bg-primary  p10 mr5"></i> Balance Inquiry</a></li>


		</ul>

	</div>
	<div class="col-xs-12 col-sm-8 col-md-6 padding_zero border-left">


		<?php echo $content;
		?>

	</div>

</div>

<?php $this->endContent(); ?>