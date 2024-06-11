<div class="panel-advancedoptions" >
    <div class="row">
        <div class="col-md-12">            
            <div class="panel" >
                <div class="panel-body p0">
                    <h3 class="mt0">Gozo Coins
						<span class="pull-right">Credit balance:<?= $totalGozoCoins ?> Coins</span>
					</h3>
					
                    <div class="">
						<!--<h2>Gozo Coins Transaction</h2>-->
                        <div style="width: 100%; overflow: auto;  border: 1px #aaa solid;color: #444;">
							<?php
							if (!empty($dataProvider))
							{ 
								/* @var $dataProvider TbGridView */
								$params									 = array_filter($_REQUEST);
								$dataProvider->getPagination()->params	 = $params;
								$dataProvider->getSort()->params		 = $params;
								$this->widget('booster.widgets.TbGridView', array(
									'id'				 => 'tripdetails-grid' . $qry['booking_id'],
									'responsiveTable'	 => true,
									// 'filter' => FALSE,
									'dataProvider'		 => $dataProvider,
									'template'			 => "<div class='panel-heading border-primary bg-primary text-white'>
                                        <div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div>
                                            <div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                        </div>
                                    </div>
                                    <div class='panel-body'>{items}</div>
                                    ",
									'itemsCssClass'		 => 'table table-striped table-bordered mb0',
									'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
									'columns'			 => array(
										array('name'	 => 'created', 'value'	 => function($data) {
												echo $data['created'];
											}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'), 'header'			 => 'Date'),
										array('name'	 => 'amount', 'value'	 => function($data) {
												if ($data['ptp_id'] == '5')
												{
													echo "-" . round($data['amount'], 1);
												}
												else if ($data['ptp_id'] == '0')
												{
													echo "+" . round($data['amount'], 1);
												}
											}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'), 'header'			 => 'Debit/Credit'),
										array('name'	 => 'ucr_type', 'value'	 => function($data) {
												if ($data['ptp_id'] == '0')
												{
													// 1:promo,2:refund,3:referral,4:booking,5:others   
													switch ($data['ucr_type'])
													{
														case '1':
															echo "Promo";
															break;
														case '2':
															echo "Refund";
															break;
														case '3':
															echo "Referral";
															break;
														case '4':
															echo "Booking";
															break;
														case '5':
															echo "Others(Admin)";
															break;
														case '6':
															echo "Referred";
															break;
														case '7':
															echo "booking(CREDITS PER KM RIDDEN)";
															break;
														case '8':
															echo "booking(CREDITS EQUALS COD AMOUNT)";
															break;
														case '9':
															echo "Notification";
															break;
													}
												}
												else
												{
													if ($data['ptp_id'] == '5' && $data['ucr_type'] == 4)
													{
														echo " Credits Used in Booking";
													}
													else
													{
														echo $data['ucr_type'];
													}
												}
											}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'), 'header'			 => 'Type'),
										array('name'	 => 'ucr_maxuse_type', 'value'	 => function($data) {
												$maxStr = UserCredits::model()->getMaxUseTypes($data['ucr_maxuse_type'],$data['ucr_user_id']);
												if ($data['ucr_max_use'] > 0)
												{
													//  $maxStr.=" (Max use: ".$data['ucr_max_use'].")";
												}
												return $maxStr;
											}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'), 'header'			 => 'Max Use'),
										array('name' => 'description', 'value' => $data['description'], 'sortable' => false, 'htmlOptions' => array('class' => 'text-left'), 'headerHtmlOptions' => array('class' => 'col-xs-6 text-center'), 'header' => 'Description'),
										array('name'	 => 'ucr_validity', 'value'	 => function($data) {
												if ($data['ucr_validity'] > date("Y-m-d H:i:s"))
												{
													echo date("d/m/Y H:i:s", strtotime($data['ucr_validity']));
												}
												else if ($data['ucr_validity'] < date("Y-m-d H:i:s"))
												{
													echo '<span class="text-danger"><i class="fa fa-close"></i>Expired</span>';
												}
												else
												{
													echo 'NA';
												}
											}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'), 'header'			 => 'Valid Upto'),
								)));
							}
							?>
						</div>
						<BR>
						<h2>Pending Gozo Coins</h2>
						<div style="width: 100%; overflow: auto;  border: 1px #aaa solid;color: #444;">
							<?php
							if (!empty($dataProvider2))
							{
								/* @var $dataProvider2 TbGridView */
								$params									 = array_filter($_REQUEST);
								$dataProvider2->getPagination()->params	 = $params;
								$dataProvider2->getSort()->params		 = $params;
								$this->widget('booster.widgets.TbGridView', array(
									'responsiveTable'	 => true,
									'dataProvider'		 => $dataProvider2,
									'pager'				 => ['maxButtonCount' => 5, 'class' => 'booster.widgets.TbPager'],
									'id'				 => 'pendingListGrid',
									'template'			 => "<div class='panel-heading border-primary bg-primary text-white'>                            
                            <div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div>
                                            <div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                        </div>
                            </div>
                            <div class='panel-body table-responsive'>{items}</div><div></div>",
									'itemsCssClass'		 => 'table table-striped table-bordered dataTable mb0',
									'htmlOptions'		 => array('class' => 'panel panel-primary  compact'),
									'columns'			 => array(
										array('name'	 => 'created', 'value'	 => function($data) {
												echo $data['created'];
											}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'), 'header'			 => 'Date'),
										array('name'	 => 'amount', 'value'	 => function($data) {
												if ($data['ptp_id'] == '5')
												{
													echo "-" . round($data['amount'], 1);
												}
												else if ($data['ptp_id'] == '0')
												{
													echo "+" . round($data['amount'], 1);
												}
											}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'), 'header'			 => 'Debit/Credit'),
										array('name'	 => 'ucr_type', 'value'	 => function($data) {
												if ($data['ptp_id'] == '0')
												{
													// 1:promo,2:refund,3:referral,4:booking,5:others   
													switch ($data['ucr_type'])
													{
														case '1':
															echo "Promo";
															break;
														case '2':
															echo "Refund";
															break;
														case '3':
															echo "Referral";
															break;
														case '4':
															echo "Booking";
															break;
														case '5':
															echo "Others(Admin)";
															break;
														case '6':
															echo "Referred";
															break;
														case '7':
															echo "booking(CREDITS PER KM RIDDEN)";
															break;
														case '8':
															echo "booking(CREDITS EQUALS COD AMOUNT)";
															break;
													}
												}
												else
												{
													echo $data['ucr_type'];
												}
											}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'), 'header'			 => 'Type'),
										array('name'	 => 'ucr_maxuse_type', 'value'	 => function($data) {
												$maxStr = UserCredits::model()->getMaxUseTypes($data['ucr_maxuse_type'],$data['ucr_user_id']);
												if ($data['ucr_max_use'] > 0)
												{
													//  $maxStr.=" (Max use: ".$data['ucr_max_use'].")";
												}
												return $maxStr;
											}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'), 'header'			 => 'Max Use'),
										array('name' => 'description', 'value' => $data['description'], 'sortable' => false, 'htmlOptions' => array('class' => 'text-left'), 'headerHtmlOptions' => array('class' => 'col-xs-6 text-center'), 'header' => 'Description'),
								)));
							}
							?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('#tripdetails-grid-<?= $qry['booking_id'] ?> .tScore .a1').click(function (e) {
        e.preventDefault();
        return showReturnDetails(this);
    });
</script>