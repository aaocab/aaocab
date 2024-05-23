<style>
    .profile-usertitle-name {
        color: #5a7391;
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 7px;
    }
    .profile-usertitle-job {
        text-transform: uppercase;
        color: #5b9bd1;
        font-size: 13px;
        font-weight: 800;
        margin-bottom: 7px;
    }

    .profile-usertitle {
        text-align: center;
        margin-top: 20px;
    }
    .new-booking-list .form-horizontal .form-group{ margin-left: 0; margin-right: 0;}
</style>
<div class="container">
	<div class="page-content-inner new-booking-list">
		<div class="row ">
			<div class="col-md-12">
				<div class="row">
					<div class="profile-sidebar col-xs-12 col-sm-3">
						<div class="portlet light profile-sidebar-portlet text-center">
							<div class="profile-userpic pl20" >
								<?
								$pathImage = "/images/noimg.gif";
								if ($model->usr_profile_pic_path != '')
								{
									$pathImage = $model->usr_profile_pic_path;
								}
								?>
								<img src="<?= $pathImage ?>" class="img-responsive" alt="" style="height: 160px;width: 150px; display: inline-block"> 
							</div>
							<div class="profile-usertitle p20 text-center">
								<div class="profile-usertitle-name"><?= ucwords($model->usr_name . " " . $model->usr_lname); ?></div>
								<div class="profile-usertitle-job uppercase"><?= $modelAgent->approveArr($modelAgent->agt_approved) ?></div>
							</div>

							<!--                                <div class="portlet-title">
																<ul class="nav nav-tabs">
																	<li>
																		<a href="#tab_1_1" data-toggle="tab" onclick="$('#profile_edit_div').removeClass('hide');
																				$('#markup_settings_div').addClass('hide');"><span style="color: #5b9bd1"><i class="icon-settings"></i>Account Settings</span></a>
																	</li>
																</ul>
															</div>-->
						</div>
						<div class="portlet light ">
							<div class="profile-userbuttons">
								<!--                                    <a href="javascript:;" class="icon-btn">
																		<i class="fa fa-bullhorn"></i>
																		<div> Notifications </div>
																		<span class="badge badge-danger"> 3 </span>
																	</a>-->
								<a href="<?= Yii::app()->createUrl('agent/booking/list') ?>" class="icon-btn">
									<i class="fa fa-car"></i>
									<div> Bookings </div>
									<span class="badge badge-info"><?= $totBookings['totOntheWay'] + $totBookings['totAssinged'] + $totBookings['totCompleted'] + $totBookings['totNew'] ?></span>
								</a>
							</div>
						</div>
					</div>

					<div class="profile-content col-xs-12 col-md-9" id="profile_edit_div">
						<div class="row">
							<div class="col-md-12">
								<div class="portlet light ">
									<div class="portlet-title tabbable-line">
										<div class="caption caption-md">
											<i class="icon-globe theme-font hide"></i>
											<span class="caption-subject font-blue-madison bold uppercase">Profile Account</span>
										</div>
										<ul class="nav nav-tabs">
											<li class="active">
												<a href="#tab_1_1" data-toggle="tab">Personal Info</a>
											</li>
											<li>
												<a href="#tab_1_2" data-toggle="tab">Change Photo</a>
											</li>
										</ul>
									</div>
									<div class="portlet-body">
										<?php
										$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
											'id'					 => 'form_tab_1', 'enableClientValidation' => FALSE,
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
										$response	 = Contact::userMappedToItems($model->user_id, 3);
										if ($response->getStatus())
										{
											$contactNo	 = $response->getData()->phone['number'];
											$countryCode = $response->getData()->phone['ext'];
											$firstName	 = $response->getData()->email['firstName'];
											$lastName	 = $response->getData()->email['lastName'];
											$email		 = $response->getData()->email['email'];
										}
										/* @var $form TbActiveForm */
										?>
										<div class="tab-content">
											<div class="tab-pane active p15" id="tab_1_1">
												<div class="row">
													<div class="col-xs-12 col-md-6">
														<div class="form-group">
															<label class="control-label">First Name</label>
															<input type="text" placeholder="John" class="form-control" name="Users[usr_name]" value="<?= $firstName ?>"> </div>
													</div> 
													<div class="col-xs-12 col-md-6">
														<div class="form-group">
															<label class="control-label">Last Name</label>
															<input type="text" placeholder="Doe" class="form-control" name="Users[usr_lname]" value="<?= $lastName ?>"> </div>
													</div>
												</div>
												<div class="row">
													<div class="col-xs-12 col-md-6">
														<div class="form-group">
															<label class="control-label">Mobile Number</label>
															<input type="text" placeholder="+1 646 580 DEMO (6284)" class="form-control" name="Users[usr_mobile]" value="<?= $contactNo ?>"> </div>
													</div>
													<div class="col-xs-12 col-md-6">
														<div class="form-group">
															<label class="control-label">Email</label>
															<input type="text" placeholder="demo@gmail.com" class="form-control" id="usr_email" name="Users[usr_email]" readonly="true" value="<?= $email ?>"> </div>
													</div>
												</div> 
												<div class="row">
													<div class="col-xs-12 col-md-6">
														<div class="form-group">
															<label class="control-label">Address Line1</label>
															<textarea  placeholder="ABC -123" class="form-control" name="Users[usr_address1]" value="<?= $model->usr_address1 ?>"><?= $model->usr_address1 ?></textarea> </div>
													</div>
													<div class="col-xs-12 col-md-6">
														<div class="form-group">
															<label class="control-label">Address Line2</label>
															<textarea  placeholder="ABC -BLOCK -G" class="form-control" name="Users[usr_address2]" value="<?= $model->usr_address2 ?>"><?= $model->usr_address2 ?></textarea> </div>
													</div>
												</div> 
												<div class="row">
													<div class="col-xs-12 col-md-6">
														<div class="form-group">
															<label class="control-label">Nearby Landmark</label>
															<input type="text" placeholder="Tower -XYZ" class="form-control" name="Users[usr_address3]" value="<?= $model->usr_address3 ?>"> </div>
													</div> 
													<div class="col-xs-12 col-md-6">
														<div class="form-group">
															<label class="control-label">Zip Code</label>
															<input type="text" placeholder="111111" class="form-control" name="Users[usr_zip]" value="<?= $model->usr_zip ?>"> </div>
													</div>
												</div>
												<div class="margiv-top-10">
													<button type="submit" class="btn green" name="form_tab_1"> Save Changes </button>    
												</div>
											</div>

											<div class="tab-pane p15" id="tab_1_2">
												<div class="form-group">
													<div class="fileinput fileinput-new" data-provides="fileinput">
														<img src="<?= $model->usr_profile_pic_path ?>" alt="" id="img_preview" style="height: 150px;width: 200px"> 
														<div class="fileinput-preview fileinput-exists thumbnail mt10" style="max-width: 200px; max-height: 150px;"> </div>
														<div>
															<span class="btn default btn-file">
																<?= $form->fileFieldGroup($model, 'usr_profile_pic_path', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['onchange' => "readURL(this);", 'class' => 'btn default btn-file']))); ?>
															</span>
															<a href="javascript:;" class="btn btn-danger" data-dismiss="fileinput" onclick="document.getElementById('Users_usr_profile_pic_path').value = '';"> Remove </a>
														</div>
													</div>
													<div class="clearfix margin-top-10">
														<span class="label label-default">NOTE! </span>
														<span style="margin-left: 5px"> Attached image must be less than 2 MB</span>
													</div>
												</div>
												<div class="margin-top-10">
													<button type="submit" class="btn green" name="form_tab_2"> Submit </button>
													<button  class="btn default"> Cancel </button>
												</div>
											</div>

											<div class="tab-pane" id="tab_1_3">
												<form action="#">
													<div class="form-group">
														<label class="control-label">Current Password</label>
														<input type="password" class="form-control"> </div>
													<div class="form-group">
														<label class="control-label">New Password</label>
														<input type="password" class="form-control"> </div>
													<div class="form-group">
														<label class="control-label">Re-type New Password</label>
														<input type="password" class="form-control"> </div>
													<div class="margin-top-10">
														<a href="javascript:;" class="btn green"> Change Password </a>
														<a href="javascript:;" class="btn default"> Cancel </a>
													</div>
												</form>
											</div>
										</div>
										<?php $this->endWidget(); ?>
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
<script>
    function readURL(input) {
        if (input.files && input.files[0]) {

            var reader = new FileReader();
            reader.onload = function (e) {
                $('#img_preview').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>