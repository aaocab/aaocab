<section>
	<?php
	$display = 'none';
	if (!empty(Yii::app()->session['_voucher_cart']))
	{
		$form	 = $this->beginWidget('booster.widgets.TbActiveForm', array(
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
		/* @var $form TbActiveForm */
		?>
		<div class="row ctn1">
			<div class="col-lg-10 col-lg-offset-1"><?php echo $errors;?></div>
			<div class="col-lg-10 col-lg-offset-1">
				<table class="table table-bordered">
					<tr class="active">
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
							<td><?php echo $c['title'] . ' - ' . $c['desc']; ?><br><?php echo $c['name'] . ' ( ' . $c['email'] . ' )'; ?></td>
							<td class="text-center"><?php echo $c['qty']; ?></td>
							<td class="text-center"><i class='fa fa-inr'></i><?php echo round($c['price'] / $c['qty']); ?></td>
							<td class="text-center"><i class='fa fa-inr'></i><?php echo $c['price']; ?></td>
							<td class="text-center" width="25%"><a href="javascript:void(0);"  data-href="<?= Yii::app()->createUrl('/voucher/del', ['voucherId' => $c['id']]) ?>" class="white-color btn btn-danger mb10 delItem" data-val="<?php echo $i ?>" title="Delete Voucher"><i class="fa fa-trash"></i></a></td>
						</tr>
						<?php
						$i++;
					}
					?>
					<tr>
						<td colspan="3">&nbsp;</td>
						<td class="text-center"><i class='fa fa-inr'></i><span class="totPrice"><?php echo $cartBalance; ?></span></td>
						<td class="text-center"><button type="submit" class="btn next-btn border-none" name="btnCheckout" id="btnCheckout" value="Checkout">Checkout</button></td>
					</tr>
				</table>
			</div>
			<div class="col-lg-10 col-lg-offset-1"><button type="button" class="btn btn-success btn-lg text-uppercase btnContinue" name="btnContinue" id="btnContinue" value="Continue"><b>Continue Shopping</b></button></div>
		</div>
		<input type="hidden" id="mtoken" value="<?= Yii::app()->request->csrfToken ?>">

		<?php
		$this->endWidget();
	}
	else
	{
		$display = 'show';
	}
	?>
	<div class="row ctn2" style="display:<?php echo $display; ?>">
		<div class="col-xs-12 col-sm-12 coupon_box">
			<div class="panel panel-default">
				<div class="p20 panel-body">
					<div class="col-sm-12"><b>Cart is empty</b></div>
				</div>
			</div>
		</div>
		<div class="col-lg-10 col-lg-offset-1"><button type="button" class="btn btn-success btn-lg text-uppercase btnContinue" name="btnContinue" id="btnContinue" value="Continue"><b>Continue</b></button></div>
	</div>
</section>

<script>
    $('.delItem').click(function () {
        var r = confirm("Are you sure you want to delete this voucher?");
        if (r == true) {
            var token = $('#mtoken').val();
            var pval = $(this).data('val');
            var href = $(this).data('href');
            $.ajax({
                url: href,
                data: {"YII_CSRF_TOKEN": token},
                type: 'POST',
                success: function (data) 
				{
                    $(".cover" + pval).hide();
                    var obj = JSON.parse(data);
                    if (obj.cartBalance > 0)
                    {
                        $(".totPrice").html(obj.cartBalance);
                    } else
                    {
                        $(".ctn2").show();
                        $(".ctn1").hide();
                    }
                }
            });
        }
    });
    $('.btnContinue').click(function () {

        window.location.href = '/voucher';

    });
	
	$('#buyForm').submit(function (e) {   
		var token = $('#mtoken').val();   
		$.ajax({
			url: '/users/userdata',
			data: {"YII_CSRF_TOKEN": token},
			type: 'POST',
			async:false,
			success: function (data) 
			{         
				let obj = JSON.parse(data);                  
				if(obj.usr_name == null && obj.usr_lname == null && !obj.hasOwnProperty('usr_mobile') && !obj.hasOwnProperty('usr_email')){
					$("#signinpopup").click();
					e.preventDefault();           
				}            
			}
        }); 
    });
</script>