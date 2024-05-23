<?php
$docType = Document::model()->documentType();
unset($docType[1], $docType[6]);
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
							$pathfront		 = Document::getDocPathById($value['doc_id' . $key], 1);
							?>
							<div class="col-xs-12 col-md-4 widget-tab-box4">
								<div class="panel">
									<div class="panel-body p15 pt0">
										<div class="row">
											<div class="col-xs-12 bg-blue">
												<h3 class="mt10 mb0"><?php echo $data; ?>
													<!--<a href="#" class="pull-right btn-6">Approved</a>-->
													<?php echo ($value["doc_status" . $key] == 1) ? '<span class="pull-right label label-success">Approved</span>' : '<span class="pull-right label label-info">Not Approved</span>'; ?>
												</h3>
												<!--<p class="font-11 mb5"><span class="color-gray">Uploaded on</span> <b>12 mar 2021</b></p>-->                                                                                                                                                                
												<div class="row">
													<div class="col-xs-6 text-center p5">
																	<!--                                                                                            <a target="_blank"  href="/attachments/vehicles/63231/63231-frontLicensePlate-20200314081154.IMG-20200314-WA0010.jpg"><div class="image-box text-center"><img src="/attachments/vehicles/63231/63231-frontLicensePlate-20200314081154.IMG-20200314-WA0010.jpg" alt=""></div></a>-->
														<?php
														echo $docFrontLink	 = ($pathfront != '') ? '<a href="' . $pathfront . '" target="_blank"><div class="image-box text-center"><img src="' . $pathfront . '" alt=""></div></a>' : '<div class="image-box text-center"><img src="' . $pathfront . '" alt=""></div>';
														?>

														<span class="font-10"><a target="_blank" href="<?= $pathfront ?>">Front Number Plate Link</a></span>
													</div> 
													<?php
													if ($key != 7)
													{
														$pathback		 = Document::getDocPathById($value['doc_id' . $key], 2);
														?>
														<div class="col-xs-6 text-center p5">
															<?php
															echo $docFrontLink	 = ($pathback != '') ? '<a href="' . $pathback . '" target="_blank"><div class="image-box text-center"><img src="' . $pathback . '" alt=""></div></a>' : '<div class="image-box text-center"><img src="' . $pathback . '" alt=""></div>';
															?>
															<span class="font-10"><a target="_blank" href="<?= $pathback ?>">Back Number Plate Link</a></span>
															<!--  <a target="_blank"  href="/attachments/vehicles/63231/63231-rearLicensePlate-20200314081211.IMG-20200314-WA0009.jpg"><div class="image-box text-center"><img src="/attachments/vehicles/63231/63231-rearLicensePlate-20200314081211.IMG-20200314-WA0009.jpg" alt=""></div></a>-->
															<!-- <span class="font-10"><a target="_blank" href="/attachments/vehicles/63231/63231-rearLicensePlate-20200314081211.IMG-20200314-WA0009.jpg">Back number plate Link</a></span>-->
														</div>
													<?php } ?>
												</div>
											</div>
											<div class="col-xs-12 pt10">
												<?php
												if ($value["doc_approved_by" . $key] != '')
												{
													?>
													<p><span class="color-gray">Approved by</span>
														<br>
														<?php
															$userName = Admins::findById($value["doc_approved_by" . $key]);
														
														?>
														<b><?php print_r($userName['adm_fname'] .' '.$userName['adm_lname']); ?></b>
													</p>
												<?php } ?>
												<p><span class="color-gray">Uploaded at</span>
													<br>
													<b><?php echo $value["doc_created_at" . $key] ?></b>
												</p>
											</div>
										</div>
									</div>
								</div>
							</div>
							<?php
						}
					}
					?>
				</div>
			</div>
		</div>
	</div>
</div>