<div class="row">
	<div class="col-xs-12">
		<div class="panel"> 
			<div class='panel-body'>
				<div class="row"><div class="col-xs-12 h3">Quotation Details</div></div>
				<div class="row">
					<div class="col-xs-12 col-md-6">
						Source City : <span class=""><?= $data[0]['cty_name'] ?></span>

					</div>	
					<div class="col-xs-12 col-md-6">
						Cab Type : <span class=""><?= $data[0]['vct_label'].'('. $data[0]['scc_label'] .')'?></span>
					</div>	
				</div>
				<div class="row">
					<div class="col-xs-12 col-md-6">
						Pickup Date : <span class=""><?= DateTimeFormat::DateTimeToLocale($data[0]['cqt_pickup_date']) ?></span>

					</div>	
					<div class="col-xs-12 col-md-6">
						No of Days : <span class=""><?= $data[0]['cqt_no_of_days'] ?></span>
					</div>	
				</div>
				<div class="row">
					<div class="col-xs-12 col-md-6">
						Booking type : <span class=""><?= $data[0]['cqt_booking_type'] ?></span>

					</div>	

				</div>
				<?
				if ($data[0]['vqt_description'] != '')
				{
					?>
					<div class="row">
						<div class="col-xs-12">
							Description : <?= $data[0]['vqt_description'] ?>

						</div>
					</div>
				<? } ?>

			</div>


			<div class='panel-body'>
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>Vendor</th>
							<th>Quoted Amount (<i class="fa fa-inr"></i>)</th>
                            <th>Total Trip</th>
                            <th>Vendor Ratings</th>
							<th>Remark</th>
							<th>Responded at</th>
							<th>Status</th>
						</tr>
					</thead><tbody>
						<?
						foreach ($data as $val)
						{
							?> 

							<tr>
								<td><?= $val['vnd_name'] ?></td>
								<td><?= $val['vqt_amount'] ?></td>
                                <td><?= $val['trip'] ?></td>
                                <td><?= $val['ratings'] ?></td>
								<td><?= $val['vqt_description'] ?></td>
								<td><?= DateTimeFormat::DateTimeToLocale($val['vqt_created']) ?></td>
								<td><?= ($val['vqt_status'] == 1) ? 'Accepted' : 'Denied' ?></td>
							</tr>

							<?
						}
						?></tbody></table> 
			</div> 
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$('.close').html('<i class="fa fa-close fa-2x text-danger"></i>');
})
</script>

