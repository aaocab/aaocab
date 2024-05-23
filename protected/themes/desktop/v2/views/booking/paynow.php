
<div class="row">
    <div class="col-xs-12">
		<?php
		if ($model->bkg_agent_id!='')
		{
			/* @var $agentModel Agents */
			$agentModel		 = Agents::model()->findByPk($model->bkg_agent_id);
			$acceptPayment	 = $agentModel->agt_use_gateway;
			?>
			<div class="row  ">
				<div class="col-xs-6 text-left"><h1><?= $agentModel->agt_company ?></h1></div>
				<div class="col-xs-6 text-right mt5"><img src="<?= Yii::app()->baseUrl ?>/images/logo4.png"/></div>
			</div>
			<?
		}
		?>

		<?php
		$platform		 = Yii::app()->request->getParam('platform');
		$src			 = Yii::app()->request->getParam('src', 2);
		if ($src != 1 && $platform != 3)
		{
			?>
			<div class="" id="bookingDetPayNow">
				<h4 class=" text-center"><?= ucwords($model->getBookingType($model->bkg_booking_type, 'Trip')) ?> - <?= $ct ?> <nobr>(Booking ID:<?= $model->bkg_booking_id ?>) </nobr>(<?= Booking::model()->getActiveBookingStatus($model->bkg_status) ?>)</h4>
				<?
				if ($paymentdone)
				{
					if ($succ == 'success')
					{
						?>
						<div role="alert" class="alert alert-success">
							<strong>Transaction was successful. Thank you for your order. Your Transaction Id : <?= $transid ?></strong>
						</div>
						<?
					}
					else
					{
						?>
						<div role="alert" class="alert alert-danger">
							<strong>Oh snap!</strong> Something went wrong. Transaction was not successful.
						</div>
						<?
					}
				}
				?>
			</div>
		<? } ?>
    </div>
</div>


<?php
$this->renderPartial("booksummary", ['model'				 => $model, 'model1'			 => $model1, 'ccmodel'			 => $payment, 'minPay'			 => $minPay, 'minDiff'			 => $minDiff, 'src'				 => $src, 'isAgent'			 => $isAgent, 'platform'			 => $platform,
	'cod'				 => $cod, 'serviceTax'		 => $serviceTax, 'amountWithCod'		 => $amountWithCod, 'showUserInfoPickup' => $showUserInfoPickup, 'succ'				 => $succ, 'paymentdone'		 => $paymentdone], false, true);
?>