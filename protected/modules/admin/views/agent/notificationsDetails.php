<style>
	th, td{
		text-align: center;
	}
</style>
<?
$chTrue		 = '<i class="fa fa-check" aria-hidden="true" style="color:green"></i>';
$chFalse	 = '<i class="fa fa-close" aria-hidden="true" style="color:red"></i>'
?>
<div class="row">
	<div class="col-xs-12">
		<table class="table table-bordered ">
			<tr class="bg-primary">
				<th rowspan="2"></th>
				<th colspan="4">Partner</th>
				<th colspan="4">Traveller</th>    
				<th colspan="4">Relationship Manager</th>   
			</tr>
			<tr class="bg-info">				
				<td>Email</td><td>SMS</td><td>App</td><td>WhatsApp</td>
				<td>Email</td><td>SMS</td><td>App</td><td>WhatsApp</td>
				<td>Email</td><td>SMS</td><td>App</td><td>WhatsApp</td>
			</tr>
			<?
			$arrEvents	 = AgentMessages::getEvents();
			foreach ($arrEvents as $key => $value)
			{
				$isAgentEmail	 = false;
				$isAgentSMS		 = false;
				$isAgentApp		 = false;
				$isAgentWhatsApp = false;

				$isTrvlEmail	 = false;
				$isTrvlSMS		 = false;
				$isTrvlApp		 = false;
				$isTrvlWhatsApp	 = false;

				$isRmEmail		 = false;
				$isRmSMS		 = false;
				$isRmApp		 = false;
				$isRmWhatsApp	 = false;

				$agtMsgModel = AgentMessages::model()->getByEventAndAgent($agentId, $key);
				if ($agtMsgModel != '' || $agtMsgModel != NULL)
				{
					?>    
					<tr>
						<th><?= $arrEvents[$key] ?></th>
						<td><?php echo ($agtMsgModel->agt_agent_email == 1) ? $chTrue : $chFalse; ?></td>
						<td><?php echo ($agtMsgModel->agt_agent_sms == 1) ? $chTrue : $chFalse; ?></td>
						<td><?php echo ($agtMsgModel->agt_agent_app == 1) ? $chTrue : $chFalse; ?></td>
						<td><?php echo ($agtMsgModel->agt_agent_whatsapp == 1) ? $chTrue : $chFalse; ?></td>
						<td><?php echo ($agtMsgModel->agt_trvl_email == 1) ? $chTrue : $chFalse; ?></td>
						<td><?php echo ($agtMsgModel->agt_trvl_sms == 1) ? $chTrue : $chFalse; ?></td>
						<td><?php echo ($agtMsgModel->agt_trvl_app == 1) ? $chTrue : $chFalse; ?></td>
						<td><?php echo ($agtMsgModel->agt_trvl_whatsapp == 1) ? $chTrue : $chFalse; ?></td>
						<td><?php echo ($agtMsgModel->agt_rm_email == 1) ? $chTrue : $chFalse; ?></td>
						<td><?php echo ($agtMsgModel->agt_rm_sms == 1) ? $chTrue : $chFalse; ?></td>
						<td><?php echo ($agtMsgModel->agt_rm_app == 1) ? $chTrue : $chFalse; ?></td>
						<td><?php echo ($agtMsgModel->agt_rm_whatsapp == 1) ? $chTrue : $chFalse; ?></td>

					</tr>
					<?
				}
			}
			?>
		</table>
	</div>
</div>