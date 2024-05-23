<div class="row mt10 testimonials-box">                                        
                  <?php  if(count($model) > 0) {
						foreach ($model as $data) {                      
                        ?>
                        <div class="col-xs-12 col-sm-6 col-md-6 mb40">
                            <div class="row">								
                                <div class="col-xs-12 col-sm-3 col-md-2 p0">
                                    <div class="test-name"><?= $data['initial']; ?>									
								</div>
                                </div>								      
                                <div class="col-xs-12 col-sm-9 col-md-10">      
									<?php 
										if($data['rtg_customer_overall'] >= 4) {
											echo $data['rtg_customer_review'];
										} else {
											if(strlen($data['rtg_customer_review']) <= 50) {
												echo $data['rtg_customer_review'];
											}
										}
									 ?>
									<?php
										$strRating	 = '&nbsp;';
										$rating_star = floor($data['rtg_customer_overall']);
										if ($rating_star > 0)
										{
											$strRating .= '(';
											for ($s = 0; $s < $rating_star; $s++) {										
												$strRating .= '<i class="fa fa-star orange-color"></i>';
											}
											if ($data['rtg_customer_overall'] > $rating_star) {										
												$strRating .= '<i class="fa fa-star-half orange-color"></i> ';
											}										
										}
										$strRating.=')';
									?>                           
                                    <p class="m0 block-color3"><i><b>- <?= $data['user_name']; ?></b></i>&nbsp;<?=$strRating?></p>
                                    <p class="m0"><b><?=$data['cities'];?>,</b> <i><?=Booking::model()->getBookingType($data['bkg_booking_type']);?></i></p>
                                    <p class="m0 block-color3"><i><b><?= date('jS M Y', strtotime($data['rtg_customer_date'])) ?></b></i></p>
                                </div>
                            </div>
                        </div>
                    <? } ?>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <?php
                        // the pagination widget with some options to mess
                        $this->widget('booster.widgets.TbPager', array('pages' => $usersList->pagination,'displayFirstAndLast'=> false,'maxButtonCount'=>0,'nextPageLabel'=>'Next','prevPageLabel'=>'Previous'));
						
                        ?>
                    </div>
                </div>
				  <?php } else { ?>
				  <div class="row">
                    <div class="col-xs-12">
                        <?php echo 'No Records Found.'; ?>
                    </div>
                </div>
				  <?php } ?>

