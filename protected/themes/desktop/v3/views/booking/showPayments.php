<style>
	.paytable thead tr th, .paytable tbody tr td {
		padding: 5px 7px;
		font-size: 12px;
	}
	.paytable tbody tr:last-child td {
		border-bottom: 1px solid;
	}
</style>
<?php
$countdata = $pgData->getRowCount();
if ($countdata == 0)
{
	echo "No records found";
	return;
}
?>
<div class="panel panel-primary compact" >
	<div class="panel-heading">
		<div class="row m0">
			<div class="col-xs-12 col-sm-6 pb5 pl0">
				<div class="summary">
					Displaying 1-<?= $countdata ?> results
				</div>
			</div>
		</div>
	</div>
	<div class="panel-body table-responsive">
		<table class="table paytable table-striped table-bordered mb0" style="padding: 0px;">
			<thead class="font-11 ">
				<tr>
					<th class="text-center" >
						Transaction Id
					</th>
					<th class="text-center" >
						Payment Ref Id
					</th>
					<th class="text-center" >
						Payment Gateway
					</th>
					<th class="text-center" >
						Response
					</th>

					<th class="text-center" >
						Initiate Date
					</th>
					<th class="text-center" >
						Last Response Date
					</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$i = 0;
				foreach ($pgData as $key => $value)
				{
					$i++;
					?>
					<tr class="<?php echo (($i & 1) ? 'even' : 'odd') ?>">
						<td class="text-center" >
							<?php echo $value['apg_code'] ?>
						</td>
						<td class="text-center" >
							<?php echo $value['apg_txn_id'] ?>
						</td>
						<td class="text-center" >
							<?php echo $value['ledgerName'] ?>
						</td>
						<td class="text-center" >
							<?php echo $value['apg_remarks'] ?>
						</td>

						<td class="text-center" >
							<?php echo DateTimeFormat::DateTimeToLocale($value['apg_start_datetime']) ?>
						</td>
						<td class="text-center" >
							<?php echo DateTimeFormat::DateTimeToLocale($value['apg_complete_datetime']) ?>
						</td>
					</tr>
					<?php
				}
				?>
			</tbody>
		</table>
	</div>
	<div class="panel-footer">
		<div class="row mt5">
			<div class="col-xs-12 col-sm-6 ">
				<div class="summary">
					Displaying 1-<?= $countdata ?> results
				</div>
			</div>
		</div>
	</div>
</div>
