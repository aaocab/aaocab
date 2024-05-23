<?php
$version = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/voucher.js?v=' . $version);
?>
<div class="row title-widget">
    <div class="col-12">
        <div class="container">
            <?php echo $this->pageTitle; ?>
        </div>
    </div>
</div>
<div class="container mt30 mb30">
	<?php
	if (!empty($errors))
	{
		$errorList = json_decode($errors);
	?>
	<div class="alert alert-danger" style="font-size: 18px;" role="alert">
		<ul style="list-style-type:none;">
			<?php 
			foreach($errorList as $err)
			{				
				foreach($err as $v1)
				{
				?>
					<li><?php echo $v1; ?></li>
			<?php 		
				}					
			}
			?>
		</ul>
    </div>
	<?php
	}
	$display = none;
	if (!empty(Yii::app()->session['_voucher_cart']))
	{
		$form	 = $this->beginWidget('CActiveForm', array(
			'id'					 => 'buyForm', 'enableClientValidation' => true,
			'clientOptions'			 => array(
				'validateOnSubmit'	 => true,
				'errorCssClass'		 => 'has-error'
			),
			// Please note: When you enable ajax validation, make sure the corresponding
			// controller action is handling ajax validation correctly.
			// See class documentation of CActiveForm for details on this,
			// you need to use the performAjaxValidation()-method described there.
			'enableAjaxValidation'	 => false,
			'errorMessageCssClass'	 => 'help-block',
			'htmlOptions'			 => array(
				'class' => 'form-horizontal',
			),
		));
		/* @var $form CActiveForm */
		?>
		<div class="row ctn1">
			<div class="col-12 col-lg-10 offset-lg-1">
				<table class="table table-bordered">
					<tr class="active thead-dark">
						<th>Voucher</th>
						<th class="text-center">Quantity</th>
						<th class="text-center">Price / Quantity</th>
						<th class="text-center">Total</th>
						<th class="text-center"></th>
					</tr>
					<?php
					$i		 = 1;
					foreach ($cartData as $c)
					{
						?>
						<tr class="cover<?php echo $i ?>">
							<td><?php echo $c['code'] . ' - ' . $c['title']; ?><br><?php echo $c['name'] . ' ( ' . $c['email'] . ' )'; ?></td>
							<td class="text-center"><?php echo $c['qty']; ?></td>
							<td class="text-center">&#x20B9;<?php echo round($c['price'] / $c['qty']); ?></td>
							<td class="text-center">&#x20B9;<?php echo $c['price']; ?></td>
							<td class="text-center" width="25%"><a href="javascript:void(0);"  data-id="<?php echo $c['id']; ?>" class="white-color btn btn-danger mb10 delItem" data-val="<?php echo $i ?>" title="Delete Voucher"><i class="fa fa-trash"></i></a></td>
						</tr>

						<?php
						$i++;
					}
					?>

					<tr>
						<td colspan="3">&nbsp;</td>
                                                <td class="text-center"><span class="font-22">&#x20B9<span class="totPrice"><b><?php echo $cartBalance; ?></b></span></td>
						<td class="text-center"><button type="submit" class="btn-orange pl20 pr20" name="btnCheckout" id="btnCheckout" value="Checkout">Checkout</button></td>
					</tr>
				</table>
			</div>
			<div class="col-lg-10 offset-lg-1"><button type="button" class="btn btn-success btn-lg text-uppercase btnContinue" name="btnContinue" id="btnContinue" value="Continue"><b>Continue Shopping</b></button></div>
		</div>
		<input type="hidden" id="mtoken" value="<?= Yii::app()->request->csrfToken ?>">

		<?php
		$this->endWidget();
	}
	else
	{
		$display = show;
	}
	?>
	<div class="row ctn2" style="display:<?php echo $display; ?>">
		<div class="col-12 col-sm-12 coupon_box">
			<div class="panel panel-default">
				<div class="p20 panel-body">
					<div class="col-sm-12"><b>Cart is empty</b></div>
				</div>
			</div>
		</div>
		<div class="col-lg-10 offset-lg-1"><button type="button" class="btn btn-success btn-lg text-uppercase btnContinue" name="btnContinue" id="btnContinue" value="Continue"><b>Continue</b></button></div>
	</div>
</div>

<script>	
	$('.delItem').click(function () {
        var r = confirm("Are you sure you want to delete this voucher?");
        if (r == true) {
			let obj = new Voucher();
			obj.model.token = $('#mtoken').val();
            obj.model.item = $(this).data('val');
            obj.model.id = $(this).data('id');            
			obj.itemDelete();
        }
    });
	
    $('.btnContinue').click(function () {
        window.location.href = '/voucher';
    });
	
	$('#buyForm').submit(function (event)
	{		
		let obj = new Voucher();
		obj.model.token = $('#mtoken').val();
		obj.checkLoginForCheckout(event);		
	});
</script>