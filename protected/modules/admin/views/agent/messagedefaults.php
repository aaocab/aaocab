
<style>

    .checkbox{
        margin-left: 35px
    }
</style>
<div class="panel">
    <div class="panel-heading">Partner notification defaults</div>
    <div class="panel-body">
        <div class="row">
			<?php
			$form			 = $this->beginWidget('booster.widgets.TbActiveForm', array(
				'id'					 => 'agent-notification-form', 'enableClientValidation' => FALSE,
				'clientOptions'			 => array(
					'validateOnSubmit'	 => true,
					'errorCssClass'		 => 'has-error'
				),
				'enableAjaxValidation'	 => false,
				'errorMessageCssClass'	 => 'help-block',
				'htmlOptions'			 => array(
					'class'			 => 'form-horizontal', 'enctype'		 => 'multipart/form-data', 'autocomplete'	 => "off",
				),
			));
			/* @var $form TbActiveForm */
			?>
			<table class="table table-bordered">
				<tr>
					<th></th>
					<th colspan="3">Partner</th>
					<th colspan="3">Traveller</th>    
					<th colspan="3">Relationship Manager</th>   
				</tr>
				<tr>
					<td></td>
					<td>Email</td><td>SMS</td><td>App</td>
					<td>Email</td><td>SMS</td><td>App </td>
					<td>Email</td><td>SMS</td><td>App </td>
				</tr>
				<?
				$arrEvents		 = AgentMessages::getEvents();
				$AgentMessages	 = new AgentMessages();
				foreach ($arrEvents as $key => $value)
				{
					$isAgentEmail	 = false;
					$isAgentSMS		 = false;
					$isAgentApp		 = false;

					$isTrvlEmail = false;
					$isTrvlSMS	 = false;
					$isTrvlApp	 = false;

					$isRmEmail	 = false;
					$isRmSMS	 = false;
					$isRmApp	 = false;


					$agtMsgModel = AgentMessages::model()->getByEventAndAgent($agentId, $key);

					if ($agtMsgModel == '')
					{
						$agtMsgModel = new AgentMessages();
						$agtMsgModel->getMessageDefaults($key);
					}

					if ($agtMsgModel != '')
					{
						$isAgentEmail	 = ($agtMsgModel->agt_agent_email == 1) ? true : false;
						$isAgentSMS		 = ($agtMsgModel->agt_agent_sms == 1) ? true : false;
						$isAgentApp		 = ($agtMsgModel->agt_agent_app == 1) ? true : false;

						$isTrvlEmail = ($agtMsgModel->agt_trvl_email == 1) ? true : false;
						$isTrvlSMS	 = ($agtMsgModel->agt_trvl_sms == 1) ? true : false;
						$isTrvlApp	 = ($agtMsgModel->agt_trvl_app == 1) ? true : false;

						$isRmEmail	 = ($agtMsgModel->agt_rm_email == 1) ? true : false;
						$isRmSMS	 = ($agtMsgModel->agt_rm_sms == 1) ? true : false;
						$isRmApp	 = ($agtMsgModel->agt_rm_app == 1) ? true : false;
					}

					if ($notifydata != '' && $notifydata != null && $notifydata != 'null' && $notifydata != '""')
					{
						$arrAgentNotifyOpt	 = json_decode($notifydata, true);
						$isAgentEmail		 = ($arrAgentNotifyOpt['agt_agent_email'][$key] == 1) ? true : false;
						$isAgentSMS			 = ($arrAgentNotifyOpt['agt_agent_sms'][$key] == 1) ? true : false;
						$isAgentApp			 = ($arrAgentNotifyOpt['agt_agent_app'][$key] == 1) ? true : false;

						$isTrvlEmail = ($arrAgentNotifyOpt['agt_trvl_email'][$key] == 1) ? true : false;
						$isTrvlSMS	 = ($arrAgentNotifyOpt['agt_trvl_sms'][$key] == 1) ? true : false;
						$isTrvlApp	 = ($arrAgentNotifyOpt['agt_trvl_app'][$key] == 1) ? true : false;

						$isRmEmail	 = ($arrAgentNotifyOpt['agt_rm_email'][$key] == 1) ? true : false;
						$isRmSMS	 = ($arrAgentNotifyOpt['agt_rm_sms'][$key] == 1) ? true : false;
						$isRmApp	 = ($arrAgentNotifyOpt['agt_rm_app'][$key] == 1) ? true : false;
					}
					?>    
					<tr>
						<th><?= $arrEvents[$key] ?></th>
						<td><?= $form->checkboxGroup($AgentMessages, 'agt_agent_email[' . $key . ']', ['label' => '', 'widgetOptions' => ['htmlOptions' => ['checked' => $isAgentEmail]], 'inline' => true]); ?></td>
						<td><?= $form->checkboxGroup($AgentMessages, 'agt_agent_sms[' . $key . ']', ['label' => '', 'widgetOptions' => ['htmlOptions' => ['checked' => $isAgentSMS]], 'inline' => true]); ?></td>
						<td><?= $form->checkboxGroup($AgentMessages, 'agt_agent_app[' . $key . ']', ['label' => '', 'widgetOptions' => ['htmlOptions' => ['checked' => $isAgentApp]], 'inline' => true]); ?></td>

						<td><?= $form->checkboxGroup($AgentMessages, 'agt_trvl_email[' . $key . ']', ['label' => '', 'widgetOptions' => ['htmlOptions' => ['checked' => $isTrvlEmail]], 'inline' => true]); ?></td>
						<td><?= $form->checkboxGroup($AgentMessages, 'agt_trvl_sms[' . $key . ']', ['label' => '', 'widgetOptions' => ['htmlOptions' => ['checked' => $isTrvlSMS]], 'inline' => true]); ?></td>
						<td><?= $form->checkboxGroup($AgentMessages, 'agt_trvl_app[' . $key . ']', ['label' => '', 'widgetOptions' => ['htmlOptions' => ['checked' => $isTrvlApp]], 'inline' => true]); ?></td>

						<td><?= $form->checkboxGroup($AgentMessages, 'agt_rm_email[' . $key . ']', ['label' => '', 'widgetOptions' => ['htmlOptions' => ['checked' => $isRmEmail]], 'inline' => true]); ?></td>
						<td><?= $form->checkboxGroup($AgentMessages, 'agt_rm_sms[' . $key . ']', ['label' => '', 'widgetOptions' => ['htmlOptions' => ['checked' => $isRmSMS]], 'inline' => true]); ?></td>
						<td><?= $form->checkboxGroup($AgentMessages, 'agt_rm_app[' . $key . ']', ['label' => '', 'widgetOptions' => ['htmlOptions' => ['checked' => $isRmApp]], 'inline' => true]); ?></td>
					</tr>
					<?
				}
				?>
			</table>
            <div class="col-xs-12 text-center pb10">
				<?= CHtml::button('Save', array('class' => 'btn btn-primary pl30 pr30', 'onclick' => 'savenotifyoptions()')); ?>
            </div>
			<?php $this->endWidget(); ?>
        </div>
    </div>
</div>
