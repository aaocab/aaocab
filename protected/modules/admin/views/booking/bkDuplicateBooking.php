<style>
	.btn-dup-outline:active,
	.btn-dup-outline:focus,
	.btn-dup-outline:hover{
		background-color: #e6e6e6;
		color: #000;
	}
	.btn-dup-outline.active{
		background-color: #7cca7c;
		color: #fff;
	}
	.btn-dup-outline{
		border: 1px solid #ccc;
	}
</style>
<div class="panel-advancedoptions" >
    <div class="row">
        <div class="col-xs-12"> 
            <div class="panel" >

                <div class="panel-body panel-body panel-no-padding">
                    <div class="panel-scroll">
                        <div class="row">
                            <div class="col-xs-12">
                                <input type="hidden" name="bkg_id" value="<?= $bkg_id?>">
								<div class="row" >
                                    <div class="col-xs-12 text-center"> 
										<p style="font-size: 18px; font-weight: 700;">Create copi(es) of <span style="color:#5e99b5;"><?= $booking_id ?></span></p>
                                    </div>
									<div class="col-xs-12"> 
										<span class="p10" style="font-size: 15px;">How many copies do you want to create:-</span>
										<input type="number" class="text-center" name="copy" min="1" max="20" value="1"/>
                                    </div>
                                </div>
								
								<div class="row">
									<div class="col-xs-12 pt20">
										<div class="p10" style="font-size: 15px;">Do you also want to apply surge in the copied bookings?</div>
										<div class="btn-group btn-group-toggle p10" data-toggle="buttons">
											<label class="btn btn-dup-outline active">
												<input type="radio" name="applySurge" value="1" id="applySurge_1" autocomplete="off" checked> Yes
											</label>
											<label class="btn btn-dup-outline">
												<input type="radio" name="applySurge" value="0" id="applySurge_0" autocomplete="off"> No
											</label>
										</div>
									</div>
								</div>
								<?php if($bkg_agent_id > 0){?>
								<div class="row">
									<div class="col-xs-12 pt20">
										<div class="p10" style="font-size: 15px;">Copy payments as..?</div>
										<div class="btn-group btn-group-toggle p10" data-toggle="buttons">
											<label class="btn btn-dup-outline active">
												<input type="radio" name="copyPayment" value="0" id="copyPayment_0" autocomplete="off" checked> Advance payment
											</label>
											<label class="btn btn-dup-outline">
												<input type="radio" name="copyPayment" value="" id="copyPayment_1" autocomplete="off"> Advance paid 
											</label>
											<label class="pl20">
											<input type="number" min="1" max="<?= $bkg_total_amount?>" readonly="readonly" class="form-control text-center" name="advancedAmount"  id="advancedAmount"  value="0"/>
											</label>
										</div>
									</div>
								</div>

								<?php } if($vendor_id > 0) { 
									$vendor_name = Vendors::model()->findByPk($vendor_id)->vnd_name;
									?>
									<div class="row">
										<div class="col-xs-12 pt10">
											<div class="p10" style="font-size: 15px;">Do you also want to assign the copied bookings to same vendor (<?= $vendor_name; ?>)?</div>
											<div class="btn-group btn-group-toggle p10" data-toggle="buttons">
												<label class="btn btn-dup-outline">
													<input type="radio" name="assignVendor" value="1" autocomplete="off"> Yes
												</label>
												<label class="btn btn-dup-outline active">
													<input type="radio" name="assignVendor" value="0" autocomplete="off" checked> No
												</label>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-xs-12 pt20">
											<span class="p10" style="font-size: 15px;"><b>NOTE</b>: 
												<ul>
													<li style="list-style-type: circle;">We will never copy the driver and car assignments.</li>
													<li style="list-style-type: circle;">Copi(es) cannot be created if the bookings pickup time has passed.</li>
												</ul>
											</span>
										</div>
									</div>
								<?php } ?>
								<div class="row">
									<div class="col-xs-12 pt20 text-center">
										<button type="button" class="btn btn-warning btn-duplicate" onclick="duplicateBooking(this)">Submit</button>
									</div>
								</div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
	function duplicateBooking(obj)
	{
		var href = '<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/booking/duplicateBooking')) ?>';
		var bkgId = '<?= $bkg_id?>';
		var noOfBooking = parseInt($('input[name="copy"]').val());
		if(noOfBooking <= 0 || noOfBooking > 20)
		{
			alert("Please provide the value between 1 to 20");
			return false;
		}
		
		if($('#advancedAmount').val() == ''){
			alert("Please provide the Adavanced paid amount.");
			return false;
		}
		var assignVnd  = $('input[name="assignVendor"]:checked').val();
		var applySurge = $('input[name="applySurge"]:checked').val();
		var copyPayment = $('input[name="copyPayment"]:checked').val();
		var advancedAmount = $('#advancedAmount').val();
		
		$('.btn-duplicate').css("pointer-events","none");
		$('.btn-duplicate').html('please wait...');
		jQuery.ajax({type: 'GET',
			url: href,
			data: {"bkg_id": bkgId, "view": 1, "copy": noOfBooking, "assignVendor": assignVnd, "applySurge": applySurge,"copyPayment":copyPayment,"advancedAmount":advancedAmount},
			success: function (data)
			{
				$( '.modal' ).modal( 'hide' ).data( 'bs.modal', null );
				var dupBox = bootbox.dialog({
					message: data,
					title: 'Create copies (v2)',
					onEscape: function () {
						location.reload();
					},
				});

			}
		});
	}
	
	$('input[name="applySurge"]').change(function(event){
		if($(event.currentTarget).val() == '0')
		{
			<?php if(!Yii::app()->user->checkAccess('createCopiesV2SurgeOverride')){?>
					$('#applySurge_1').click();
					alert("You do not have a permission to change");
			<?php } ?>
		}
	});
	
	
	$('input[name="copyPayment"]').change(function(event){
		if($(event.currentTarget).val() == '')
		{
			$('#advancedAmount').attr("readonly", false); 
			$('#advancedAmount').val('');
		}else{
			$('#advancedAmount').val(0);
			$('#advancedAmount').attr("readonly", true);
		}
	});	
	
	
	$('#advancedAmount').keyup(function () {
		var copyPaymentType = $('input[name="copyPayment"]:checked').val();
		if(copyPaymentType== ''){	
			var advancedAmount = $('#advancedAmount').val();
			var totalAmount = <?php echo $bkg_total_amount;?>;
			if (!(parseInt(advancedAmount) >= 1 && parseInt(advancedAmount) <= totalAmount)){
				//alert("You are not allowed to more than "+totalAmount);
			    alert("You can provide Advanced Paid amount between 1 to "+totalAmount);
				$('#advancedAmount').val('');
				return false;
			}
		}
	});
		
	
</script>
