

<div class="col-xs-12 pt10"> 
    <div class="col-xs-4"><b>Agent ID: </b> <?= $totAgentCredit['agentid'] ?></div>
    <div class="col-xs-4"><b>Name: </b> <?= $totAgentCredit['name'] ?></div>
    <div class="col-xs-4"><b>Company: </b> <?= $totAgentCredit['company'] ?></div>
	<? if ($totAgentCredit['type'] == 1)
	{
		?>
		<div class="col-xs-4"><b>Corporate Code: </b> <?= $totAgentCredit['corporatecode'] ?></div>
<? } ?>
</div>
<div class="panel">
    <div class="panel panel-heading"></div>
    <div class="panel panel-body">


        <table class="table table-bordered">
			<?
			$ctr				 = 0;
			$countTransaction	 = count($agentmodels);
			?>
            <tr class="bg-gray"><td colspan="9"> <?
					$this->widget('CLinkPager', array('pages' => $agentList->pagination));
					?><span class="pull-right">Total Records <?= $countTransaction ?></span></td></tr>
            <tr class="blue2 white-color">
                <td align="center"><b>Transaction Date</b></td>
                <td align="center"><b>Booking ID</b></td>
                <td align="center"><b>Pickup Date</b></td>
                <td align="center"><b>Booking Info</b></td>
                <td align="center"><b>Advanced Collected</b></td>
                <td class="text-center"><b>amount (<i class="fa fa-inr"></i>)</b><br>(+=credit to gozo,<br>-=credit to agent)</td>
                <td align="center"><b>Notes</b></td>
                <td align="center"><b>Who</b></td>
                <td align="center"><b>Running Balance</b></td>
            </tr>
			<?php
			if (count($agentmodels) > 0)
			{
				foreach ($agentmodels as $agent)
				{
					$bookingId		 = ($agent['bkg_booking_id'] == NULL) ? 'NA' : $agent['bkg_booking_id'];
					$pickupDate		 = ($agent['bkg_booking_id'] == NULL) ? 'NA' : date('d/m/Y', strtotime($agent['bkg_pickup_date']));
					$fromCity		 = ($agent['city'] == NULL) ? 'NA' : trim($agent['city']);
					$advanceAmt		 = ($agent['advance_amount'] == NULL || $agent['advance_amount'] == '0') ? 'NA' : trim($agent['advance_amount']);
					$balance[$ctr]	 = $agent['agt_trans_amount'];
					$index			 = ($countTransaction - $ctr);
					$bookingDetail	 = ($agent['bkg_booking_id'] == NULL) ? 'NA' : $bookingId . "<BR>" . $fromCity;
					?>
					<tr>
						<td><?php echo date('d/m/Y', strtotime($agent['agt_trans_date'])); ?></td>
						<td><?= ($agent['bkg_booking_id'] == NULL) ? 'NA' : $agent['bkg_booking_id'] ?></td>
						<td><?= $pickupDate ?></td>
						<td><?= $bookingDetail ?></td>
						<td align="right"><?= $advanceAmt; ?></td>
						<td class="text-right"><?php echo round(trim($agent['agt_trans_amount'])); ?></td>
						<td><?php echo trim($agent['agt_trans_remarks']); ?></td>
						<td><b><?php echo trim($agent['adm_name']); ?></b></td>
						<td align="right"><?= $agent['running']; ?></td>
					</tr>
					<?php
				}
			}
			else
			{
				?>
				<tr><td colspan="10">No Records  Found.</td></tr>
			<?php }
			?>
        </table>

		<?
		$this->widget('CLinkPager', array('pages' => $agentList->pagination));
		?>
    </div>
</div>
