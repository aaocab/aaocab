<?php
$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
	'id'					 => 'newAddressForm',
	'enableClientValidation' => true,
	'clientOptions'			 => array(
		'validateOnSubmit'	 => true,
		'errorCssClass'		 => 'has-error'
	),
	'enableAjaxValidation'	 => false,
	'errorMessageCssClass'	 => 'help-block',
	'htmlOptions'			 => array(
		'class' => 'form-horizontal',
	),
		));
/* @var $form TbActiveForm */

/** @var Booking $model */
$brtRoutes = $model->bookingRoutes;

if ($model instanceof Booking)
{
	$user = $model->bkgUserInfo->bkg_user_id;
}
else if ($model instanceof BookingTemp)
{
	$user = $model->bkg_user_id;
}
if ($user == "")
{
	$user = UserInfo::getUserId();
}

$i				 = 0;
$requiredFields	 = [];
foreach ($brtRoutes as $brtRoute)
{
	$ctr = ($brtRoute->brt_id > 0) ? $brtRoute->brt_id : $i;
	if ($i == 0)
	{
		$requiredFields[]	 = CHtml::activeId($brtRoute, "[" . ($ctr) . "]from_place");
		?>
		<div class="row">
			<div class="col-12 col-md-6">
				<div>Provide street address in <?= $brtRoute->brtFromCity->cty_display_name ?></div>
				<?php
				$requiredFields[]	 = CHtml::activeId($brtRoute, "[$ctr]from_place");
				$this->widget('application.widgets.PlaceAddress',
						['model' => $brtRoute, 'attribute' => "[$ctr]from_place", 'city' => $brtRoute->brt_from_city_id, "user" => $user]);
				?>
			</div>
		<?php
	}
	?>
		<div class="col-12 col-md-6">
			<div>Provide street address in <?= $brtRoute->brtToCity->cty_display_name ?></div>
			<?php
			$this->widget('application.widgets.PlaceAddress',
					['model'		 => $brtRoute, 'attribute'	 => "[$ctr]to_place",
						'city'		 => $brtRoute->brt_to_city_id, "user"		 => $user]);
			?>	
		</div>
	</div>
	<?php
	$i++;
}
$requiredFields[] = CHtml::activeId($brtRoute, "[" . ($ctr) . "]to_place");
?>

<?= $form->hiddenField($model, "bkg_id"); ?>
<?= $form->hiddenField($model, "hash", ['value' => Yii::app()->shortHash->hash($model->bkg_id)]); ?>
<!--<div class="col-12 text-center">
	<button type="button" id="saveNewAddreses" class="btn btn-effect-ripple btn-success p5 mt10" name="saveNewAddreses" onclick="saveAddressesByRoutes();">Save Addresses</button>
</div>-->
<?php $this->endWidget(); ?>
<a href="#" data-menu="map-marker" class="hide" id="booknow-map-marker"></a>

<script type="text/javascript">
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
    function saveAddressesByRoutes()
    {
        var success = validateRoute();
        //var success = validateAddresses();
        if (success)
        {
            var frmcityid = $('#Cities_form_cty_id').val();
            $.ajax({
                "type": "POST",
                "url": '<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/updateRouteAddress')) ?>',
                "data": $('#newAddressForm').serialize(),
                "dataType": "html",
                "success": function (data)
                {
                    data = jQuery.parseJSON(data);
                    if (data.success)
                    {

                        refreshAddress();
                        alert("Addresses saved successfully.");
                        $('.isPickupAdrsCls').val(1);
                        $('#saveNewAddreses').attr("disabled", "disabled");
                        $('#newAddreses').attr("disabled", "disabled");

                        if (data.data.additional_km > 0)
                        {
                            updateAfterAddressSaved(data);
                            var additionalData = $('.clsAdditionalParams').val();
                            var bkgStatus = data.data.status;
//                            if (additionalData != '' && additionalData != undefined && (bkgStatus == 15 || bkgStatus == 1))
//                            {
//                                var additionalDataObj = JSON.parse(additionalData);
                            var additionalDataObj = JSON.parse(additionalData);
                            if ((additionalDataObj.code != '' || additionalDataObj.wallet != '' || additionalDataObj.coins != '') && (bkgStatus == 15 || bkgStatus == 1))
                            {
                                if (additionalDataObj.wallet > 0)
                                {
                                    prmObj.applyPromo(5, additionalDataObj.wallet);
                                }
                                if (additionalDataObj.code != '')
                                {
                                    prmObj.applyPromo(1, additionalDataObj.code);
                                }
                                if (additionalDataObj.coins > 0)
                                {
                                    prmObj.applyPromo(3, additionalDataObj.coins);
                                }
                            }
                        }
                    }
                }

            });
        }
    }

    function updateAfterAddressSaved(data)
    {
        if (data.data.fare.customerPaid == 0)
        {
            $('.clsPayChk').checked = true;
        }
        if (huiObj == null || huiObj == undefined)
        {
            huiObj = new HandleUI();
        }
        huiObj.bkgId = '<?= $model->bkg_id ?>';
        huiObj.updateInvoice(data);
        $('.extrachargeDiv').removeClass('hide');
        $('.additionalKmVal').html(data.data.additional_km);
        $('.extraChargeVal').html(data.data.extra_charge);
        $('.oldBasefareDiv').removeClass('hide');
        $('.txtBaseFareOld').html(data.data.oldBaseFare);
    }

    function validateRoute()
    {
        var reqFields = <?= CJavaScript::encode($requiredFields) ?>;
        var success = true;
        $.each(reqFields, function (key, value)
        {
            var PAWObject = AWObject.get(value);
            var PAWVal = PAWObject.model.id;
            if (PAWObject && !PAWObject.hasData())
            {
                success = false;
                alert("Pickup and Drop locations are mandatory");
                PAWObject.focus();
            } else if ($('#' + PAWVal).val() == '')
            {
                success = false;
                alert("Please enter proper address");
                PAWObject.focus();
            }

            return success;
        });

        return success;
    }



    function refreshAddress()
    {
//		emitMessageToVendors($('#Booking_bkg_id').val());
		
        $href = "<?php echo Yii::app()->createUrl('booking/refreshAddressWidget') ?>";
        var bkg_id = $('#Booking_bkg_id').val();
        var hash = $('#Booking_hash').val();
        jQuery.ajax({
            global: false,
            type: 'GET',
            url: $href,
            data: {"booking_id": bkg_id, "hash": hash},

            success: function (data1)
            {
                $(".addressWidget").html(data1);
                $("#bidList").show();
                setTimer();
            }
        });
    }
</script>
