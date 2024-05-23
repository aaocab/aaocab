<?php
$this->layout	 = 'column1';
?>
<?php
$redeemUrl		 = Yii::app()->createAbsoluteUrl('/', ['credit' => '1']);
$walletUrl		 = Yii::app()->createAbsoluteUrl('/', ['wallet' => '1']);
?>
    
	<div id="menu-hider"></div>
	<div class="content-boxed-widget">
		<div class="content uppercase bottom-0 p0">
			<p class="font-18 bottom-0">Gozo Wallet History <span class="pull-right"><img src="/images/history.svg" alt="" width="25"></span></p>
		</div>
	</div>

	<div class="content-boxed-widget">
			<div class="content p0 bottom-0">
			  <div class="one-half text-center"> 
				<b class="uppercase">Total Gozo Coins</b><br>
				<b class="font-15 color-green3-dark"><?php echo $totalAmount; ?></b><br>
				<?php
				if ($totalAmount != '' && $totalAmount > 0)
				{
					?>
					<!--						<a href="#" class="uppercase btn-green pl10 pr10 mr5">Redeem Now</a>-->
					<button type="button" class="uppercase btn-green pl10 pr10 mr5 mt5" id="redeemBtn" name="redeemBtn" title="Redeem Now"><a href="<?= $redeemUrl ?>" class="default-link" style="color: white">Redeem Now</a></button>

				<?php }				
				?>
			  </div>
			  <div class="one-half last-column text-center"> 
				<b class="uppercase">Wallet Balance</b><br>
				<b class="font-15 color-green3-dark"><?php echo $walletBalance; ?></b><br>
				<?php
				if ($walletBalance != '' && $walletBalance > 0)
				{
					?>
					<!--						<a href="#" class="uppercase btn-green pl10 pr10 mr5">Redeem Now</a>-->
					<button type="button" class="uppercase btn-green pl10 pr10 mr5 mt5" id="redeemBtn" name="redeemBtn" title="Redeem Now"><a href="<?= $walletUrl ?>" class="default-link" style="color: white">Redeem Now</a></button>

				<?php }
				?>
			  </div>
<div class="clear"></div>
			</div>
	</div>
    <?php
	//if (count($datarecordSet)>0)	
	$walletBalance = (empty($walletBalance)) ? 0 : $walletBalance;
	if ($totalAmount > 0 || $walletBalance > 0)
	{
	?>
	<div class="container content-style mb0 mobile-type tab-styles">
		<div class="above-overlay">
			<div class="tab-style tabs">
				<div class="t-style text-center" data-active-tab-pill-background="bg-green-dark">
					<a href="#" class="devPrimaryTab1 mainTab active" data-tab-pill="tab-pill-1a">Wallet Balance</a>
					<a href="#" data-tab-pill="tab-pill-3a" class="devPrimaryTab3 mainTab">Gozo Coins</a>
				</div>
				<div class="tab-pill-content p10">
				  <div class="tab-item active-tab" id="tab-pill-1a" style="display: block;">
								<?php
								if (!empty($dataProvider3)) {
									/* @var $dataProvider2 TbGridView */
									$params1 = array_filter($_REQUEST);
									$dataProvider3->getPagination()->params = $params1;
									$dataProvider3->getSort()->params = $params1;
									$allData = $dataProvider3->getData();
									foreach ($allData as $record)
									{
									?>
									<div class="content-boxed-widget">
												 <div class="accordion-content" style="display: block;">
														 <div class="content p0 bottom-5">
															 <div class="line-s4 gray-color">Date</div>
															 <div class="line-t4 text-right"><?php echo  date("d/m/Y H:i:s", strtotime($record['created'])); ?></div>
															 <div class="clear"></div>
														 </div>
														 <div class="content p0 bottom-5">
															 <div class="line-s4 gray-color">Amount</div>
															 <div class="line-t4 text-right"><?= (-1*$record['adt_amount']) ?></div>
															 <div class="clear"></div>
														 </div>
														 <div class="content p0 bottom-5">
															 <div class="line-s4 gray-color">Description</div>
															 <div class="line-t4 text-right"><?= $record['act_remarks'] ?></div>
															 <div class="clear"></div>
														 </div>
                                                 </div>
                                    </div>
                                  <?php
									}
//									$this->widget('booster.widgets.TbGridView', array(
//										'responsiveTable' => true,
//										'dataProvider' => $dataProvider3,
//										'pager' => ['maxButtonCount' => 5, 'class' => 'booster.widgets.TbPager'],
//										'id' => 'walletListGrid',
//										'template' => "
//												{items}<div></div>",
//										'itemsCssClass' => 'table table-striped table-bordered dataTable mb0',
//										'htmlOptions' => array('class' => 'compact'),
//										'columns' => array(
//											array('name' => 'created', 'value' => 'date("d/m/Y H:i:s",strtotime($data["created"]))', 'sortable' => false, 'htmlOptions' => array('class' => 'text-left'), 'headerHtmlOptions' => array('class' => 'col-xs-3 text-center'), 'header' => 'Date'),
//											array('name' => 'adt_amount', 'value' => $data['adt_amount'], 'sortable' => false, 'htmlOptions' => array('class' => 'text-left'), 'headerHtmlOptions' => array('class' => 'col-xs-3 text-center'), 'header' => 'Amount'),
//											array('name' => 'act_remarks', 'value' => $data['act_remarks'], 'sortable' => false, 'htmlOptions' => array('class' => 'text-left'), 'headerHtmlOptions' => array('class' => 'col-xs-3 text-center'), 'header' => 'Description'),
//									)));
								}
								?>
				  </div>
				  <div class="tab-item devSecondaryTab3" id="tab-pill-3a" style="display: none;">
				        <div class="content-boxed-widget gradient-green-blue text-center font-18">Active Gozo Coins</div>
							<?php } ?>
							 <?
							 if (!empty($dataProvider))
							 {
								 $i = rand(0, 100);		
								 foreach ($datarecordSet as $record)
								 {
								 ?>
									 <div class="content-boxed-widget">
												 <div class="accordion-content" style="display: block;">
														 <div class="content p0 bottom-5">
															 <div class="line-s4 gray-color">Date</div>
															 <div class="line-t4 text-right"><?= $record['created'] ?></div>
															 <div class="clear"></div>
														 </div>
														 <div class="content p0 bottom-5">
															 <div class="line-s4 gray-color">Debit/Credit</div>
															 <div class="line-t4 text-right">
																 <?
																 if ($record['ptp_id'] == '5')
																 {
																	 echo "-" . round($record['amount'], 1);
																 }
																 else if ($record['ptp_id'] == '0')
																 {
																	 echo "+" . round($record['amount'], 1);
																 }
																 ?>
															 </div>
															 <div class="clear"></div>
														 </div>
														 <div class="content p0 bottom-5">
															 <div class="line-s4 gray-color">Type</div>
															 <div class="line-t4 text-right">
																 <?

																 if ($record['ptp_id'] == '0')
																 {
																	 // 1:promo,2:refund,3:referral,4:booking,5:others   
																	 switch ($record['ucr_type'])
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
																	 if($record['ptp_id'] == '5' && $record['ucr_type']==4)
																	 {
																	  echo " Credits Used in Booking";
																	 }
																	 else
																	 {
																		 echo $record['ucr_type'];
																	 }
																 }
																 ?>
															 </div>
															 <div class="clear"></div>
														 </div>
														 <div class="content p0 bottom-5">
															 <div class="line-s4 gray-color">Max Use</div>
															 <div class="line-t4 text-right">
																 <?
																 $maxStr = UserCredits::model()->getMaxUseTypes();
																 echo $maxStr[$record['ucr_maxuse_type']];										
																 ?>
															 </div>
															 <div class="clear"></div>
														 </div>
														 <div class="content p0 bottom-5 line-height18">
															 <span class="gray-color font-13">Description</span><br><?= $record['description'] ?>
														 </div>
														 <div class="content p0 bottom-5">
															 <div class="line-s4 gray-color">Valid Upto</div>
															 <div class="line-t4 text-right color-red-dark">
								 <!--Expired <i class="fas fa-times-circle"></i>-->
																 <?
																 if ($record['ucr_validity'] > date("Y-m-d H:i:s"))
																 {
																	 echo date("d/m/Y H:i:s", strtotime($record['ucr_validity']));
																 }
																 else if ($record['ucr_validity'] < date("Y-m-d H:i:s"))
																 {
																	 echo "Expired";
																 }
																 else
																 {
																	 echo 'NA';
																 }
																 ?>
															 </div>
															 <div class="clear"></div>
														 </div>
												 </div>
									 </div>

									 <?
									 $i++;
								 }
							 }
							 ?>		
							 <?php
							 if (count($datarecordSet2) > 0)
							 {
							 ?>
							 <div class="content-boxed-widget font-18 text-center gradient-orange">Pending Gozo Coins</div>
							 <? } ?>
							 <?
							 if (!empty($dataProvider2))
							 {

								 $j = rand(100, 200);
								 foreach ($datarecordSet2 as $record)
								 {
									 ?>
									 <div class="content-boxed-widget">
												 <div class="accordion-content" style="display: block;">
														 <div class="content p0 bottom-5">
															 <div class="line-s4 gray-color">Date</div>
															 <div class="line-t4 text-right"><?= $record['created'] ?></div>
															 <div class="clear"></div>
														 </div>
														 <div class="content p0 bottom-5">
															 <div class="line-s4 gray-color">Debit/Credit</div>
															 <div class="line-t4 text-right">
																 <?
																 if ($record['ptp_id'] == '5')
																 {
																	 echo "-" . round($record['amount'], 1);
																 }
																 else if ($record['ptp_id'] == '0')
																 {
																	 echo "+" . round($record['amount'], 1);
																 }
																 ?>
															 </div>
															 <div class="clear"></div>
														 </div>
														 <div class="content p0 bottom-5">
															 <div class="line-s4 gray-color">Type</div>
															 <div class="line-t4 text-right">
																 <?
																 if ($record['ptp_id'] == '0')
																 {
																	 // 1:promo,2:refund,3:referral,4:booking,5:others   
																	 switch ($record['ucr_type'])
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
																	 echo $record['ucr_type'];
																 }
																 ?>
															 </div>
															 <div class="clear"></div>
														 </div>
														 <div class="content p0 bottom-5">
															 <div class="line-s4 gray-color">Max Use</div>
															 <div class="line-t4 text-right">
																 <?
																 echo $maxStr = UserCredits::model()->getMaxUseTypes($record['ucr_maxuse_type']);
																 //if($record['ucr_max_use']>0){
																 //$maxStr.=" (Max use: ".$record['ucr_max_use'].")";
																 //}
																 //return $maxStr;
																 ?>
															 </div>
															 <div class="clear"></div>
														 </div>
														 <div class="content p0 bottom-5">
															 <span class="gray-color font-13">Description</span><br><?= $record['description'] ?>
														 </div>
						 <!--								<div class="line-f4 mb10">
															 <div class="line-s4 gray-color">Valid Upto</div>
															 <div class="line-t4 text-right color-red-dark">

																 <?
																 /*if ($record['ucr_validity'] > date("Y-m-d H:i:s"))
																 {
																	 echo date("d/m/Y H:i:s", strtotime($record['ucr_validity']));
																 }
																 else if ($record['ucr_validity'] < date("Y-m-d H:i:s"))
																 {
																	 echo "Expired";
																 }
																 else
																 {
																	 echo 'NA';
																 }*/
																 ?>
															 </div>
														 </div>-->
												 </div>
									 </div>
								 <?
								 $j++;
							 }
						 }
						 ?>
				  </div>
				</div>
			</div>
		</div>	
	</div>	
