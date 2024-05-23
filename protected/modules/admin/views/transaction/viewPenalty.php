<style>
    .dlgComments .dijitDialogPaneContent{
        overflow: auto;
    }
</style>
<div class="panel-advancedoptions" >
    <div class="row">
        <div class="col-md-12"> 
            <div class="panel" >
                <div class="panel-body panel-no-padding p0 pt10">
                    <div class="panel-scroll1">
						<?php
						if (count($penaltylist) > 0)
						{
							?>
							<div style="width: 100%; overflow: auto;  border: 1px #aaa solid;color: #444;">

								<table class="table table-bordered mb0"  >
									<tr>
										<th>Vendor Name</th>
										<th>Remarks</th>
										<th>Amount</th>
										<th>Created Date</th>
										<th>Action</th>

									</tr>
									<?php
									foreach ($penaltylist as $val)
									{
										$ptype		 = CJSON::decode($val['adt_addt_params']);
										$penaltyType = $ptype['penaltyType'];
										?>
										<tr>									
											<td><?php echo $val['vnd_name']; ?></td>
											<td><?php echo $val['act_remarks']; ?> </td>
											<td><?php echo $val['amount']; ?></td>
											<td><?php echo $val['act_created']; ?></td>
											<td width="15%">
											<?php if($val['vendorCoinId'] == ''){ ?>
									<a  href="javascript:void(0)" onclick="modifyPenalty('<?php echo $val['act_id']; ?>', '<?php echo $val['adt_trans_ref_id']; ?>', '<?php echo $val['vnd_id']; ?>', '<?php echo $bkg_id; ?>', '<?php echo $penaltyType; ?>', '<?php echo $val['adt_type']; ?>', '<?php echo $val['act_amount']; ?>')"><img src="<?php Yii::app()->request->baseUrl ?>\images\icon\edit_booking.png"></a>
												<!--<a  href="javascript:void(0)" onclick="redeemPenalty('<?php echo $val['act_id']; ?>','<?php echo $val['adt_trans_ref_id']; ?>','<?php echo $val['vnd_id']; ?>','<?php echo $bkg_id; ?>','<?php echo $penaltyType; ?>','<?php echo $val['adt_type']; ?>','<?php echo $val['act_amount']; ?>')"><img src="<?php Yii::app()->request->baseUrl ?>\images\icon\penalty_adjustment.png
		"></a>-->	

												<a  href="javascript:void(0)" onclick="penaltyDelete('<?php echo $val['act_id']; ?>', '<?php echo $val['adt_trans_ref_id']; ?>', '<?php echo $penaltyType; ?>', '<?php echo $val['act_amount']; ?>', '<?php echo $val['adt_type']; ?>', '<?php echo $bkg_id; ?>' , '<?php echo $val['vnd_id']; ?>')"><img src="<?php Yii::app()->request->baseUrl ?>\images\icon\customer_cancel.png"></a>
												<a href="javascript:void(0)" onclick="AdjustPenalty('<?php echo $val['act_id']; ?>', '<?php echo $val['vnd_id']; ?>')"><img src="<?php Yii::app()->request->baseUrl ?>\images\icon\add_penalty.png" title="Adjust Penalty"></a>
									<?php } ?>
											</td>
										</tr> 
										<?php
									}
									?>
								</table>

							</div><?php
						}
						else
						{
							echo "No Record Found.";
						}
						?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

	function penaltyDelete(act_id, adt_trans_ref_id, adt_addt_params, act_amount, adt_type, bkg_id, vnd_id)
	{
		$href = "<?= Yii::app()->createUrl('admin/transaction/deletePenalty') ?>";
		jQuery.ajax({type: 'GET',
			url: $href,
			'data': {'act_id': act_id, 'adt_trans_ref_id': adt_trans_ref_id, 'adt_addt_params': adt_addt_params, 'act_amount': act_amount, 'adt_type': adt_type, 'bkg_id': bkg_id, 'vnd_id': vnd_id},
			success: function (data) {
				refndbox1 = bootbox.dialog({
					message: data,
					title: 'Delete Penalty',
					onEscape: function () {

					}
				});
				refndbox1.on('hidden.bs.modal', function (e) {
					$('body').addClass('modal-open');
				});
			}
		});

//	var conf = confirm("are you sure want to delete this penalty!")
//	if(conf)
//	{
//		$href = "<?= Yii::app()->createUrl('admin/transaction/deletePenalty') ?>";
//		
//	jQuery.ajax({type: 'GET',
//	            url: $href,
//	            'data': {'act_id': act_id,'adt_trans_ref_id': adt_trans_ref_id,'adt_addt_params': adt_addt_params,'act_amount': act_amount,'adt_type': adt_type,'bkg_id': bkg_id},
//	            'dataType': 'json',
//	            success: function (data)
//	            {
//	                if (data.success)
//	                {
//	                    alert(data.msg);
//						 //location.reload();
//	                }
//
//	            }
//	        });
//	}
//	else{
//		return false;
//	}
	}

	function modifyPenalty(act_id, adt_trans_ref_id, vnd_id, bkg_id, adt_addt_params, adt_type, act_amount) {

		$href = "<?= Yii::app()->createUrl('admin/transaction/modifyPenalty') ?>";
		jQuery.ajax({type: 'GET',
			url: $href,
			data: {"act_id": act_id, "adt_trans_ref_id": adt_trans_ref_id, "vnd_id": vnd_id, "bkg_id": bkg_id, "adt_addt_params": adt_addt_params, "adt_type": adt_type, "act_amount": act_amount},
			success: function (data) {
				refndbox = bootbox.dialog({
					message: data,
					title: 'Modify Penalty',
					onEscape: function () {

					}
				});
				refndbox.on('hidden.bs.modal', function (e) {
					$('body').addClass('modal-open');
				});
			}
		});
	}
	function redeemPenalty(act_id, adt_trans_ref_id, vnd_id, bkg_id, adt_addt_params, adt_type, act_amount) {

		$href = "<?= Yii::app()->createUrl('admin/transaction/redeemPenalty') ?>";
		jQuery.ajax({type: 'GET',
			url: $href,
			data: {"act_id": act_id, "adt_trans_ref_id": adt_trans_ref_id, "vnd_id": vnd_id, "bkg_id": bkg_id, "adt_addt_params": adt_addt_params, "adt_type": adt_type, "act_amount": act_amount},
			success: function (data) {
				refndbox = bootbox.dialog({
					message: data,
					title: 'Redeem Penalty',
					onEscape: function () {

					}
				});
				refndbox.on('hidden.bs.modal', function (e) {
					$('body').addClass('modal-open');
				});
			}
		});
	}


	function AdjustPenalty(act_id, vnd_id)
	{
		$href = "<?= Yii::app()->createUrl('admin/transaction/adjustPenalty') ?>";
		jQuery.ajax({type: 'GET',
			url: $href,
			dataType: "json",
			'data': {'act_id': act_id, 'vnd_id': vnd_id},
			success: function (data) {
				bootbox.alert(data.message);

//				if (data.success)
//				{
//					console.log(data);
//					bootbox.alert(data.message);
//					location.reload();
//				} else {
//					bootbox.alert(data.message);
//				}


			}
		});
	}


</script>


