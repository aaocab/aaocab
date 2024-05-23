<style type="text/css">
	.modal {  overflow-y:auto;}
    .flex {
		display: -webkit-box;
		display: -webkit-flex;
		display: -ms-flexbox;
		display: flex;
		flex-wrap: wrap;
	}
    .rounded-margin{ margin: 0 15px;}
    @media (min-width: 992px){
        .modal-lg {
            width: 95%!important;
        }
    }
    @media (min-width: 768px){
        .modal-lg {
            width: 100%;
        }
    }
    .control-label{
        font-weight: bold
    }   
    .box-design1{ background: #8DCF8A; color: #000; padding: 10px;}
    .box-design1a{ background: #ccffcc; color: #000;}
    .box-design2{ background: #F8A6AC; color: #000;  padding: 10px;}
    .box-design2a{ background: #ffcccc; color: #000; }
    .label-tab label{ margin:0 17%!important}
    .label-tab .form-group{ margin-bottom: 0;}
</style>
<?php $type = Yii::app()->getRequest()->getParam('type'); 
$tagBtnList = '';
if ($model->ctt_tags != '')
{
	$tagList = Tags::getListByids($model->ctt_tags);
	foreach ($tagList as $tag)
	{
		if($tag['tag_color']!='')
		{
			$tagBtnList .= " <span title='" . $tag['tag_desc'] . "' class='badge badge-pill badge-primary m5 mr0 p5 pb10 pl10 pr10' style='background:".$tag['tag_color']."'>" . $tag['tag_name'] . "</span>";
		}
		else
		{
			$tagBtnList .= " <span title='" . $tag['tag_desc'] . "' class='badge badge-pill badge-primary m5 mr0 p5 pb10 pl10 pr10' >" . $tag['tag_name'] . "</span>";
		}
	}
}
?>
<?php
if ($type == null)
{
	?>
	<div class="row">
		<div class="col-xs-12 mb20">
			<div style="text-align:center" class="below-buttons">
				<div class="btn-group1">
					<a class="btn btn-success btn-sm mb5 mr5" data-toggle="ajaxModal" data-type="Phone"  onclick="return popup(this)" href="<?= Yii::app()->createUrl("admpnl/contact/alternatephone", array("ctt_id" => $model->ctt_id)) ?>"  title="Add Phone" style="display:none;">Add Phone</a>
					<a class="btn btn-primary btn-sm mb5 mr5" data-toggle="ajaxModal" data-type="Email" onclick="return popup(this)"  href="<?= Yii::app()->createUrl("admpnl/contact/alternateemail", array("ctt_id" => $model->ctt_id)) ?>"  title="Add Email" style="display:none;">Add Email</a>
					<?php
					if (Yii::app()->request->getParam('viewType') == "driver")
					{
						?>
						<a class="btn btn-warning btn-sm mb5 mr5"   target="_blank"   href="<?= Yii::app()->createUrl("admpnl/document/view", array("ctt_id" => $model->ctt_id, 'viewType' => Yii::app()->request->getParam('viewType'))) ?>"  title="Upload Document" style="">Upload Document</a>
						<a class="btn btn-danger btn-sm mb5 mr5"   target="_blank"   href="<?= Yii::app()->createUrl("admpnl/contact/form", array("ctt_id" => $model->ctt_id, 'type' => 1)) ?>"  title="Modify Contact" style="">Modify Contact</a>
						<a class="btn btn-primary btn-sm mb5 mr5"   target="_blank"   href="<?= Yii::app()->createUrl("admpnl/driver/docapprovallist", array("ctt_id" => $model->ctt_id, 'viewType' => Yii::app()->request->getParam('viewType'))) ?>"  title="Approve Document" style="">Approve Document</a>	
						<?php
					}
					else if (Yii::app()->request->getParam('viewType') == "vendor")
					{
						?>
						<a class="btn btn-warning btn-sm mb5 mr5"   target="_blank"   href="<?= Yii::app()->createUrl("admpnl/document/view", array("ctt_id" => $model->ctt_id, 'viewType' => Yii::app()->request->getParam('viewType'))) ?>"  title="Upload Document" style="">Upload Document</a>
						<a class="btn btn-danger btn-sm mb5 mr5"   target="_blank"   href="<?= Yii::app()->createUrl("admpnl/contact/form", array("ctt_id" => $model->ctt_id, 'type' => 3)) ?>"  title="Modify Contact" style="">Modify Contact</a>
						<a class="btn btn-primary btn-sm mb5 mr5"   target="_blank"   href="<?= Yii::app()->createUrl("admpnl/document/docapprovallist", array("ctt_id" => $model->ctt_id, 'viewType' => Yii::app()->request->getParam('viewType'))) ?>"  title="Approve Document" style="">Approve Document</a>
					<?php }
					?>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
<div class="row text-right">
<a class="btn btn-danger btn-sm mb5 mr5"   target="_blank"   href="<?= Yii::app()->createUrl("admpnl/contact/form", array("ctt_id" => $model->ctt_id, 'type' => 1)) ?>"  title="Modify Contact" style="">Modify Contact</a>
</div>
<div id="view">
    <div class="row">
        <div class="col-xs-12 col-md-12 col-lg-6 new-booking-list">
            <div class="row p20">
                <div class="col-xs-12 heading_box">Contact Information</div>
				<div class="col-xs-12 main-tab1">
					<div class="row new-tab-border-b">
						<div class="row new-tab-border-b">
							<div class="col-xs-12 col-sm-6 new-tab-border-r">
								<div class="row new-tab1">
									<div class="col-xs-5"><b><?= trim($model->userType[$model->ctt_user_type]); ?>:</b></div>
									<div class="col-xs-7"><?php
										if ($model->ctt_user_type == 1)
										{
											?>
											<?= $model->ctt_first_name . ' ' . $model->ctt_last_name; ?>
											<?php
										}
										else
										{
											?>
											<?= $model->ctt_business_name . ' ' . $model->userType[$model->ctt_user_type]; ?>
										<?php } ?>
									</div>
								</div>
							</div>
							<div class="col-xs-12 col-sm-6">
								<div class="row new-tab1">		
									<div class="col-xs-5"><br /><b>Contact Status:</b><br /></div>
									<div class="col-xs-7">
									<br />
									<?php
										if($isMerged)
										{
									?>		<span id="pan"><a class="btn btn-success btn-xs mb5 mr5"   target="_blank"   href=" <?= Yii::app()->createUrl("admpnl/contact/mergedetails", array("ctt_id" => $model->ctt_id))?>"  title="" style="">Merged(Check Details) </a></span>
									<?php
										}
										else
										{
									?>		<span id="pan" class="label label-success">Not Merged</span>
									<?php } ?>	
									</div>
								</div>
							</div>
						</div>
						<div class="row new-tab-border-b">
							<div class="col-xs-12 col-sm-6 new-tab-border-r">
								<div class="row new-tab1">
									<div class="col-xs-5"><b>Email:</b></div>
									<div class="col-xs-7"> 
										<?php
										if ($emailModel[0]->eml_is_verified == 1)
										{
											echo trim($emailModel[0]->eml_email_address) . '<img src="/images/icon/reconfirmed.png" style="cursor:pointer" title="Contact Verified" width="15">';
										}
										else
										{
											echo trim($emailModel[0]->eml_email_address) . '<img src="/images/icon/unblock.png" style="cursor:pointer" title="Contact UnVerified" width="15">';
										}
										if ($emailModel[0]->eml_is_primary == 1)
										{
											echo '&nbsp;<span id="pan" class="label label-success">Primary</span>';
										}
										?>
									</div>
								</div>
							</div>
							<div class="col-xs-12 col-sm-6">
								<div class="row new-tab1">
									<div class="col-xs-5"><b>Alternate Email:</b></div>
									<div class="col-xs-7" style="display: inline-block" >
										<?php
										if (count($emailModel) >= 2)
										{
											$allEmail = "";
											for ($i = 1; $i < count($emailModel); $i++)
											{
												$allEmailVerified = $emailModel[$i]->eml_is_verified == 1 ? '<img src="/images/icon/reconfirmed.png" style="cursor:pointer" title="Contact Verified" width="15">' : '<img src="/images/icon/unblock.png" style="cursor:pointer" title="Contact UnVerified" width="15">';
												if ($i == 1)
												{
													$allEmail = trim($emailModel[$i]->eml_email_address) . $allEmailVerified;
												}
												else
												{
													$allEmail .= "<br>" . trim($emailModel[$i]->eml_email_address) . $allEmailVerified;
												}
											}
											echo $allEmail;
										}
										?>
									</div>
								</div>
							</div>
						</div>
						<div class="row new-tab-border-b">
							<div class="col-xs-12 col-sm-6 new-tab-border-r">
								<div class="row new-tab1">
									<div class="col-xs-5"><b>Phone:</b></div>
									<div class="col-xs-7"> <?php
										if ($phoneModel[0]->phn_is_verified == 1)
										{
											echo trim($phoneModel[0]->phn_phone_no) . '<img src="/images/icon/reconfirmed.png" style="cursor:pointer" title="Contact Verified" width="15">';
										}
										else
										{
											echo trim($phoneModel[0]->phn_phone_no) . '<img src="/images/icon/unblock.png" style="cursor:pointer" title="Contact UnVerified" width="15">';
										}

										if ($phoneModel[0]->phn_is_primary == 1)
										{
											echo '&nbsp;<span id="pan" class="label label-success">Primary</span>';
										}
										?></div>
								</div>
							</div>
							<div class="col-xs-12 col-sm-6">
								<div class="row new-tab1">
									<div class="col-xs-5"><b>Alternate Phone:</b></div>
									<div class="col-xs-7">
										<?php
										if (count($phoneModel) >= 2)
										{
											$allPhone = "";
											for ($i = 1; $i < count($phoneModel); $i++)
											{
												$allPhoneVerified = $phoneModel[$i]->phn_is_verified == 1 ? '<img src="/images/icon/reconfirmed.png" style="cursor:pointer" title="Contact Verified" width="15">' : '<img src="/images/icon/unblock.png" style="cursor:pointer" title="Contact UnVerified" width="15">';
												if ($i == 1)
												{
													$allPhone = trim($phoneModel[$i]->phn_phone_no) . $allPhoneVerified;
												}
												else
												{
													$allPhone .= "<br>" . trim($phoneModel[$i]->phn_phone_no) . $allPhoneVerified;
												}
											}
											echo $allPhone;
										}
										?>
									</div>
								</div>
							</div>
						</div>		
						<div class="row new-tab-border-b">
							<div class="col-xs-12 col-sm-12 new-tab-border-r">
								<div class="row new-tab1">
									<div class="col-xs-5"><b>Address:</b></div>
									<div class="col-xs-7"><?= $model->ctt_address; ?></div>
								</div>
							</div>                       
						</div>
						<div class="row new-tab-border-b">
							<div class="col-xs-12 col-sm-6 new-tab-border-r">
								<div class="row new-tab1">
									<div class="col-xs-5"><b>State:</b></div>
									<div class="col-xs-7"><?php
										$stateDetails	 = States::model()->findByPk($model->ctt_state);
										echo $stateDetails->stt_name;
										?></div>
								</div>
							</div>
							<div class="col-xs-12 col-sm-6">
								<div class="row new-tab1">
									<div class="col-xs-5"><b>City:</b></div>
									<div class="col-xs-7">
										<?php
										$cityDetails	 = Cities::model()->findByPk($model->ctt_city);
										echo $cityDetails->cty_name;
										?></div>
								</div>
							</div>						
						</div>
						<div class="row new-tab-border-b">
							<div class="col-xs-12 col-sm-6 new-tab-border-r">
								<div class="row new-tab1">
									<div class="col-xs-5"><b>Create Date:</b></div>
									<div class="col-xs-7"><?= date('d/m/Y', strtotime($model->ctt_created_date)); ?></div>
								</div>
							</div>
							<div class="col-xs-12 col-sm-6">
								<div class="row new-tab1">
									<div class="col-xs-5"><b>Status:</b></div>
									<div class="col-xs-7">
										<?php
										$label			 = "";
										$labelTitle		 = "";
										if ($model->activeType[$model->ctt_active] == "deleted")
										{
											$label		 = "label-danger";
											$labelTitle	 = "Deleted";
										}
										else if ($model->activeType[$model->ctt_active] == "active")
										{
											$label		 = "label-success";
											$labelTitle	 = "Active";
										}
										else if ($model->activeType[$model->ctt_active] == "deactive")
										{
											$label		 = "label-info";
											$labelTitle	 = "InActive";
										}
										else if ($model->activeType[$model->ctt_active] == "pending approval")
										{
											$label		 = "label-default";
											$labelTitle	 = "Pending Approval";
										}
										else
										{
											$label		 = "label-warning";
											$labelTitle	 = "Ready For Approval";
										}
										?><span id="pan" class="label <?= $label; ?>"><?= $labelTitle; ?></span></div>
								</div>
							</div>
						</div>
						<div class="row new-tab-border-b">
							<div class="col-xs-12 col-sm-6 new-tab-border-r">
								<div class="row new-tab1">
									<div class="col-xs-5"><b>Tags:</b></div>
									<div class="col-xs-7"><?=$tagBtnList;?></div>
								</div>
							</div>
							
						</div>
						<?php
						if (count($UserIdArr) > 0)
						{
							?>
							<div class="row new-tab-border-b">
								<div class="col-xs-12 col-sm-12 new-tab-border-r">
									<div class="row new-tab1">
										<div class="col-xs-3"><b>Social Information:</b></div>
										<div class="col-xs-9"><?php
											$role		 = "";
											$socialemail = "";
											$flagdrv	 = 0;
											$flagvnd	 = 0;
											for ($i = 0; $i < count($UserIdArr); $i++)
											{
												$dataprofiledata = explode('"email";', $UserIdArr[$i]['profile_cache']);
												$dataprofiledata = explode(';', $dataprofiledata[1]);
												$dataprofiledata = explode(':"', $dataprofiledata[0]);
												$socialIcons	 = $UserIdArr[$i]['provider'] == "Google" ? '<i style="color:green" class="fa fa-google"></i>' : '<i  style="color:blue" class="fa fa-facebook-f"></i>';
												$socialemail .= "<b>Linked $socialIcons - " . trim($dataprofiledata[1], '"') . "</b><br>";
												if ($UserIdArr[$i]['UserType'] == "Driver" && $flagdrv == 0)
												{
													$flagdrv = 1;
													$role	 .= "<b> Driver account:</b> " . trim($UserIdArr[$i]['drv_code']) . "</br>";
												}
												else if ($UserIdArr[$i]['UserType'] == "Vendor" && $flagvnd == 0)
												{
													$flagvnd = 1;
													$role	 .= "<b> Vendor account:</b> " . trim($UserIdArr[$i]['vnd_code']) . "</br>";
												}
											}
											echo $socialemail;
											?>
										</div>
									</div>
								</div>                       
							</div>
						<?php } ?>
						<?php
						if (count($UserIdArr) > 0)
						{
							?>
							<div class="row new-tab-border-b">
								<div class="col-xs-12 col-sm-12 new-tab-border-r">
									<div class="row new-tab1">
										<div class="col-xs-3"><b>Role Information:</b></div>
										<div class="col-xs-9"><?= $role; ?></div>
									</div>
								</div>                       
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
        </div>
		<div class="col-xs-12 col-md-12 col-lg-6 new-booking-list">
            <div class="row p20">
                <div class="col-xs-12 heading_box">Bank/Document Information</div>
				<?php
				$this->renderPartial('bankdocumentdetail', ['model' => $model]);
				?>
            </div>
        </div>
	</div>
	<div class="row">
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="col-xs-12">
					<div class="row">
						<div class="col-xs-12 text-center h3 mt0">Documents</div>
					</div>
					<div class="row bordered mt10">
						<?php
						$docType = Document::model()->docType();
						if (Yii::app()->request->getParam('viewType') == "driver")
						{
							unset($docType[6]);
						}
						else
						{
							unset($docType[7]);
						}
						?>	
						<div class="col-xs-12">
							<div class="panel panel-default panel-border">
								<div class="panel-body">
									<?php
									foreach ($docType as $key => $data)
									{
										foreach ($docpath as $value)
										{
											?> 
											<div class="row">
												<div class="col-xs-12 col-sm-5">
													<div class="col-xs-12 col-sm-5">
														<label><b><?php echo $docType[$key][1]; ?></b></label>:
													</div>
													<div class="col-xs-12 col-sm-5">
														<?php
														if ($value["doc_file_front_path" . $key] == "")
														{
															echo "Missing";
														}
														else
														{
															$Url = "";
                                                                                                                        $Url = Document::getDocPathById($value['doc_id' . $key], 1);
															echo '<a href="' . $Url . '" target="_blank">Attachment Link</a>';
														}
														?>
													</div>
												</div>
												<?php
												if ($docType[$key][2] != '')
												{
													?>
													<div class="col-xs-12 col-sm-5">
														<div class="col-xs-12 col-sm-5">
															<label><b><?php echo $docType[$key][2]; ?></b></label>:
														</div>
														<div class="col-xs-12 col-sm-5">
															<?php
															if ($value["doc_file_back_path" . $key] == "")
															{
																echo "Missing";
															}
															else
															{
																$Url = "";
                                                                                                                                $Url = Document::getDocPathById($value['doc_id'. $key], 2);
																echo '<a href="' . $Url . '" target="_blank">Attachment Link</a>';
															}
															?>
														</div>
													</div>
												<?php } ?>
												<?php
												if ($value["doc_file_front_path" . $key] != '' || $value['doc_file_back_path' . $key] != '')
												{
													?>
													<div class="col-xs-12 col-sm-2">
														<?php
														if ($value['doc_status' . $key] == 0)
														{
															?>
															<span id = "docnotapprove" class="label label-default"> Not Approved</span>
															<?php
														}
														elseif ($value['doc_status' . $key] == '1')
														{
															?>
															<div  id="doc<?php echo $key ?>" >
																<span  class="label label-success"> Approved</span>
																<?php
																if ($key == 2)
																{
																	echo '<a  href="javascript:void(0)" title="Reject Document"><img  src="/images/deleteImg.png" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$model->ctt_id','" . $value['doc_id' . $key] . "','" . $model->ctt_voter_no . "','" . $key . "','0'" . ')"></a>';
																}
																else if ($key == 3)
																{
																	echo '<a href="javascript:void(0)" title="Reject Document"><img  src="/images/deleteImg.png" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$model->ctt_id','" . $value['doc_id' . $key] . "','" . $model->ctt_aadhaar_no . "','" . $key . "','0'" . ')"></a>';
																}
																else if ($key == 4)
																{
																	echo '<a href="javascript:void(0)" title="Reject Document"><img  src="/images/deleteImg.png" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$model->ctt_id','" . $value['doc_id' . $key] . "','" . $model->ctt_pan_no . "','" . $key . "','0'" . ')"></a>';
																}
																else if ($key == 5)
																{
																	echo '<a href="javascript:void(0)" title="Reject Document"><img  src="/images/deleteImg.png" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$model->ctt_id','" . $value['doc_id' . $key] . "','" . $model->ctt_license_no . "','" . $key . "','0'" . ')"></a>';
																}
																else if ($key == 6)
																{
																	echo '<a href="javascript:void(0)" title="Reject Document"><img  src="/images/deleteImg.png" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$model->ctt_id','" . $value['doc_id' . $key] . "','','" . $key . "','0'" . ')"></a>';
																}
																else if ($key == 7)
																{
																	echo '<a href="javascript:void(0)" title="Reject Document"><img  src="/images/deleteImg.png" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$model->ctt_id','" . $value['doc_id' . $key] . "','','" . $key . "','0'" . ')"></a>';
																}
																?>

															</div>							
															<?php
														}
														elseif ($value['doc_status' . $key] == 2)
														{
															?>
															<span class="label label-danger"> Rejected</span>
															<br><span><i><?= $value['doc_remarks' . $key]; ?></i></span>
														<?php } ?>
													</div>
												<?php } ?>
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
			</div>    
		</div>
	</div>
</div>

<?php
$version = Yii::app()->params['customJsVersion'];
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/custom.js?v=' . $version, CClientScript::POS_HEAD);
?>
<script type="text/javascript">
    $(document).ready(function () {});
    function popup(obj) {
        $('.bootbox').modal('hide');
        var type = $(obj).attr("data-type")
        var href2 = $(obj).attr("href");
        $.ajax({
            "url": href2,
            "type": "GET",
            "dataType": "html",
            "success": function (data) {
                var box = bootbox.dialog({
                    message: data,
                    size: 'medium',
                    title: 'Alternate ' + type,
                    onEscape: function () {
                        popupView('<?= Yii::app()->createUrl("admin/contact/view", array('ctt_id' => $model->ctt_id, 'viewType' => Yii::app()->request->getParam('viewType'))) ?>');
                    }});
                if ($('body').hasClass("modal-open")) {
                    box.on('hidden.bs.modal', function (e) {
                        $('body').addClass('modal-open');
                    });
                }
            }
        });
        return false;
    }
    function popupView(obj) {
        $('.bootbox').modal('hide');
        $("#resultLoading").hide();
        $.ajax({
            "url": obj,
            "type": "GET",
            "dataType": "html",
            "success": function (data) {
                var box = bootbox.dialog({
                    message: data,
                    size: 'large',
                    title: ' Contact Details',
                    onEscape: function () {// user pressed escape}});
                        if ($('body').hasClass("modal-open")) {
                            box.on('hidden.bs.modal', function (e) {
                                $('body').addClass('modal-open');
                            });
                        }
                    }
                });
                return false;
            }
        });
        return false;
    }
    function openpic(pid, docid, dnam, doctype, sidetype) {
        $.ajax({
            "type": "GET",
            "dataType": "html",
            "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/document/showdoc')) ?>",
            "data": {"ctt_id": pid, "docid": docid, 'doctype': doctype, 'sidetype': sidetype},
            "success": function (data) {
                box = bootbox.dialog({
                    message: data,
                    className: "bootbox-xs",
                    title: "<span class='text-center'>" + doctype + " - " + dnam + "</span>",
                    size: "large",
                    onEscape: function () {
                        box.modal('hide');
                    }
                });
            }
        });
    }
</script>