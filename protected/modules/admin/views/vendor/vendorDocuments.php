<?php
$docType = Document::model()->documentType();
unset($docType[7], $docType[1]);
?>
<div class="row">
	<div class="col-xs-12">
		<div class="row">
			<div class="col-xs-12">
				<div class="row" style="display: flex; flex-wrap: wrap; ">
					<?php
					foreach ($docType as $key => $data)
					{
						foreach ($docpath as $value)
						{
							if ($value['ctt_id'] == $value['ctt_ref_code'] && $value['doc_id' . $key] != '')
							{

								$docId			 = $value['doc_id' . $key];
								$docStatus		 = $value["doc_status" . $key];
								$docApproveBy	 = $value["doc_approved_by" . $key];
								$docCreatedAt	 = $value["doc_created_at" . $key];
								break;
							}
							else if ($value['doc_id' . $key] != '')
							{
								$docId			 = $value['doc_id' . $key];
								$docStatus		 = $value["doc_status" . $key];
								$docApproveBy	 = $value["doc_approved_by" . $key];
								$docCreatedAt	 = $value["doc_created_at" . $key];
								break;
							}
							else
							{
								$docId			 = $value['doc_id' . $key];
								$docStatus		 = $value["doc_status" . $key];
								$docApproveBy	 = $value["doc_approved_by" . $key];
								$docCreatedAt	 = $value["doc_created_at" . $key];
							}
						}
						$pathfront		 = Document::getDocPathById($docId, 1);
						?>
						<div class="col-xs-12 col-md-4 widget-tab-box4">
							<div class="panel">
								<div class="panel-body p15 pt0">
									<div class="row">
										<div class="col-xs-12 bg-blue">
											<h3 class="mt10 mb0"><?php echo $data; ?>
												<!--<a href="#" class="pull-right btn-6">Approved</a>-->
												<?php echo ($docStatus == 1) ? '<span class="pull-right label label-success">Approved</span>' : '<span class="pull-right label label-info">Not Approved</span>'; ?>
											</h3>
											<!--<p class="font-11 mb5"><span class="color-gray">Uploaded on</span> <b>12 mar 2021</b></p>-->                                                                                                                                                                
											<div class="row">
												<div class="col-xs-6 text-center p5">
												<!--                                                                                            <a target="_blank"  href="/attachments/vehicles/63231/63231-frontLicensePlate-20200314081154.IMG-20200314-WA0010.jpg"><div class="image-box text-center"><img src="/attachments/vehicles/63231/63231-frontLicensePlate-20200314081154.IMG-20200314-WA0010.jpg" alt=""></div></a>-->
													<?php
													echo $docFrontLink	 = ($pathfront != '') ? '<a href="' . $pathfront . '" target="_blank"><div class="image-box text-center"><img src="' . $pathfront . '" alt=""></div></a>' : 'Missing';
													?>

													<span class="font-10"><a target="_blank" href="<?= $pathfront ?>">Front Number Plate Link</a></span>
												</div> 
												<?php
												if ($key != 6)
												{
//                                                                                                    
													$pathback		 = Document::getDocPathById($docId, 2);
													?>
													<div class="col-xs-6 text-center p5">
														<?php
														echo $docFrontLink	 = ($pathback != '') ? '<a href="' . $pathback . '" target="_blank"><div class="image-box text-center"><img src="' . $pathback . '" alt=""></div></a>' : 'Missing';
														?>
														<span class="font-10"><a target="_blank" href="<?= $pathback ?>">Back Number Plate Link</a></span>
														<!--                                                                                            <a target="_blank"  href="/attachments/vehicles/63231/63231-rearLicensePlate-20200314081211.IMG-20200314-WA0009.jpg"><div class="image-box text-center"><img src="/attachments/vehicles/63231/63231-rearLicensePlate-20200314081211.IMG-20200314-WA0009.jpg" alt=""></div></a>-->
														<!--                                                                                            <span class="font-10"><a target="_blank" href="/attachments/vehicles/63231/63231-rearLicensePlate-20200314081211.IMG-20200314-WA0009.jpg">Back number plate Link</a></span>-->
													</div>
												<?php } ?>
											</div>
										</div>
										<div class="col-xs-12 pt10">
											<?php
											if ($docApproveBy != '')
											{
												?>
												<p><span class="color-gray">Approved by</span>
													<br>
													<?php
													
														$userName = Admins::findById($docApproveBy);
														
													?>
														<b><?php print_r($userName['adm_fname'] .' '.$userName['adm_lname']); ?></b>
													
													
												</p>
											<?php } ?>
											<p><span class="color-gray">Uploaded at</span>
												<br>
												<b><?php echo $docCreatedAt ?></b>
											</p>
										</div>
									</div>
								</div>
							</div>
						</div>
						<?php
					}
					if($agreement['vag_digital_agreement']!=null || $agreement['vag_digital_agreement_s3_data']!=null)
					{
					$Url = VendorAgreement::getPathById($agreement['vag_id'], VendorAgreement::DIGITAL_AGREEMENT);
					?>
					<div class="col-xs-12 col-md-4 widget-tab-box4">
						<div class="panel">
							<div class="panel-body p15 pt0">
								<div class="row">
									<div class="col-xs-12 bg-blue">
										<h3 class="mt10 mb0">Agreement									<!--<a href="#" class="pull-right btn-6">Approved</a>-->
											<span class="pull-right label label-info"><?=$agreement['vag_approved']!=1?("Not approved"):("Approved")?></span>											</h3>
										<!--<p class="font-11 mb5"><span class="color-gray">Uploaded on</span> <b>12 mar 2021</b></p>-->                                                                                                                                                                
										<div class="row">
											<div class="col-xs-6 text-center p5">
											<!--                                                                                            <a target="_blank"  href="/attachments/vehicles/63231/63231-frontLicensePlate-20200314081154.IMG-20200314-WA0010.jpg"><div class="image-box text-center"><img src="/attachments/vehicles/63231/63231-frontLicensePlate-20200314081154.IMG-20200314-WA0010.jpg" alt=""></div></a>-->
												<a href="<?=$Url?>" target="_blank"><div class="image-box text-center"><img src="/images/pdf.jpg" height="100%"></div></a>
											
											</div> 

										</div>
									</div>
									<div class="col-xs-12 pt10">
										<p><span class="color-gray">Uploaded at</span>
											<br>
											<b><?=$agreement['vag_created_at']?></b>
										</p>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php
					}
					?>
<!--				<div class="row">
					<div class="col-xs-12">
						<a class="btn btn-success font11x" href="<?php #echo Yii::app()->createUrl("admin/vendor/agreementShowdoc", array('ctt_id' => $contactId, 'vnd_id' => $vndId)) ?>" target="_blank">Approve Agreement</a>
					</div>
				</div>-->
			</div>
		</div>
	</div>
</div>