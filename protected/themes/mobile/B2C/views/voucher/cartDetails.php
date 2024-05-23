<?php
$version = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/voucher.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/login.js?v=' . $version);
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/fontawesome-web/css/all.min.css?v0.6');
$this->layout	 = 'column1';
?>
<div class="content-boxed-widget p10 mb10 top-10">
	<div class="content bottom-0 uppercase pl0"><h3 class="mb0">SHOPPING CART </h3></div>
</div>

<?php
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
            <?php
			$i	= 1;
            foreach ($cartData as $c)
            {
                ?>
                <div class="content-boxed-widget p0 cover<?php echo $i ?>" style="line-height:35px;">
                  	<div class="content p10 bottom-0">
						<div class="one-half">
							<span style="float:left"><b>Voucher</b> : <?php echo $c['code']; ?> - <?= $c['title']; ?></span> <br/>
							<span style="float:left"><?php echo $c['name'] ; ?></span> <br/>
							<span style="float:left"><?php echo $c['email']; ?></span> <br/>
							<span style="float:left"><b>Quantity</b> : <?php echo $c['qty'] | 0; ?></span><br/>
							<span style="float:left"><b>Price / Quantity</b>: &#8377;<?php echo round($c['price'] / $c['qty']); ?></span><br/>
							<span style="float:left"><b>Price</b> : &#8377; <?php echo $c['price']; ?></span>
						</div>						
						<div class="one-half last-column text-right">
							<a href="javascript:void(0);" data-id="<?php echo $c['id']; ?>" class="uppercase btn-red pl15 pr15 cancelModal delItem" data-val="<?= $i ?>" title="Delete Voucher"><i class="fa fa-trash"></i></a>							
						</div>
						<div class="clear"></div>
					</div>
                </div>
				
        <?php 
				$i++; 
			} 
		 ?>
    
		
            <div class="content-boxed-widget p0" style="line-height:35px;">
			<div class="content p10 bottom-10">
				<div class="one-half">
                                    Total Price : <span class="font-22">&#8377;<b><span class="totPrice"><?php echo $cartBalance; ?></span></b></span>
				</div>
				<div class="one-half last-column text-right">		
                                    <button type="submit" class="uppercase btn-green pl15 pr15" name="btnCheckout" id="btnCheckout" value="Checkout">Checkout</button>
				</div>		
				<div class="clear"></div>							
			</div>

			</div>
		</div>
<input type="hidden" id="mtoken" value="<?php echo Yii::app()->request->csrfToken ?>">
 <?php 
$this->endWidget();	
 } else {  
$display = show;
 } ?>
	<div class="content-boxed-widget p0 ctn2" style="display:<?php echo $display;?>">
       <div class="content p10 bottom-0">Cart is empty
		</div><div class="clear"></div>	
	</div>
   

<div class="content-boxed-widget p0">
       <div class="content p10 bottom-0"><button type="button" class="uppercase btn-orange pl15 pr15 btnContinue" name="btnContinue" id="btnContinue" value="Continue"><b>Continue Shopping</b></button>
		</div><div class="clear"></div>	
	</div>
			<?php $this->renderPartial("bkInfoLogin", [], false);	 ?>
			
						
			
           
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