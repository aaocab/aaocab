<?php
/** @var CActiveForm $form */
$form = $this->beginWidget('CActiveForm', array(
	'id'					 => 'cabrate-form13',
	'enableClientValidation' => true,
	'clientOptions'			 => array(),
	'enableAjaxValidation'	 => false,
	'errorMessageCssClass'	 => 'help-block',
	'htmlOptions'			 => array(
		'class'			 => 'form-horizontal',
		'autocomplete'	 => 'off'
	),
		));
?>


<?php
if ($quotes)
{
	?>
	<div class="container mb-2">
		<div class="alert alert-danger mb-2 text-center hide alertcategory" role="alert"></div>
		<div class="col-12 text-center mb-3 style-widget-1"><h3 class="gothic weight600">What category of cab are you looking for.</h3></div>	
		<div class="row">
			<?php
			foreach ($quotes as $key => $quote)
			{
				//	echo 	$names = min(array_column($quote, 'baseFare'));	
				?>
				<div class="col-xl-3 col-md-6 col-sm-12">
					<div class="card text-center pt-1">
						<span class="text-center"> <img src="<?= Yii::app()->baseUrl . min(array_column($quote, 'image')) ?>" width="150" class="img-fluid" alt="singleminded"></span>
						<div class="card-header text-center pt10" style="display: inline-block;">
							<h4 class="card-title text-center weight500 text-uppercase"><?php echo min(array_column($quote, 'cabCategoryType')); ?></h4>
						</div>
						<div class="card-body">
							<p class="weight400 mb0">More comfort Reasonably priced</p>
							<p class="weight400 color-blue">
								<img src="/images/bxs-star.svg" alt="img" width="18" height="18"> <img src="/images/bxs-star.svg" alt="img" width="18" height="18"> <img src="/images/bxs-star.svg" alt="img" width="18" height="18"> <img src="/images/bxs-star.svg" alt="img" width="18" height="18">
							</p>
							<p class="mb0"><span class="font-20">&#x20B9;</span><span class="font-24 weight600"><?php echo min(array_column($quote, 'baseFare')); ?></span></p>
							<p class="mb0">onwards</p>
							<div class="radio-style3">
								<div class="radio">
									<input id="category<?php echo $category; ?>" value="<?php echo $category; ?>" type="radio" name="cabcategory" class="">
									<label for="category<?php echo $category; ?>"></label>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php }
			?>
			<div class="col-xl-12 text-center">
				<button type="button" class="btn btn-primary mr-1 mb-1 text-uppercase showcabdetails">NEXT</button>
				<input type="hidden" name="pageID" id="pageID" value="7">
			</div>         
		</div>

	</div>
<?php } ?>
</div>   

<?php $this->endWidget(); ?>
