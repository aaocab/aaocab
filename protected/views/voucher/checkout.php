<style>
    #Users_usr_email , #Users_usr_gender{
        border: 1px #434A54 solid;
    }
</style>
<style>
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        /* display: none; <- Crashes Chrome on hover */
        -webkit-appearance: none;
        margin:0;/* <-- Apparently some margin are still there even though it's hidden */
    }

</style>
<?php
$detect		 = Yii::app()->mobileDetect;
// call methodss
$isMobile	 = $detect->isMobile() && $detect->is("AndroidOS");
$isredirct	 = true;
?>
<section>

    <div class="row">
        <div class="col-xs-12">
			<?php echo CHtml::errorSummary($model); ?>
        </div>
        <div class="col-xs-12 text-center">
			<?php if (Yii::app()->user->hasFlash('success')): ?>
				<div class="alert alert-success" style="padding: 10px">
					<?php echo Yii::app()->user->getFlash('success'); ?>
				</div>
			<?php endif; ?>
        </div>
    </div>


	<?php
	if (Yii::app()->session['_voucher_sess_id'] != '')
	{
		?>
		<div class="row">

			<table class="table table-bordered">
				<tr class="active">
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
						<td><?php echo $c['title'] . ' - ' . $c['desc']; ?><br><?php echo $c['name'] . ' ( ' . $c['email'] . ' )'; ?></td>
						<td class="text-center"><?php echo $c['qty']; ?></td>
						<td class="text-center"><i class='fa fa-inr'></i><?php echo round($c['price'] / $c['qty']); ?></td>
						<td class="text-center"><i class='fa fa-inr'></i><?php echo $c['price']; ?></td>
					</tr>

				<?php }
				?>
				<tr>
					<td colspan="3">&nbsp;</td>
					<td class="text-center"><i class='fa fa-inr'></i><?= $cartBalance; ?></td>
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


    <div class="row">
        <div class="row m0 mb10 mt30">
            <div class="col-xs-12 h4 m0 text-uppercase pl0">Billing Information</div>
        </div>
		<?php
		if (!$isMobile)
		{
			$this->renderPartial("paymentWidget", ["model" => $model, 'isredirct' => $isredirct], false);
		}
		?>
    </div>
</section>
