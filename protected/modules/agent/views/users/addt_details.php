<?php
$version	 = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/city.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/lookup/cities?v' . Cities::model()->getLastModified());
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');
?>
<style>

    .panel_listcom{ float: left!important; width: 100%; margin-top: 20px;}
    .rcorners2{
        text-align: center;
        color: #fff;
        background: #36C6D3;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: inline-block;
        padding-top: 5px;
        font-weight: bold;
    }
    .panel_listcom li.t1 { width: 18%; text-align: center;}
    .panel_listcom li.t2 { width: 28%; text-align: center;}
    .panel-heading{
        border-bottom: 15px solid transparent !important; overflow: hidden;
    }
    a.disabled {
        pointer-events: none;
        cursor: default;
    }
    .panel_listcom .nav-tabs > li.active > a, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus{
        background: #ffffff!important;
    }
    .checkbox{
        margin-left: 35px
    }
</style>
<div class="container">
    <div class="portlet light">
        <div class="portlet-title">
            <div class="tabbable-line">
                <ul class="nav nav-tabs" style="background-color: #fff">
					<?
					$disable1	 = $disable2	 = $disable3	 = $disable4	 = $disable5	 = $disable6	 = 'disabled';
					$tablink1	 = $tablink2	 = $tablink3	 = $tablink4	 = $tablink5	 = $tablink6	 = 'javascript:void(0)';
					if ($tab == 6 || $model->agt_company != '')
					{
						$disable1	 = $disable2	 = $disable3	 = $disable4	 = $disable5	 = $disable6	 = '';
						$tablink1	 = '#tab1';
						$tablink2	 = '#tab2';
						$tablink3	 = '#tab3';
						$tablink4	 = '#tab4';
						$tablink5	 = '#tab5';
						$tablink6	 = '#tab6';
					}
					if ($tab == 5)
					{
						$disable1	 = $disable2	 = $disable3	 = $disable4	 = $disable5	 = '';
						$tablink1	 = '#tab1';
						$tablink2	 = '#tab2';
						$tablink3	 = '#tab3';
						$tablink4	 = '#tab4';
						$tablink5	 = '#tab5';
					}
					if ($tab == 4)
					{
						$disable1	 = $disable2	 = $disable3	 = $disable4	 = '';
						$tablink1	 = '#tab1';
						$tablink2	 = '#tab2';
						$tablink3	 = '#tab3';
						$tablink4	 = '#tab4';
					}
					if ($tab == 3)
					{
						$disable1	 = $disable2	 = $disable3	 = '';
						$tablink1	 = '#tab1';
						$tablink2	 = '#tab2';
						$tablink3	 = '#tab3';
					}
					if ($tab == 2)
					{
						$disable1	 = $disable2	 = '';
						$tablink1	 = '#tab1';
						$tablink2	 = '#tab2';
					}
					if ($tab == 1)
					{
						$disable1	 = '';
						$tablink1	 = '#tab1';
					}
					?>
                    <li class="t1 <?= ($tab == 1) ? 'active' : $disable1 ?>" id="li_tab1"><a href="<?= ($tab == 1) ? '#tab1' : $tablink1 ?>" data-toggle="tab" ><span class="rcorners2">1</span> Basic Details</a></li>
                    <li class="t1 <?= ($tab == 2) ? 'active' : $disable2 ?>" id='li_tab2'><a href="<?= ($tab == 2) ? '#tab2' : $tablink2 ?>" data-toggle="tab"><span class="rcorners2">2</span> Contact Details</a></li>
                    <li class="t1 <?= ($tab == 3) ? 'active' : $disable3 ?>" id='li_tab3'><a href="<?= ($tab == 3) ? '#tab3' : $tablink3 ?>" data-toggle="tab"><span class="rcorners2">3</span> Bank Details</a></li>
                    <li class="t2 <?= ($tab == 4) ? 'active' : $disable4 ?>" id='li_tab4'><a href="<?= ($tab == 4) ? '#tab4' : $tablink4 ?>" data-toggle="tab"><span class="rcorners2">4</span> Notification Details</a></li>
                    <li class="t1 <?= ($tab == 5) ? 'active' : $disable5 ?>" id='li_tab5'><a href="<?= ($tab == 5) ? '#tab5' : $tablink5 ?>" data-toggle="tab"><span class="rcorners2">5</span> Documents</a></li>
                    <li class="t1 <?= ($tab == 6) ? 'active' : $disable6 ?>" id='li_tab6'><a href="<?= ($tab == 6) ? '#tab6' : $tablink6 ?>" data-toggle="tab"><span class="rcorners2">6</span> Settings</a></li>
                </ul>
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
				<?php
				$form			 = $this->beginWidget('booster.widgets.TbActiveForm', array(
					'id'					 => 'agent-additional-details', 'enableClientValidation' => FALSE,
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


                <div class="tab-content col-xs-12" style="height: 100%">
                    <div class="tab-pane  <?= ($tab == 1) ? 'active' : 'fade' ?> in home-search" id="tab1">
                        <div class="panel panel-white panel-border">
                            <div class="panel-heading">
                                <span class="pull-left">Basic Information</span>
								<? $agtOrCorp		 = (Yii::app()->user->getCorpCode() != '') ? "Corporate Code: " . $model->agt_referral_code : "Agent ID: " . $model->agt_agent_id; ?>
                                <span class="pull-right"><?= ($model->agt_id > 0) ? $agtOrCorp : $agtOrCorp ?></span>
                            </div>
                            <div class="panel-body">
                                <div class="col-xs-12 col-sm-9">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-5">
											<?= $form->textFieldGroup($model, 'agt_company', array('label' => "Company name *", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Company Name')))) ?>  
                                        </div>
                                        <div class="col-xs-12 col-sm-5 col-sm-offset-1">
                                            <div class="form-group">
                                                <label class="control-label" for="exampleInputName6">Select Company type *</label>
												<?php
												$compTypesArr	 = VehicleTypes::model()->getJSON(Agents::model()->company_type);
												$this->widget('booster.widgets.TbSelect2', array(
													'model'			 => $model,
													'attribute'		 => 'agt_company_type',
													'val'			 => $model->agt_company_type,
													'asDropDownList' => FALSE,
													'options'		 => array('data' => new CJavaScriptExpression($compTypesArr)),
													'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Company Type', 'id' => 'Agents_agt_company_type')
												));
												?>  <span class="has-error"><? echo $form->error($model, 'agt_company_type'); ?></span></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-5">
											<?= $form->textFieldGroup($model, 'agt_owner_name', array('label' => "Proprietor/Director Name *", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Director Name')))) ?> 
                                        </div>
                                        <div class="col-xs-12 col-sm-5 col-sm-offset-1">
                                            <div class="form-group">
												<?php //$cityModel = Cities::getName($model->agt_city);   ?>
												<? //= $form->textFieldGroup($model, 'agt_city', array('label' => "City *", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Agent City', 'value' => $cityModel)))) ?>
                                                <label >City *</label>

												<?php
												$this->widget('booster.widgets.TbSelect2', array(
													'model'			 => $model,
													'attribute'		 => 'agt_city',
													'val'			 => $model->agt_city,
													'asDropDownList' => FALSE,
													'options'		 => array('data' => new CJavaScriptExpression('$cityList')),
													'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Enter Agent City')
												));
												?>
                                                <span class="has-error"><? echo $form->error($model, 'bkg_from_city_id'); ?></span>
                                            </div>
                                        </div> 
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-5">
											<?= $form->textFieldGroup($model, 'agt_fname', array('label' => "First name of primary contact *", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter First Name')))) ?> 
                                        </div>
                                        <div class="col-xs-12 col-sm-5 col-sm-offset-1">
											<?= $form->textFieldGroup($model, 'agt_lname', array('label' => " Last name of primary contact *", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Last Name')))) ?> 
                                        </div>
                                    </div>
                                    <div class="row pull-right"><button class="btn btn-danger" type="submit" name="tab1submit">NEXT >></button>
                                        <!--                            <a href="#tab2"  data-toggle="tab" class="btn btn-danger" onclick="activeTab(2);">NEXT >></a>-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane <?= ($tab == 2) ? 'active' : 'fade' ?> in home-search" id="tab2">
                        <div class="panel panel-white panel-border">
                            <div class="panel-heading">
                                <span class="pull-left">Contact Information <span class="text-sm" style="font-size: 12px">(we will use this information to contact you. All items marked with * are mandatory)</span></span>                  
                            </div>
                            <div class="panel-body">
                                <div class="col-xs-12 col-sm-9">
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <div class="form-group">
                                                <label>Email (Primary contact)</label>
                                                <div class="form-control">
													<?= $model->agt_email; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-5 col-sm-offset-1">
											<?= $form->textFieldGroup($model, 'agt_email_two', array('label' => "Email 1", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter alternative email')))) ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5">
											<?= $form->textFieldGroup($model, 'agt_phone', array('label' => "Phone (primary contact)*", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter primary phone number')))) ?>
                                        </div>
                                        <div class="col-sm-5 col-sm-offset-1">
											<?= $form->textFieldGroup($model, 'agt_phone_two', array('label' => "Phone 2", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter alternative phone number')))) ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5">
											<?= $form->textFieldGroup($model, 'agt_phone_three', array('label' => "Phone 3", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter second alternative phone number')))) ?>
                                        </div>
                                        <div class="col-sm-5 col-sm-offset-1">
											<?= $form->textFieldGroup($model, 'agt_fax', array('label' => "Fax Number", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Fax Number')))) ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5">
											<?= $form->textFieldGroup($model, 'agt_location', array('label' => " Closest Landmark", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter your location')))) ?>
                                        </div>
                                        <div class="col-sm-5 col-sm-offset-1">
											<?= $form->textFieldGroup($model, 'agt_address', array('label' => "Business Address", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter address')))) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-heading">
                                <span class="pull-left">Other Contacts <span class="text-sm" style="font-size: 12px">(here you can add other representatives for your business who can talk to us on your behalf)</span></span>                  
                            </div>
                            <div class="panel-body">
                                <div class="col-xs-12 col-sm-9">
                                    <div class="row">

                                        <div class="col-xs-12 table-responsive">
                                            <table class="table table-bordered" width="100%">
                                                <tr>
                                                    <th>Sl</th>
                                                    <th>Name</th>
                                                    <th>Phone</th>
                                                    <th>Email</th>
                                                </tr>
                                                <tr>
                                                    <td>1</td>
                                                    <td><?= $form->textFieldGroup($model, 'agt_other_con_name_one', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter name', 'style' => "width:80%; margin-left:15px;",)))) ?></td>
                                                    <td><?= $form->textFieldGroup($model, 'agt_other_con_phone_one', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter phone', 'style' => "width:80%; margin-left:15px;",)))) ?></td>
                                                    <td><?= $form->textFieldGroup($model, 'agt_other_con_email_one', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter email', 'style' => "width:80%; margin-left:15px;",)))) ?></td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td><?= $form->textFieldGroup($model, 'agt_other_con_name_two', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter name', 'style' => "width:80%; margin-left:15px;",)))) ?></td>
                                                    <td><?= $form->textFieldGroup($model, 'agt_other_con_phone_two', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter phone', 'style' => "width:80%; margin-left:15px;",)))) ?></td>
                                                    <td><?= $form->textFieldGroup($model, 'agt_other_con_email_two', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter email', 'style' => "width:80%; margin-left:15px;",)))) ?></td>
                                                </tr>
                                                <tr>
                                                    <td>3</td>
                                                    <td><?= $form->textFieldGroup($model, 'agt_other_con_name_three', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter name', 'style' => "width:80%; margin-left:15px;",)))) ?></td>
                                                    <td><?= $form->textFieldGroup($model, 'agt_other_con_phone_three', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter phone', 'style' => "width:80%; margin-left:15px;",)))) ?></td>
                                                    <td><?= $form->textFieldGroup($model, 'agt_other_con_email_three', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter email', 'style' => "width:80%; margin-left:15px;",)))) ?></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="row pull-right">
                                        <!--                            <button href="#tab3"  data-toggle="tab"  class="btn btn-danger"  onclick="activeTab(3);">NEXT >></button>-->
                                        <button class="btn btn-danger" type="submit" name="tab2submit">NEXT >></button>
                                    </div>
                                </div>  
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane <?= ($tab == 3) ? 'active' : 'fade' ?> in home-search" id="tab3">
                        <div class="panel panel-white panel-border">
                            <div class="panel-heading">
                                <span class="pull-left">Bank Details <span style="font-size: 12px">(we need this information for sending you payments)</span></span>                  
                            </div>
                            <div class="panel-body">
                                <div class="col-xs-9">
                                    <div class="row">
                                        <div class="col-sm-5"> 
											<?= $form->textFieldGroup($model, 'agt_bank', array('label' => "Bank name", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter bank name')))) ?>
                                        </div>
                                        <div class="col-sm-5 col-sm-offset-1">
											<?= $form->textFieldGroup($model, 'agt_bank_account', array('label' => "Bank account", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter bank account uumber')))) ?>
                                        </div>
                                        <div class="col-sm-5">
											<?= $form->textFieldGroup($model, 'agt_branch_name', array('label' => "Branch name", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter branch name')))) ?>
                                        </div>
										<!--                                        <div class="col-sm-5 col-sm-offset-1">-->
										<? //= $form->textFieldGroup($model, 'agt_swift_code', array('label' => "Swift code", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter swift code')))) ?>
										<!--                                        </div>-->
                                        <div class="col-sm-5  col-sm-offset-1">
											<?= $form->textFieldGroup($model, 'agt_ifsc_code', array('label' => "IFSC code", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter IFSC code')))) ?>
                                        </div>
                                    </div>
                                    <div class="row pull-right">
                                        <div class="col-xs-12">
                                            <!--                                <button href="#tab4"  data-toggle="tab"  class="btn btn-danger"  onclick="activeTab(4);">NEXT >></button>-->
                                            <button class="btn btn-danger" type="submit" name="tab3submit">NEXT >></button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="tab-pane <?= ($tab == 4) ? 'active' : 'fade' ?> in home-search" id="tab4">
                        <div class="panel panel-white panel-border">

                            <div class="panel-heading">
                                <span class="pull-left">Notification Details <span style="font-size: 12px">(use this to set the default notification settings for all bookings created by you.)</span></span>                  
                            </div>
                            <div class="panel-body">
                                <div class="col-xs-12">
                                    <div class="row">
                                        <div class="col-xs-12 ml10 n">ALL BOOKINGS COPIED TO <span style="font-size: 12px">(we will send copies of all bookings to this email address or sms). This is your copy.</span></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-3">
											<?= $form->textFieldGroup($model, 'agt_copybooking_name', array('label' => "Name", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Name')))) ?>
                                        </div>

                                        <div class="col-xs-6 col-sm-3 col-sm-offset-1"> 
											<?= $form->textFieldGroup($model, 'agt_copybooking_email', array('label' => "Email", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Email')))) ?>
                                        </div>
                                        <div class="col-xs-6 col-sm-3 col-sm-offset-1"> 
											<?= $form->textFieldGroup($model, 'agt_copybooking_phone', array('label' => "Phone", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Phone')))) ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 ml10 n">Should we send booking updates to travellers ?</div>
										<?= $form->radioButtonListGroup($model, 'agt_trvl_sendupdate', array('label' => '', 'widgetOptions' => array('htmlOptions' => [], 'data' => [1 => 'Yes', 2 => 'No']), 'inline' => true)) ?>
                                    </div>
                                    <div class="row mb10">NOTIFICATION OPTIONS</div>
                                    <div class="row mt15">
                                        <div class="col-xs-12 col-sm-9">
                                            <table class="table table-responsive table-bordered">
                                                <tr>
                                                    <th></th>
                                                    <th colspan="8">Advanced Portal</th>
                                                </tr>
                                                <tr>
                                                    <th colspan="1"></th>
                                                    <th colspan="4">Agent</th>
                                                    <th colspan="4">Traveller</th>    
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td>Email</td><td>SMS</td><td>App</td><td>WhatsApp</td>
                                                    <td>Email</td><td>SMS</td><td>App </td><td>WhatsApp</td>
                                                </tr>
												<?
												$AgentMessages	 = new AgentMessages();
												$arrEvents		 = AgentMessages::getEvents();
												foreach ($arrEvents as $key => $value)
												{

													$agtMsgModel = AgentMessages::model()->getByEventAndAgent($model->agt_id, $key);
													if ($agtMsgModel == '')
													{
														$agtMsgModel = new AgentMessages();
														if ($key == AgentMessages::BOOKING_CONF_WITH_PAYMENTINFO || $key == AgentMessages::PAYMENT_CONFIRM || $key == AgentMessages::PAYMENT_FAILED || $key == AgentMessages::BOOKING_EDIT || $key == AgentMessages::CAB_ASSIGNED || $key == AgentMessages::INVOICE || $key == AgentMessages::RATING_AND_REVIEWS || $key == AgentMessages::RESCHEDULE_REQUEST || $key == AgentMessages::CAB_DRIVER_DETAIL || $key == AgentMessages::CANCEL_TRIP)
														{
															$agtMsgModel->agt_agent_email	 = 1;
															$agtMsgModel->agt_agent_sms		 = 1;
															$agtMsgModel->agt_agent_whatsapp = 1;
														}
														if ($key == AgentMessages::BOOKING_CONF_WITHOUT_PAYMENTINFO || $key == AgentMessages::CAB_ASSIGNED)
														{
															$agtMsgModel->agt_trvl_email	 = 1;
															$agtMsgModel->agt_trvl_sms		 = 1;
															$agtMsgModel->agt_trvl_whatsapp	 = 1;
														}
													}

													if ($agtMsgModel != '')
													{
														$isAgentEmail	 = ($agtMsgModel->agt_agent_email == 1) ? true : false;
														$isAgentSMS		 = ($agtMsgModel->agt_agent_sms == 1) ? true : false;
														$isAgentApp		 = ($agtMsgModel->agt_agent_app == 1) ? true : false;
														$isAgentWhatsApp = ($agtMsgModel->agt_agent_whatsapp == 1) ? true : false;

														$isTrvlEmail	 = ($agtMsgModel->agt_trvl_email == 1) ? true : false;
														$isTrvlSMS		 = ($agtMsgModel->agt_trvl_sms == 1) ? true : false;
														$isTrvlApp		 = ($agtMsgModel->agt_trvl_app == 1) ? true : false;
														$isTrvlWhatsApp	 = ($agtMsgModel->agt_trvl_whatsapp == 1) ? true : false;

														$isRmEmail		 = ($agtMsgModel->agt_rm_email == 1) ? true : false;
														$isRmSMS		 = ($agtMsgModel->agt_rm_sms == 1) ? true : false;
														$isRmApp		 = ($agtMsgModel->agt_rm_app == 1) ? true : false;
														$isRmWhatsApp	 = ($agtMsgModel->agt_rm_whatsapp == 1) ? true : false;
													}
													?>  
													<tr>
														<th class="mr10"><? echo $arrEvents[$key]; ?></th>
														<td>  <?= $form->checkboxGroup($AgentMessages, 'agt_agent_email[' . $key . ']', ['label' => '', 'widgetOptions' => ['htmlOptions' => ['checked' => $isAgentEmail]], 'inline' => true]); ?></td>
														<td>  <?= $form->checkboxGroup($AgentMessages, 'agt_agent_sms[' . $key . ']', ['label' => '', 'widgetOptions' => ['htmlOptions' => ['checked' => $isAgentSMS]], 'inline' => true]); ?></td>
														<td>  <?= $form->checkboxGroup($AgentMessages, 'agt_agent_app[' . $key . ']', ['label' => '', 'widgetOptions' => ['htmlOptions' => ['checked' => $isAgentApp]], 'inline' => true]); ?></td>
														<td>  <?= $form->checkboxGroup($AgentMessages, 'agt_agent_whatsapp[' . $key . ']', ['label' => '', 'widgetOptions' => ['htmlOptions' => ['checked' => $isAgentWhatsApp]], 'inline' => true]); ?></td>
														<td>  <?= $form->checkboxGroup($AgentMessages, 'agt_trvl_email[' . $key . ']', ['label' => '', 'widgetOptions' => ['htmlOptions' => ['checked' => $isTrvlEmail]], 'inline' => true]); ?></td>
														<td>  <?= $form->checkboxGroup($AgentMessages, 'agt_trvl_sms[' . $key . ']', ['label' => '', 'widgetOptions' => ['htmlOptions' => ['checked' => $isTrvlSMS]], 'inline' => true]); ?></td>
														<td>  <?= $form->checkboxGroup($AgentMessages, 'agt_trvl_app[' . $key . ']', ['label' => '', 'widgetOptions' => ['htmlOptions' => ['checked' => $isTrvlApp]], 'inline' => true]); ?></td>
														<td>  <?= $form->checkboxGroup($AgentMessages, 'agt_trvl_whatsapp[' . $key . ']', ['label' => '', 'widgetOptions' => ['htmlOptions' => ['checked' => $isTrvlWhatsApp]], 'inline' => true]); ?></td>
													</tr>
													<?
												}
												?>
                                            </table>
                                        </div>

                                    </div>
                                    <div class="row pull-right">
                                        <div class="col-xs-12">
                                            <button class="btn btn-danger" type="submit" name="tab4submit">NEXT >></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane <?= ($tab == 5) ? 'active' : 'fade' ?> in home-search" id="tab5">
                        <div class="panel panel-white panel-border">
                            <div class="panel-heading">
                                <span class="pull-left">Documents <span style="font-size: 12px">(we require company address proof, owner photo, driver license and aadhar card)</span></span>                  
                            </div>
                            <div class="panel-body">
                                <div class="col-xs-9">
                                    <div class="row">
                                        <div class="col-sm-5">
											<?= $form->textFieldGroup($model, 'agt_trade_license', array('label' => "Trade license # (if any)", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter trade license number')))) ?> 
                                        </div>
                                        <div class="col-sm-5 col-sm-offset-1">
											<?= $form->fileFieldGroup($model, 'agt_owner_photo', array('label' => "Proprietor's / Director's photo", 'widgetOptions' => array())); ?>
											<?
											if ($model->agt_owner_photo != '')
											{
												?>
												<a href="<?= $model->agt_owner_photo ?>" target="_blank"><?= basename($model->agt_owner_photo) ?></a>
											<? } ?>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5"> 
											<?= $form->textFieldGroup($model, 'agt_pan_number', array('label' => "PAN Number *", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter PAN Number')))) ?>
                                        </div>
                                        <div class="col-sm-5 col-sm-offset-1">
											<?= $form->fileFieldGroup($model, 'agt_pan_card', array('label' => 'PAN Card *', 'widgetOptions' => array())); ?>
											<?
											if ($model->agt_pan_card != '')
											{
												?>
												<a href="<?= $model->agt_pan_card ?>" target="_blank"><?= basename($model->agt_pan_card) ?></a>
											<? } ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5"> 
											<?= $form->textFieldGroup($model, 'agt_aadhar_id', array('label' => "Aadhar ID", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter aadhar ID')))) ?>
                                        </div>
                                        <div class="col-sm-5 col-sm-offset-1">
											<?= $form->fileFieldGroup($model, 'agt_aadhar', array('label' => 'Scanned copy of aadhar card', 'widgetOptions' => array())); ?>
											<?
											if ($model->agt_aadhar != '')
											{
												?>
												<a href="<?= $model->agt_aadhar ?>" target="_blank"><?= basename($model->agt_aadhar) ?></a>
											<? } ?>
                                        </div>
                                    </div>
                                    <div class="row">

                                        <div class="col-sm-5">
											<?= $form->textFieldGroup($model, 'agt_voter_id', array('label' => "Voter ID", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter voter ID')))) ?>
                                        </div>
                                        <div class="col-sm-5  col-sm-offset-1">
											<?= $form->fileFieldGroup($AgentRel, 'arl_voter_id_path', array('label' => 'Scanned copy of voter card', 'widgetOptions' => array())); ?>
											<?
											if ($AgentRel->arl_voter_id_path != '')
											{
												?>
												<a href="<?= $AgentRel->arl_voter_id_path ?>" target="_blank"><?= basename($AgentRel->arl_voter_id_path) ?></a>
											<? } ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5">
											<?= $form->textFieldGroup($model, 'agt_driver_license', array('label' => "Driver license of primary contact", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter driver license number')))) ?>
                                        </div>
                                        <div class="col-sm-5 col-sm-offset-1">
											<? //= $form->textFieldGroup($model, 'agt_license_issued_state', array('label' => "Driver license issued by state", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter state name')))) ?>
                                            <label for="Agents_agt_license_issued_state">Driver license issued by state</label>
											<?
											$this->widget('ext.yii-selectize.YiiSelectize', array(
												'model'				 => $model,
												'attribute'			 => 'agt_license_issued_state',
												'useWithBootstrap'	 => true,
												"placeholder"		 => "State",
												'fullWidth'			 => true,
												'htmlOptions'		 => array(
												),
												'defaultOptions'	 => array(
													'create'			 => false,
													'persist'			 => false,
													'selectOnTab'		 => true,
													'createOnBlur'		 => true,
													'dropdownParent'	 => 'body',
													'optgroupValueField' => 'id',
													'optgroupLabelField' => 'id',
													'optgroupField'		 => 'id',
													'openOnFocus'		 => true,
													'labelField'		 => 'text',
													'valueField'		 => 'id',
													'searchField'		 => 'text',
													'closeAfterSelect'	 => true,
													'addPrecedence'		 => false,
													'onInitialize'		 => "js:function(){
                                                                                            this.load(function(callback){
                                                                                            var obj=this;    
                                                                                            xhr=$.ajax({
                                                                                                url:'" . CHtml::normalizeUrl(Yii::app()->createUrl('users/countrytostate', ['countryid' => 99])) . "',
                                                                                                dataType:'json',                  
                                                                                                success:function(results){
                                                                                                    obj.enable();
                                                                                                    callback(results);
                                                                                                     $('#Agents_agt_license_issued_state')[0].selectize.setValue({$model->agt_license_issued_state});
                                                                                                },                    
                                                                                                error:function(){
                                                                                                    callback();
                                                                                                    }});
                                                                                                });
                                                                                            }",
													'render'			 => "js:{
                                                                                   option: function(item, escape){
                                                                                                return '<div><span class=\"\">' + escape(item.text) +'</span></div>';
                                                                                    },
				                                                   option_create: function(data, escape){
                                                                                                 $('#countryname').val(escape(data.id));
                                                                                                 return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
                                                                                   }
                                                                                }",
												),
											));
											?>




                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5">

											<?= $form->datePickerGroup($model, 'agt_license_expiry_date', array('label' => 'Driver license Expiry Date', 'widgetOptions' => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'Expiry date', 'value' => date('d/m/Y'))), 'prepend' => '<i class="fa fa-calendar"></i>')); ?>
                                        </div>
                                        <div class="col-sm-5 col-sm-offset-1">
											<?= $form->fileFieldGroup($AgentRel, 'arl_driver_license_path', array('label' => 'Scanned copy of driver license of primary contact', 'widgetOptions' => array())); ?>
											<?
											if ($AgentRel->arl_driver_license_path != '')
											{
												?>
												<a href="<?= $AgentRel->arl_driver_license_path ?>" target="_blank"><?= basename($AgentRel->arl_driver_license_path) ?></a>
											<? } ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-5">
											<?= $form->textFieldGroup($model, 'agt_gstin', array('label' => "GST Identification Number", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter GSTIN Number')))) ?> 
                                        </div>
                                        <div class="col-sm-5   col-sm-offset-1">
											<?= $form->fileFieldGroup($model, 'agt_company_add_proof', array('label' => 'Scanned copy of company address proof', 'widgetOptions' => array())); ?>
											<?
											if ($model->agt_company_add_proof != '')
											{
												?>
												<a href="<?= $model->agt_company_add_proof ?>" target="_blank"><?= basename($model->agt_company_add_proof) ?></a>
											<? } ?>
                                        </div>

                                    </div>
                                    <div class="row pull-right">
                                        <div class="col-xs-12">
                                            <button type="submit" class="btn btn-danger" name="tab5submit">NEXT >></button>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane  <?= ($tab == 6) ? 'active' : 'fade' ?> in home-search" id="tab6">
                        <div class="panel panel-white panel-border">
                            <div class="panel-heading">
                                <span class="pull-left">Settings</span>      
                            </div>
                            <div class="row p10">
								<?
								$model->agt_booking_platform = ($model->agt_booking_platform == '') ? 1 : $model->agt_booking_platform;
								$arrDisabled				 = ($model->agt_approved == 1) ? [] : ['disabled' => 'disabled'];
								?>
								<?
								if ($model->agt_approved == 1)
								{
									?>
									<div class="col-xs-9">
										<?
									}
									else
									{
										?>
										<div class="col-xs-9" onclick="alert('Advanced portal can be used by approved travel partners only. Please submit papers to get approved.');">
										<? } ?>
                                        <div class="col-xs-12 ml10 n">Default booking platform</div>

										<?= $form->radioButtonListGroup($model, 'agt_booking_platform', array('label' => '', 'widgetOptions' => array('htmlOptions' => [] + $arrDisabled, 'data' => [1 => 'Gozo Spot Kiosk', 2 => 'Advanced Portal']), 'inline' => true)) ?>
										<div class="row">
											<div class="col-xs-12 mb20"><b>Partner preferences</b></div>
											<div class="col-sm-4">
												<?
												$checkedslip				 = ($model->agt_duty_slip_required == 1) ? "'checked'=>'checked'" : '';
												?>
												<?= $form->checkboxGroup($model, 'agt_duty_slip_required', ['label' => 'All receipts & duty slips required', 'widgetOptions' => array('htmlOptions' => [$checkedslip])]) ?>

											</div>
											<div class="col-sm-4">
												<?
												$checkedapp					 = ($model->agt_driver_app_required == 1) ? "'checked'=>'checked'" : '';
												?>
												<?= $form->checkboxGroup($model, 'agt_driver_app_required', ['label' => 'Driver app use is requred', 'widgetOptions' => array('htmlOptions' => [$checkedapp])]) ?>

											</div>
											<div class="col-sm-4">
												<?
												//$checkedotp	 = ($model->agt_otp_required == 1) ? "'checked'=>'checked'" : '';
												?>
												<? //= $form->checkboxGroup($model, 'agt_otp_required', ['label' => 'Otp Required', 'widgetOptions' => array('htmlOptions' => [$checkedotp])]) ?>
												<?php
												$model->agt_otp_not_required = ($model->agt_otp_required == 1) ? 0 : 1;
												?>
												<?= $form->checkboxListGroup($model, 'agt_otp_not_required', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'OTP not required from customer. Use Driver app to start, stop trip'), 'htmlOptions' => []), 'inline' => true)) ?>

											</div>
											<div class="col-sm-4">
												<?
												$checkedwater				 = ($model->agt_water_bottles_required == 1) ? "'checked'=>'checked'" : '';
												?>
												<?= $form->checkboxGroup($model, 'agt_water_bottles_required', ['label' => '2x 500ml water bottles required', 'widgetOptions' => array('htmlOptions' => [$checkedwater])]) ?>

											</div>
											<div class="col-sm-4">
												<?
												$checkedcash				 = ($model->agt_is_cash_required == 1) ? "'checked'=>'checked'" : '';
												?>
												<?= $form->checkboxGroup($model, 'agt_is_cash_required', ['label' => 'Do not ask customer for cash', 'widgetOptions' => array('htmlOptions' => [$checkedcash])]) ?>

											</div>
											<div class="col-sm-4">
												<? $model->agt_chk_others		 = ($model->agt_pref_req_other != '') ? 1 : 0; ?>
												<?= $form->checkboxGroup($model, 'agt_chk_others', ['label' => 'Other']) ?>
												<div id="othreq" style="display: block">
													<?= $form->textAreaGroup($model, 'agt_pref_req_other', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Other Requests"]), 'groupOptions' => ['class' => 'm0'])) ?>  
												</div>
											</div>
										</div>
									</div>
                                </div>
                                <div class="panel-heading">
                                    <span class="pull-left">Other Details <span style="font-size: 12px">(office use only)</span></span>                  
                                </div>
                                <div class="panel-body">
                                    <div class="col-xs-9">
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <div class="form-group">
                                                    <label>Account opening deposit</label>
                                                    <div class="form-control" style="background: #C0C0C0">
														<?= ($model->agt_opening_deposit != '') ? $model->agt_opening_deposit : 0; ?>
                                                    </div>
                                                </div>                  
                                            </div>
                                            <div class="col-sm-5 col-sm-offset-1">
                                                <div class="form-group">
                                                    <label>Credit Limit</label>
                                                    <div class="form-control"  style="background: #C0C0C0">
														<?= ($model->agt_credit_limit != '') ? $model->agt_credit_limit : 0; ?>
                                                    </div>
                                                </div>                                    
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="col-xs-9">
                                        <div class="row pull-right">
                                            <button class="btn btn-danger" type="submit" name="tab6submit">SAVE</button>                                  
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
					<?php $this->endWidget(); ?>
                </div>
            </div>
        </div>
    </div>
    <script>
		$(document).ready(function () {
<?
if ($_REQUEST['login'] == 1)
{
	?>

				setTimeout(
						function ()
						{
							alert("Your account is still pending approval. Please upload required papers in the partner profile section. You may create bookings temporarily but this may be blocked unless papers are submitted soon.");
						}, 1200);

<? } ?>
		});
		$('#<?= CHtml::activeId($model, "agt_chk_others") ?>').change(function ()
		{
			if ($('#<?= CHtml::activeId($model, "agt_chk_others") ?>').is(':checked'))
			{
				$("#othreq").show();
			} else
			{
				$("#othreq").hide();
			}
		});
    </script>