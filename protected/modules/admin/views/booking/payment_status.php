<?
$providerList = PaymentType::model()->getList();
?>
<div class="row">
    <div class="col-xs-12 mb20 panel">
        <div  class="row ">

			<div class=' table-responsive'>
				<table class="table table-striped table-bordered    ">
					<thead class="table table-header-fixed">
						<tr>
							<th id="yw0_c1">Code(Mode)</th>
							<th id="yw0_c2">Provider</th>
							<th id="yw0_c3">Amount</th>
							<th id="yw0_c4">Status</th>
							<th id="yw0_c5">Action</th>
						</tr></thead><tbody >
						<?
						foreach ($payData as $payrow)
						{
							$txtColor = ($payrow['apg_status'] == 1) ? 'text-success' : 'text-danger';
							?><tr>
								<td  ><?= $payrow['apg_code'] . ' (' . (( $payrow['apg_mode'] == 1) ? 'Refund' : 'Payment') . ')'; ?></td>
								<td> 
									<?= $providerList[$payrow['apg_ptp_id']] ?> 
								</td>

								<td class="text-right <?= $txtColor ?>"><?= abs($payrow['apg_amount']); ?>

								</td>
								<td >
									<span id="apg1_<?= $payrow['apg_id'] ?>"><?= $payrow['apg_remarks']; ?></span><br>
									<span id="apg2_<?= $payrow['apg_id'] ?>">(<?= $payrow['apg_response_message']; ?>)</span>
								</td>
								<td>
									<?php
									if ($payrow['apg_status'] == 0)
									{
										?>

										<button onclick="showstatus(<?= $payrow['apg_id'] ?>)"  apgid = "<?= $payrow['apg_id'] ?>">Check Status</button>
										<?
									}
									?>

								</td>
							</tr>
							<?
						}
						?>
					</tbody>
				</table>
			</div>

		</div>
	</div>
</div> 
<script type="text/javascript">
	function showstatus(apgid) {

		booking_id = '<?= $model->bkg_id ?>';
		$href = "<?= Yii::app()->createUrl('admin/booking/showPaymentStatus') ?>";
		jQuery.ajax({type: 'GET',
			url: $href,
			dataType: 'json',
			data: {"apgid": apgid},
			success: function (data)
			{
				if (data.success) {

					$('#apg1_' + data.apgid).text(data.message);
					$('#apg2_' + data.apgid).text(data.response);

				} else {
					alert('Some error occured');
				}


			}
		});
	}



</script>