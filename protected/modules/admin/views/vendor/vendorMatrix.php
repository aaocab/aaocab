<link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.min.css">
<div class="page-title">
	<h3>Accounts Panel :: Vendor Matrix</h3>

</div>

<section id="section7">
	<div class=" p20">
		<div class="row">
			<div class="col-xs-12 col-sm-5 table-responsive">
				<table class="table table-striped table-bordered">
					<tbody>
						<tr>
							<td><b>Total Completed trip in last 30 days</b></td>
							<td><?=$data['vrs_total_completed_days_30'] ?></td>
						</tr>
						<tr>
							<td><b>Total Used car in last 30 days</b></td>
							<td><?=$data['vrs_total_vehicle_30'] ?></td>
						</tr>
						<tr>
							<td><b>Sticky Score</b></td>
							<td><?=$data['vrs_sticky_score'] ?></td>
						</tr>
						<tr>
							<td><b>Driver App used</b></td>
							<td><?=$data['vrs_driver_app_used'] ?></td>
						</tr>

						<tr>
							<td><b>Penalty Count</b></td>
							<td><?=$data['vrs_penalty_count'] ?></td>
						</tr>
						                       
						<!--- <tr>
							<td><b>Vendor Margin</b></td>
							<td><?=$data['vrs_margin'] ?></td>
						</tr>
						<tr>
							<td><b>Bid wining percentage</b></td>
							<td><?=$data['vrs_bid_win_percentage'] ?></td>
						</tr>-->
					</tbody></table>

			</div>
		</div>
	</div>	
</section>
