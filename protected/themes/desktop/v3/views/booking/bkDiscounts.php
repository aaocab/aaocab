<?php
$promoRule	 = Promos::model()->getExpTimeAdvPromo($model->bkg_create_date, $model->bookingRoutes[0]->brt_pickup_datetime);
//$promoArr=Promos::allApplicableCodes($model->bkgInvoice->bkg_base_amount, $model->bkg_pickup_date, $model->bkg_pickup_date, $model->bkg_vehicle_type_id, $platform = 1, $model->bkg_booking_type, $model->bkg_from_city_id, $model->bkg_to_city_id);
$promoArr	 = Promos::allApplicableCodes($model);
//print'<pre>';print_r($promoArr);
$hash		 = Yii::app()->shortHash->hash($model->bkg_id);
$creditVal	 = (is_array($creditVal)) ? $creditVal['credits'] : $creditVal;
$form		 = $this->beginWidget('CActiveForm', array(
	'id'					 => 'bookingdiscount', 'enableClientValidation' => true,
	'clientOptions'			 => array(
		'validateOnSubmit'	 => true,
		'errorCssClass'		 => 'has-error',
		'afterValidate'		 => ''
	),
	'errorMessageCssClass'	 => 'help-block',
	'htmlOptions'			 => array(
		'class'		 => '', 'enctype'	 => 'multipart/form-data'
	),
		));
/* @var $form CActiveForm */
?>
<div class="card-body">
	<div class="row">
		<div class="col-12 col-lg-7">
			<div class="row m0">
				<div class="d-inline-block mr-2 mb-1 radio-style4">
					<div class="radio">
						<input type="radio" name="promo" id="applyPromo" value="0" checked="checked" class="bkg_user_trip_type">
						<label for="applyPromo">Apply promo</label>
					</div>
				</div>
				<div class="d-inline-block mr-2 mb-1 radio-style4">
					<div class="radio">
						<input type="radio"name="gozocoins" id="applyGozocoins" value="1" class="bkg_user_trip_type">
						<label for="applyGozocoins">Use Gozo coins</label>
					</div>
				</div>
				<div class="row promoApplyDiv">
					<div class="col-8 col-md-8 col-sm-12 pr-0">
						<div class="form-group mb5">
							<input type="text" id="BookingInvoice_bkg_promo1_code" name="BookingInvoice_bkg_promo1_code" class="form-control copy-to-clipboard BookingInvoice_bkg_promo1_code txtPromoCode" placeholder="Enter promo code">

							<input type="hidden" id="BookingInvoice_bkg_promo1_id" name="BookingInvoice_bkg_promo1_id" class="txtPromoId" placeholder="Enter promo code">

						</div>
					</div>
					<div class="col-4 col-md-4 col-sm-12">
						<button type="button" class="btn btn-primary copy-btn" onclick="prmObj.applyPromo(1, $('#BookingInvoice_bkg_promo1_code').val())">Apply</button>
					</div>
					<div id="showPromoError" class="col-12 showPromoError text-danger"></div>

				</div>
			</div>
<div class="row creditApplyDiv hide" id="creditApplyDiv">
		<div class="col-8 col-md-6 pr-0 inline-block">
			<div class="form-group mb5">
				<input type="number" id="creditvalamt" credits="<?= $creditVal ?>" name="creditvalamt" class="form-control copy-to-clipboard creditvalamt" value="<?= $creditVal ?>" placeholder="Enter Gozo coins">
			</div>
		</div>
		<div class="col-3 col-md-2 inline-block pl10 pr0">
			<button type="button" class="btn btn-primary copy-btn" onclick="prmObj.applyPromo(3, $('#creditvalamt').val());">Apply</button>
		</div>
		<div class="col-12 inline-block">(Available Gozo Coins <?= $creditVal; ?>)</div>
		<div id="showCoinsError" class="col-12 showCoinsError text-danger"></div>

	</div>
    <div class="row loginBox hide">
        <div class="col-8 col-md-4 pr-0 inline-block pl10 pr0">
            <a  class="font-12 color-blue3 weight500" onclick="clickToLogin();">Login to use Gozo Coins</a>
        </div>
    </div>
    <input type="hidden" value="<?= (Yii::app()->user->isGuest) ? 1 : 0; ?>" id="isGuest"name="isGuest">
    
	<div class="row">
		<div class="col-12 creditRemoveDiv hide">
			<div class="d-flex justify-content-between align-items-center">
				<h4 class="font-16">Gozocoins &#x20B9;<span class="creditUsed txtGozoCoinsUsed"></span><span class="color-green"><b> applied</b></span></h4>
				<input type="hidden" id="BookingInvoice_bkg_temp_credits" name="BookingInvoice_bkg_temp_credits" class="hiddenGozoCoinsUsed" value="">



				<a href="javascript:void(0);" class="btn btn-danger mb-1 btn-sm" onclick="prmObj.applyPromo(4);">Remove coin</a>
			</div>
		</div>
	</div>
		</div>
           <?php 
			// $getDboConfirmEndTime = Filter::getDboConfirmEndTime($model->bkg_pickup_date);
			//if($getDboConfirmEndTime !='' && $model->bkg_status == 15)
			//{
			?>
<!--		<div class="col-12 col-lg-5 d-none d-lg-block"><p class="text-center" data-toggle="modal" data-target="#exampleModalLong" style="cursor: pointer;"><img src="/images/dbo-bkpn.png" class="img-fluid"></p></div>-->
         <?php
			//}
		   ?>
	</div>

    

	


	<div class="autoPromoApplyDiv">
		<?php
		if ($promoArr->getRowCount() > 0)
		{
			?>
			<h5 class="mt-3"><b>OFFERS</b></h5>
			<div class="row flex-main">
				<?php
				$arr_promo = array();

				while ($val = $promoArr->read())
				{
					?>
					<div class="col-12 col-md-6 col-xl-4 flex2 active-widget" style="display: grid;">
						<div class="card border shadow-none mb-1 app-file-info">
							<div class="card-body p10 promocard<?= $val['prm_id'] ?>">
								<div class="app-file-recent-details">
									<div class="app-file-name font-weight-bold mb5 color-blue"><b><?= $val['prm_code'] ?></b></div>
									<div class="d-inline-block font-13"><?= $val['prm_desc'] ?></div>
									<div class="text-right">
										<span class="applremove appl_<?= $val['prm_id'] ?>"><a href="javascript:void(0);" class="btn btn-primary btn-sm mr-1 mt5" onclick="prmObj.applyPromo(1, '<?= $val['prm_code'] ?>')">Apply</a></span>
										<span class="applydremove btn mr-1 color-black mt5 hide promo_app<?= $val['prm_id'] ?>"><b>Applied</b></span>

										



									</div>
								</div>
							</div>
						</div>
					</div>
				<?php }
			} ?>

			<!--		<div class="col-12">
						<div class="alert alert-success mb-2" role="alert">
							Promo TRYGZ applied successfully. You will get discount worth ₹148 and Gozo Coins worth ₹295.* You may redeem these Gozo Coins against your future bookings with us.*T&amp;C Apply
						</div>
			
					</div>-->
			<div class="col-12 promoRemoveDiv <?= ($model->bkgInvoice->bkg_promo1_code != '') ? '' : 'hide'; ?>">
				<div class="d-flex justify-content-between align-items-center">
					<h4 class="font-16">Applied promo code : <b><span id="txtpromo" class="text-uppercase txtpromo"><?= $model->bkgInvoice->bkg_promo1_code ?></span></b></h4>
					<a href="javascript:void(0);" class="btn btn-danger mb-1 btn-sm mt20" onclick="prmObj.applyPromo(2)">Remove code</a>
                    
                
                </div>
			</div>
		</div>
    </div>
</div>
<?php $this->endWidget(); ?>
<script>
    
    $(document).ready(function()
	{
         
      
    });
    
    
    $('#applyPromo').click(function ()
    {
        
        huiObj.checkPromo();
        huiObj.hidePromoCoinsError();
        prmObj.applyPromo(4);

    });


        function clickToLogin()
        {
            showLogin(function()
						{
                  //   debugger;  
            $("#isGuest").val(0)
			huiObj.checkGozocoins(0);
            huiObj.hidePromoCoinsError();
            prmObj.applyPromo(2);
						});
        }

        $('#applyGozocoins').click(function ()
        {
           // debugger;
          // checkLogin();
          <? //(Yii::app()->user->isGuest) ? 1 : 0; ?>;
            var isGuest = $("#isGuest").val();
           // alert(isGuest);
            huiObj.checkGozocoins(isGuest);
            huiObj.hidePromoCoinsError();
            prmObj.applyPromo(2);

        });
</script>
