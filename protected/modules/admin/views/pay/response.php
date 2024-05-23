<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="row">
	<?
	if ($type == 'statement')
	{
		$responseText = json_decode($responseText, true)
		?>
		<div class="col-xs-12  alert alert-info"  >
			<div class="col-xs-6"> Account No. : <?= $responseText['ACCOUNTNO'] ?></div>
			<div class="col-xs-6 text-right"> User ID : <?= $responseText['USER_ID'] ?></div> 
			<div  class="col-xs-12">

				<?
				if (isset($responseText['Record']) && $responseText['RESPONSE'] == 'SUCCESS')
				{
					$records = $responseText['Record'];
					?>

					<table class="table table-bordered">
						<thead>
							<tr>
								<th>Date</th>
								<th>Transaction Date</th>
								<th>Transaction ID</th>
								<th>Cheque No.</th>
								<th>Type</th>
								<th>Remarks</th>
								<th>Amount</th>
								<th>Balance</th>
							</tr></thead>
						<tbody>
							<?
							foreach ($records as $i => $data)
							{
								?> 
								<tr>
									<td><?= $data['VALUEDATE'] ?></td>
									<td><?= $data['TXNDATE'] ?></td>
									<td><?= $data['TRANSACTIONID'] ?></td>
									<td><?= $data['CHEQUENO'] ?></td>
									<td><?= $data['TYPE'] ?></td>
									<td><?= $data['REMARKS'] ?></td>
									<td><?= $data['AMOUNT'] ?></td>
									<td><?= $data['BALANCE'] ?></td>
								</tr>

							<? }
							?>


						</tbody>

					</table>



					<?
				}
				?>
			</div>



		</div>
		<?
	}
	else
	{
		?>
		<div class="col-xs-12  alert alert-info"  >
			<div style="text-align:center;" class="col-xs-12">
				<?= $responseText; ?>
			</div>



		</div>
	<? }
	?>
</div> 