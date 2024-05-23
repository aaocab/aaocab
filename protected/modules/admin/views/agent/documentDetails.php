<?php
$path			 = '/images/no-image.png';
$ownerPic		 = ($docpath['agt_owner_photo'] != '' && $docpath['agt_owner_photo'] != NULL) ? $docpath['agt_owner_photo'] : $path;
$panPic			 = ($docpath['agt_pan_card'] != '' && $docpath['agt_pan_card'] != NULL) ? $docpath['agt_pan_card'] : $path;
$aadharPic		 = ($docpath['agt_aadhar'] != '' && $docpath['agt_aadhar'] != NULL) ? $docpath['agt_aadhar'] : $path;
$voterPic		 = ($docpath['arl_voter_id_path'] != '' && $docpath['arl_voter_id_path'] != NULL) ? $docpath['arl_voter_id_path'] : $path;
$addressProofPic = ($docpath['agt_company_add_proof'] != '' && $docpath['agt_company_add_proof'] != NULL) ? $docpath['agt_company_add_proof'] : $path;
$licensePic		 = ($docpath['arl_driver_license_path'] != '' && $docpath['arl_driver_license_path'] != NULL) ? $docpath['arl_driver_license_path'] : $path;
?>
<div class="row">
	<div class="col-xs-12">
		<div class="row">
			<div class="col-xs-12">
				<div class="row" style="display: flex; flex-wrap: wrap; ">
					<div class="col-xs-12 col-md-4 widget-tab-box4">
						<div class="panel">
							<div class="panel-body p15 pt0">
								<div class="row">
									<div class="col-xs-12 bg-blue">
										<h3 class="mt10 mb0">Owner</h3>
										<div class="row">
											<div class="col-xs-12 text-center p5">
												<a href="<?php echo $ownerPic; ?>" target="_blank"><div class="image-box text-center"><img src="<?php echo $ownerPic; ?>" alt=""></div></a>
											</div> 

										</div>
									</div>

								</div>
							</div>
						</div>
					</div>
					<div class="col-xs-12 col-md-4 widget-tab-box4">
						<div class="panel">
							<div class="panel-body p15 pt0">
								<div class="row">
									<div class="col-xs-12 bg-blue">
										<h3 class="mt10 mb0">PAN</h3>
										<div class="row">
											<div class="col-xs-12 text-center p5">
												<a href="<?php echo $panPic; ?>" target="_blank"><div class="image-box text-center"><img src="<?php echo $panPic; ?>" alt=""></div></a>
											</div> 

										</div>
									</div>

								</div>
							</div>
						</div>
					</div>
					<div class="col-xs-12 col-md-4 widget-tab-box4">
						<div class="panel">
							<div class="panel-body p15 pt0">
								<div class="row">
									<div class="col-xs-12 bg-blue">
										<h3 class="mt10 mb0">Aadhar</h3>
										<div class="row">
											<div class="col-xs-12 text-center p5">
												<a href="<?php echo $aadharPic; ?>" target="_blank"><div class="image-box text-center"><img src="<?php echo $aadharPic; ?>" alt=""></div></a>
											</div> 

										</div>
									</div>

								</div>
							</div>
						</div>
					</div>
					<div class="col-xs-12 col-md-4 widget-tab-box4">
						<div class="panel">
							<div class="panel-body p15 pt0">
								<div class="row">
									<div class="col-xs-12 bg-blue">
										<h3 class="mt10 mb0">Voter</h3>
										<div class="row">
											<div class="col-xs-12 text-center p5">
												<a href="<?php echo $voterPic; ?>" target="_blank"><div class="image-box text-center"><img src="<?php echo $voterPic; ?>" alt=""></div></a>
											</div> 

										</div>
									</div>

								</div>
							</div>
						</div>
					</div>
					<div class="col-xs-12 col-md-4 widget-tab-box4">
						<div class="panel">
							<div class="panel-body p15 pt0">
								<div class="row">
									<div class="col-xs-12 bg-blue">
										<h3 class="mt10 mb0">Address Proof</h3>
										<div class="row">
											<div class="col-xs-12 text-center p5">
												<a href="<?php echo $addressProofPic; ?>" target="_blank"><div class="image-box text-center"><img src="<?php echo $addressProofPic; ?>" alt=""></div></a>
											</div> 

										</div>
									</div>

								</div>
							</div>
						</div>
					</div>
					<div class="col-xs-12 col-md-4 widget-tab-box4">
						<div class="panel">
							<div class="panel-body p15 pt0">
								<div class="row">
									<div class="col-xs-12 bg-blue">
										<h3 class="mt10 mb0">License</h3>
										<div class="row">
											<div class="col-xs-12 text-center p5">
												<a href="<?php echo $licensePic; ?>" target="_blank"><div class="image-box text-center"><img src="<?php echo $licensePic; ?>" alt=""></div></a>
											</div> 

										</div>
									</div>

								</div>
							</div>
						</div>
					</div>



				</div>
			</div>
		</div>
	</div>
</div>