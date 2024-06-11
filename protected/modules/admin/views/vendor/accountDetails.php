<div class="row">
	<div class="col-xs-12">
		<div class="row">
			<div class="col-xs-12">
				<div class="row mb20">
					<div class="col-xs-12 widget-tab-box3">
						<div class="col-xs-12">
							<div class="row">
								<div class="col-xs-4">
									<p class="mb0 color-gray">Credit Limit</p>
									<p class="font-16"><b><?= $data['vnd_credit_limit'] ?></b></p>
								</div>
								<div class="col-xs-4">
									<p class="mb0 color-gray">Effective Credit Limit</p>
									<p class="font-16"><b><?php
											if ($data['vnd_effective_credit_limit'] != 0)
											{
												echo '&#x20B9;' . $data['vnd_effective_credit_limit'];
											}
											else
											{
												echo '&#x20B9;0';
											}
											?> </b> </p>
								</div>
								<div class="col-xs-4">
									<p class="mb0 color-gray">Overdue Days</p>
									<p class="font-16"><b>
											<?php
											if ($data['vnd_effective_overdue_days'] != 0)
											{
												echo $data['vnd_effective_overdue_days'];
											}
											else
											{
												echo '0';
											}
											?>
										</b>
									</p>
								</div>
							</div>
						</div>
						<div class="col-xs-12">
							<div class="row">
								<div class="col-xs-4">
									<p class="mb0 color-gray">Security Deposit</p>
									<p class="font-16"><b>
											<?php
											if ($data['vnd_security_amount'] > 0)
											{
												$securty_date = ($data["vnd_security_receive_date"] != "" ? ' on ' . DateTimeFormat::DateToDatePicker($data["vnd_security_receive_date"]) : "");
												echo '&#x20B9;' . $data['vnd_security_amount'] . $securty_date;
											}
											else
											{
												echo '&#x20B9;0';
											}
											?>
										</b>
									</p>
								</div>
								<div class="col-xs-4">
									<p class="mb0 color-gray">Last Payment Received Amount</p>
									<p class="font-16"><b><?php
											$paymentRecv = ($acctData['paymentReceived'] > 0) ? $acctData['paymentReceived'] : '0';
											echo '&#x20B9;' . $paymentRecv . '</i>';
											?></b></p>
								</div>
								<div class="col-xs-4">
									<p class="mb0 color-gray">Last Payment Received date</p>
									<p class="font-16">
										<b>
											<?php
											echo ($acctData['ReceivedDate'] != '') ? DateTimeFormat::DateTimeToDatePicker($acctData['ReceivedDate']) : '';
											?>		
										</b>
									</p>
								</div>
							</div>
						</div>
						<div class="col-xs-12">
							<div class="row">	
								<div class="col-xs-4">
									<p class="mb0 color-gray">Running Balance</p>
									<p class="font-16"><b><?= '&#x20B9;' . ($calAmount['vendor_amount'] != "" ? $calAmount['vendor_amount'] : 0); ?></b></p>
								</div>
								<div class="col-xs-4">
									<p class="mb0 color-gray">Withdrawable Balance</p>
									<p class="font-16"><b><?= '&#x20B9;' . $data['withdrawable_balance']; ?></b></p>
								</div>
								<div class="col-xs-4">
									<p class="mb0 color-gray">Rating</p>
									<p class="font-16"><b><?= ($data['vnd_overall_rating'] != null ? round($data['vnd_overall_rating']) : 0); ?>/5</b></p>
								</div>
							</div>
						</div>	
					</div>
				</div>
				<div class="col-xs-12 pl0 ml0"><?php echo CHtml::link('Show Account Details', Yii::app()->createUrl('admin/vendor/vendoraccount/', ['vnd_id' => $data['vnd_id']]), ['class' => 'btn btn-primary mb10', 'target' => "_blank"]) ?></div>
			</div>
		</div>
	</div>
</div>