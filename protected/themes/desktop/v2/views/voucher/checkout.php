
<?php
$detect		 = Yii::app()->mobileDetect;
// call methodss
$isMobile	 = $detect->isMobile() && $detect->is("AndroidOS");
$isredirct	 = true;
?>

<div class="row title-widget">
	<div class="col-12">
		<div class="container">
			<?php echo $this->pageTitle; ?>
		</div>
	</div>
</div>
    <div class="row">
        <div class="col-12">
			<?php echo CHtml::errorSummary($model); ?>
        </div>
        <div class="col-12 text-center">
			<?php if (Yii::app()->user->hasFlash('success')): ?>
				<div class="alert alert-success" style="padding: 10px">
					<?php echo Yii::app()->user->getFlash('success'); ?>
				</div>
			<?php endif; ?>
        </div>
    </div>


	<?php
	if (count($cartData) > 0)
	{
		?>
<div class="container">
		<div class="row mt30">
                    <div class="col-12 col-lg-10 offset-lg-1">
			<table class="table table-bordered">
				<tr class="active table-warning">
					<th>Voucher</th>
					<th class="text-center">Quantity</th>
					<th class="text-center">Price / Quantity</th>
					<th class="text-center">Total</th>
				</tr>

				<?php
				foreach ($cartData as $c)
				{
					?>
					<tr>
						<td><?php echo $c['code'] . ' - ' . $c['title']; ?><br><?php echo $c['name'] . ' ( ' . $c['email'] . ' )'; ?></td>
						<td class="text-center font-22"><?php echo $c['qty']; ?></td>
						<td class="text-center">&#x20B9;<?php echo round($c['price'] / $c['qty']); ?></td>
						<td class="text-center">&#x20B9;<?php echo $c['price']; ?></td>
					</tr>

				<?php }
				?>
				<tr>
					<td colspan="3">&nbsp;</td>
                                        <td class="text-center"><span class="font-22">&#x20B9;<b><?php echo $cartBalance; ?></b></span></td>
				</tr>	

				<?php
			}
			else
			{
				?>

				<tr>
					<td colspan="4"><b>Cart is empty</b></td>
				</tr>
				<?php
			}
			?>
        </table>
                    </div>
    </div>
</div>

<div class="container">
    <div class="row">
        
        <div class="col-12 col-lg-10 offset-lg-1 mb30">
            <div class="h4 m0 mt30 text-uppercase pl0">Billing Information</div>
            <div class="row m0">
            <?php
            if (!$isMobile)
            {
                    $this->renderPartial("paymentWidget", ["model" => $model, 'isredirct' => $isredirct], false);
            }
            ?>
    </div>
        </div>
    </div>
</div>
