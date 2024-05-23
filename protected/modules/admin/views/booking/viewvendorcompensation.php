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
						$vndName = '';
						if($model->bkgBcb->bcb_vendor_id > 0)
						{
							$vndName = Vendors::model()->getVendorById($model->bkgBcb->bcb_vendor_id);
						}
						
						if ($model->bkgInvoice->bkg_vnd_compensation > 0 || $model->bkgInvoice->bkg_vnd_compensation_date != '')
						{
							$compensationDate = ($model->bkgInvoice->bkg_vnd_compensation_date != '')? date("d/m/Y h:i a", strtotime($model->bkgInvoice->bkg_vnd_compensation_date)) : '';
							?>
							<div style="width: 100%; overflow: auto;  border: 1px #aaa solid;color: #444;">

								<table class="table table-bordered mb0"  >
									<tr>
										<th>Vendor Name</th>
										<th>Vendor Compensation</th>
										<th>Vendor Compensation Date</th>
										<th>Action</th>

									</tr>
										<tr>									
											<td><?= $vndName ?></td>
											<td><?php echo $model->bkgInvoice->bkg_vnd_compensation; ?></td>
											<td><?php echo $compensationDate; ?> </td>
											<td width="15%">
											<?php //if($val['vendorCoinId'] == ''){ ?>
												<?php if(Yii::app()->user->checkAccess('removeVendorCompensation')){  ?><a  href="javascript:void(0)" onclick="removeCompensation('<?php echo $model->bkg_id; ?>')"><img src="<?php Yii::app()->request->baseUrl ?>\images\icon\delete_booking.png"></a><?php } ?>
											</td>
										</tr> 
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

<script type="text/javascript">
	function removeCompensation(bkgid) {

		$href = "<?= Yii::app()->createUrl('admin/booking/RemoveVendorCompensation') ?>";
		jQuery.ajax({type: 'GET',
			url: $href,
			data: {"bkgid": bkgid},
			success: function (data) {debugger;
				var val = JSON.parse(data);
				bootbox.alert(val.message);
				location.reload();
			}
		});
	}
</script>